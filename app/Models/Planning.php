<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $website_id
 * @property int|null $track_id
 * @property string $title
 * @property string $period_start
 * @property string $period_end
 * @property string $metric_type
 * @property string|null $metric_label
 * @property float $target_value
 * @property float $alert_threshold
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Planning extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id', 'track_id', 'title', 'period_start', 'period_end',
        'metric_type', 'metric_label', 'target_value', 'alert_threshold'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'target_value' => 'decimal:2',
        'alert_threshold' => 'decimal:2',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    public function facts()
    {
        return $this->hasMany(PlanningFact::class);
    }
}
