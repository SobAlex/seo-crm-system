<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int $project_id
 * @property int|null $website_id
 * @property int $business_process_id
 * @property int|null $track_template_id
 * @property int $sort_order
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Track extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'project_id', 'website_id', 'business_process_id',
        'track_template_id', 'sort_order', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function businessProcess()
    {
        return $this->belongsTo(BusinessProcess::class);
    }

    public function trackTemplate()
    {
        return $this->belongsTo(TrackTemplate::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function plannings()
    {
        return $this->hasMany(Planning::class);
    }
}
