<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\Project;
use App\Models\WebsiteType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WebsiteController extends Controller
{
    public function index(Request $request)
    {
        $query = Website::with(['project', 'type']);

        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        return Inertia::render('Websites/Index', [
            'websites' => $query->latest()->paginate(10),
            'projectId' => $request->project_id,
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Websites/Create', [
            'projects' => Project::orderBy('title')->get(['id', 'title']),
            'websiteTypes' => WebsiteType::orderBy('title')->get(['id', 'title']),
            'selectedProjectId' => $request->project_id,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url|max:255',
            'project_id' => 'required|exists:projects,id',
            'website_type_id' => 'required|exists:website_types,id',
            'cms' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
        ]);

        $website = Website::create($validated);

        return redirect()->route('websites.index', ['project_id' => $website->project_id])
            ->with('success', 'Сайт создан');
    }

    public function show(Website $website)
    {
        $website->load(['project', 'type', 'metrikaCounter', 'keywords']);

        return Inertia::render('Websites/Show', [
            'website' => $website,
        ]);
    }

    public function edit(Website $website)
    {
        return Inertia::render('Websites/Edit', [
            'website' => $website,
            'projects' => Project::orderBy('title')->get(['id', 'title']),
            'websiteTypes' => WebsiteType::orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function update(Request $request, Website $website)
    {
        $validated = $request->validate([
            'url' => 'required|url|max:255',
            'project_id' => 'required|exists:projects,id',
            'website_type_id' => 'required|exists:website_types,id',
            'cms' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
        ]);

        $website->update($validated);

        return redirect()->route('websites.index', ['project_id' => $website->project_id])
            ->with('success', 'Сайт обновлён');
    }

    public function destroy(Website $website)
    {
        $projectId = $website->project_id;
        $website->delete();

        return redirect()->route('websites.index', ['project_id' => $projectId])
            ->with('success', 'Сайт удалён');
    }
}
