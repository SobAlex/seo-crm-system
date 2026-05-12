<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Track;
use App\Models\User;
use App\Models\Contractor;
use App\Models\ProcessStatus;
use App\Services\TemplateService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    protected $templateService;

    public function __construct(TemplateService $templateService)
    {
        $this->templateService = $templateService;
    }

    public function index(Request $request)
    {
        $query = Task::with(['track', 'status', 'assigneeUser', 'assigneeContractor']);

        if ($request->track_id) {
            $query->where('track_id', $request->track_id);
        }

        if ($request->status_id) {
            $query->where('status_id', $request->status_id);
        }

        if ($request->assignee_type === 'user' && $request->assignee_id) {
            $query->where('assignee_user_id', $request->assignee_id);
        }

        if ($request->assignee_type === 'contractor' && $request->assignee_id) {
            $query->where('assignee_contractor_id', $request->assignee_id);
        }

        return Inertia::render('Tasks/Index', [
            'tasks' => $query->latest()->paginate(20),
            'trackId' => $request->track_id,
            'filters' => $request->only(['status_id', 'assignee_type', 'assignee_id']),
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Tasks/Create', [
            'tracks' => Track::orderBy('title')->get(['id', 'title', 'project_id']),
            'users' => User::orderBy('name')->get(['id', 'name']),
            'contractors' => Contractor::orderBy('name')->get(['id', 'name']),
            'statuses' => ProcessStatus::orderBy('sort_order')->get(['id', 'title', 'color']),
            'selectedTrackId' => $request->track_id,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'track_id' => 'required|exists:tracks,id',
            'status_id' => 'required|exists:process_statuses,id',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date',
            'assignee_user_id' => 'nullable|exists:users,id',
            'assignee_contractor_id' => 'nullable|exists:contractors,id',
            'checklist' => 'nullable|array',
            'structure' => 'nullable|array',
        ]);

        $validated['created_by_id'] = auth()->id();

        if ($request->assignee_user_id) {
            $validated['assignee_contractor_id'] = null;
        }

        Task::create($validated);

        return redirect()->route('tasks.index', ['track_id' => $request->track_id])
            ->with('success', 'Задача создана');
    }

    public function show(Task $task)
    {
        $task->load([
            'track',
            'status',
            'assigneeUser',
            'assigneeContractor',
            'createdBy',
            'comments' => function ($q) {
                $q->with(['user', 'contractor', 'tags'])->latest();
            },
            'attachments',
            'expenses',
            'tags',
            'keywords',
        ]);

        return Inertia::render('Tasks/Show', [
            'task' => $task,
        ]);
    }

    public function edit(Task $task)
    {
        return Inertia::render('Tasks/Edit', [
            'task' => $task,
            'tracks' => Track::orderBy('title')->get(['id', 'title', 'project_id']),
            'users' => User::orderBy('name')->get(['id', 'name']),
            'contractors' => Contractor::orderBy('name')->get(['id', 'name']),
            'statuses' => ProcessStatus::orderBy('sort_order')->get(['id', 'title', 'color']),
        ]);
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'track_id' => 'required|exists:tracks,id',
            'status_id' => 'required|exists:process_statuses,id',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date',
            'assignee_user_id' => 'nullable|exists:users,id',
            'assignee_contractor_id' => 'nullable|exists:contractors,id',
            'checklist' => 'nullable|array',
            'structure' => 'nullable|array',
        ]);

        if ($request->assignee_user_id) {
            $validated['assignee_contractor_id'] = null;
        }

        $status = ProcessStatus::find($request->status_id);
        if ($status && $status->is_end_status && !$task->completed_at) {
            $validated['completed_at'] = now();
        } elseif (!$status->is_end_status) {
            $validated['completed_at'] = null;
        }

        $task->update($validated);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Задача обновлена');
    }

    public function destroy(Task $task)
    {
        $trackId = $task->track_id;
        $task->delete();

        return redirect()->route('tasks.index', ['track_id' => $trackId])
            ->with('success', 'Задача удалена');
    }
}
