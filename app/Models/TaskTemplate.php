<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property array|null $checklist
 * @property array|null $structure
 * @property array|null $files
 * @property string $default_priority
 * @property int|null $default_deadline_days
 * @property string|null $default_assignee_role
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class TaskTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'checklist', 'structure', 'files',
        'default_priority', 'default_deadline_days', 'default_assignee_role'
    ];

    protected $casts = [
        'checklist' => 'array',
        'structure' => 'array',
        'files' => 'array',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function trackTemplates()
    {
        return $this->belongsToMany(TrackTemplate::class, 'track_template_task', 'task_template_id', 'track_template_id');
    }
}
