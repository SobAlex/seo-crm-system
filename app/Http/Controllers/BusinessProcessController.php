<?php

namespace App\Http\Controllers;

use App\Models\BusinessProcess;
use App\Models\ProcessStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BusinessProcessController extends Controller
{
    public function index()
    {
        return Inertia::render('Settings/BusinessProcesses/Index', [
            'businessProcesses' => BusinessProcess::with('statuses')->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Settings/BusinessProcesses/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:business_processes',
            'description' => 'nullable|string',
        ]);

        $businessProcess = BusinessProcess::create($validated);

        return redirect()->route('business-processes.index')
            ->with('success', 'Бизнес-процесс создан');
    }

    public function show(BusinessProcess $businessProcess)
    {
        $businessProcess->load('statuses');

        return Inertia::render('Settings/BusinessProcesses/Show', [
            'businessProcess' => $businessProcess,
        ]);
    }

    public function edit(BusinessProcess $businessProcess)
    {
        return Inertia::render('Settings/BusinessProcesses/Edit', [
            'businessProcess' => $businessProcess,
        ]);
    }

    public function update(Request $request, BusinessProcess $businessProcess)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:business_processes,title,' . $businessProcess->id,
            'description' => 'nullable|string',
        ]);

        $businessProcess->update($validated);

        return redirect()->route('business-processes.index')
            ->with('success', 'Бизнес-процесс обновлён');
    }

    public function destroy(BusinessProcess $businessProcess)
    {
        $businessProcess->delete();

        return back()->with('success', 'Бизнес-процесс удалён');
    }

    // Добавление статуса в процесс
    public function addStatus(Request $request, BusinessProcess $businessProcess)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'color' => 'nullable|string',
            'sort_order' => 'integer',
        ]);

        $status = $businessProcess->statuses()->create($validated);

        return back()->with('success', 'Статус добавлен');
    }
}
