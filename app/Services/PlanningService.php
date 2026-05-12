<?php

namespace App\Services;

use App\Models\Planning;
use App\Models\PlanningFact;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PlanningService
{
    /**
     * Генерирует недели для плана по правилам:
     * - Неделя 1: с period_start до первого воскресенья
     * - Полные недели: Пн-Вс
     * - Остаток: плюсуется к последней неделе
     */
    public function generateWeeks(Carbon $start, Carbon $end): Collection
    {
        $weeks = collect();
        $current = $start->copy();

        // Первая неделя: от period_start до первого воскресенья
        $firstSunday = $current->copy()->endOfWeek(Carbon::SUNDAY);
        if ($firstSunday->greaterThan($end)) {
            $firstSunday = $end->copy();
        }

        $weeks->push([
            'week_number' => 1,
            'week_start' => $current->copy(),
            'week_end' => $firstSunday->copy(),
            'days_in_week' => $firstSunday->diffInDays($current) + 1,
        ]);

        $current = $firstSunday->copy()->addDay(); // понедельник
        $weekNumber = 2;

        // Полные недели
        while ($current->lte($end)) {
            $weekEnd = $current->copy()->endOfWeek(Carbon::SUNDAY);
            if ($weekEnd->greaterThan($end)) {
                $weekEnd = $end->copy();
            }

            $weeks->push([
                'week_number' => $weekNumber,
                'week_start' => $current->copy(),
                'week_end' => $weekEnd->copy(),
                'days_in_week' => $weekEnd->diffInDays($current) + 1,
            ]);

            $current = $weekEnd->copy()->addDay();
            $weekNumber++;
        }

        return $weeks;
    }

    /**
     * Создаёт записи фактов для плана
     */
    public function generatePlanningFacts(Planning $planning): void
    {
        $weeks = $this->generateWeeks(
            Carbon::parse($planning->period_start),
            Carbon::parse($planning->period_end)
        );

        foreach ($weeks as $week) {
            PlanningFact::updateOrCreate(
                [
                    'planning_id' => $planning->id,
                    'week_number' => $week['week_number'],
                ],
                [
                    'week_start' => $week['week_start'],
                    'week_end' => $week['week_end'],
                    'days_in_week' => $week['days_in_week'],
                    'actual_value' => null,
                    'manual_value' => null,
                    'source' => null,
                ]
            );
        }
    }

    /**
     * Рассчитать прогресс плана
     */
    public function calculateProgress(Planning $planning): array
    {
        $facts = $planning->facts()->orderBy('week_number')->get();

        $cumulativeTarget = 0;
        $cumulativeFact = 0;
        $totalDays = 0;
        $daysPassed = 0;

        $today = Carbon::today();

        foreach ($facts as $fact) {
            $targetPerDay = $planning->target_value / $fact->days_in_week;
            $weekTarget = $targetPerDay * $fact->days_in_week;
            $cumulativeTarget += $weekTarget;

            $value = $fact->manual_value ?? $fact->actual_value ?? 0;
            $cumulativeFact += $value;

            $weekEnd = Carbon::parse($fact->week_end);
            if ($weekEnd->lt($today)) {
                $daysPassed += $fact->days_in_week;
            } elseif ($weekEnd->gte($today) && Carbon::parse($fact->week_start)->lte($today)) {
                $daysPassed += $today->diffInDays(Carbon::parse($fact->week_start)) + 1;
            }

            $totalDays += $fact->days_in_week;
        }

        $progressPercent = $cumulativeTarget > 0 ? ($cumulativeFact / $cumulativeTarget) * 100 : 0;

        // Прогноз: (факт / прошедшие дни) * общее количество дней
        $forecast = $daysPassed > 0 ? ($cumulativeFact / $daysPassed) * $totalDays : 0;

        return [
            'cumulative_target' => round($cumulativeTarget, 2),
            'cumulative_fact' => round($cumulativeFact, 2),
            'progress_percent' => round($progressPercent, 2),
            'days_passed' => $daysPassed,
            'total_days' => $totalDays,
            'forecast' => round($forecast, 2),
            'status' => $this->getStatus($progressPercent, $planning->alert_threshold),
        ];
    }

    /**
     * Определить статус прогресса
     */
    protected function getStatus(float $progressPercent, float $alertThreshold): string
    {
        if ($progressPercent >= 100) {
            return 'completed';
        }

        $expectedProgress = 100; // можно сделать динамическим по дням
        $deviation = $expectedProgress - $progressPercent;

        if ($deviation > $alertThreshold) {
            return 'critical';
        } elseif ($deviation > 0) {
            return 'warning';
        }

        return 'on_track';
    }
}
