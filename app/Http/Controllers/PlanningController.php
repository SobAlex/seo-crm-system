<?php

namespace App\Http\Controllers;

use App\Models\Planning;
use App\Models\Website;
use App\Models\Track;
use App\Services\PlanningService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlanningController extends Controller
{
    protected $planningService;

    public function __construct(PlanningService $planningService)
    {
        $this->planningService = $planningService;
    }

    public function index(Request $request)
    {
        $query = Planning::with(['website.project', 'track']);

        if ($request->website_id) {
            $query->where('website_id', $request->website_id);
        }

        $plannings = $query->get();

        // Добавляем расчёт прогресса для каждого плана
        foreach ($plannings as $planning) {
            $planning->progress = $this->planningService->calculateProgress($planning);
        }

        return Inertia::render('Plannings/Index', [
            'plannings' => $plannings,
            'websites' => Website::with('project')->get(),
            'tracks' => Track::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Plannings/Create', [
            'websites' => Website::with('project')->get(),
            'tracks' => Track::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'website_id' => 'required|exists:websites,id',
            'track_id' => 'nullable|exists:tracks,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'metric_type' => 'required|string',
            'target_value' => 'required|numeric|min:0',
            'alert_threshold' => 'numeric|min:0|max:100',
        ]);

        $planning = Planning::create($validated);

        // Генерация недель в planning_facts
        $this->planningService->generatePlanningFacts($planning);

        return redirect()->route('plannings.index')
            ->with('success', 'План создан');
    }

    public function show(Planning $planning)
    {
        $planning->load(['website.project', 'track', 'facts']);
        $planning->progress = $this->planningService->calculateProgress($planning);

        return Inertia::render('Plannings/Show', [
            'planning' => $planning,
        ]);
    }

    public function edit(Planning $planning)
    {
        return Inertia::render('Plannings/Edit', [
            'planning' => $planning,
            'websites' => Website::with('project')->get(),
            'tracks' => Track::all(),
        ]);
    }

    public function update(Request $request, Planning $planning)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'website_id' => 'required|exists:websites,id',
            'track_id' => 'nullable|exists:tracks,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'metric_type' => 'required|string',
            'target_value' => 'required|numeric|min:0',
            'alert_threshold' => 'numeric|min:0|max:100',
        ]);

        $planning->update($validated);

        // Перегенерация недель
        $planning->facts()->delete();
        $this->planningService->generatePlanningFacts($planning);

        return redirect()->route('plannings.index')
            ->with('success', 'План обновлён');
    }

    public function destroy(Planning $planning)
    {
        $planning->delete();

        return back()->with('success', 'План удалён');
    }

    // Ручной ввод факта
    public function storeManualFact(Request $request, Planning $planning)
    {
        $validated = $request->validate([
            'week_number' => 'required|integer',
            'manual_value' => 'required|numeric|min:0',
        ]);

        $fact = $planning->facts()->where('week_number', $validated['week_number'])->first();

        if ($fact) {
            $fact->update([
                'manual_value' => $validated['manual_value'],
                'manual_override_at' => now(),
                'manual_override_by_id' => auth()->id(),
                'source' => 'manual',
            ]);
        }

        return back()->with('success', 'Фактическое значение сохранено');
    }

    // Синхронизация с Метрикой (ручной запуск)
    public function sync(Planning $planning)
    {
        // Здесь будет вызов MetrikaService
        // Временно просто заглушка
        return back()->with('success', 'Синхронизация запущена');
    }
}
