<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int $business_process_id
 * @property array|null $tasks
 * @property array|null $default_settings
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class TrackTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'business_process_id', 'tasks', 'default_settings'
    ];

    protected $casts = [
        'tasks' => 'array',
        'default_settings' => 'array',
    ];

    public function businessProcess()
    {
        return $this->belongsTo(BusinessProcess::class);
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }

    public function taskTemplates()
    {
        return $this->belongsToMany(TaskTemplate::class, 'track_template_task', 'track_template_id', 'task_template_id');
    }
}
