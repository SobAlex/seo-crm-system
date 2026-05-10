<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $planning_id
 * @property int $week_number
 * @property string $week_start
 * @property string $week_end
 * @property int $days_in_week
 * @property float|null $actual_value
 * @property float|null $manual_value
 * @property string|null $source
 * @property string|null $synced_at
 * @property string|null $manual_override_at
 * @property int|null $manual_override_by_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class PlanningFact extends Model
{
    use HasFactory;

    protected $fillable = [
        'planning_id', 'week_number', 'week_start', 'week_end', 'days_in_week',
        'actual_value', 'manual_value', 'source', 'synced_at', 'manual_override_at', 'manual_override_by_id'
    ];

    protected $casts = [
        'week_start' => 'date',
        'week_end' => 'date',
        'actual_value' => 'decimal:2',
        'manual_value' => 'decimal:2',
        'synced_at' => 'datetime',
        'manual_override_at' => 'datetime',
    ];

    public function planning()
    {
        return $this->belongsTo(Planning::class);
    }

    public function manualOverrideBy()
    {
        return $this->belongsTo(User::class, 'manual_override_by_id');
    }
}
