<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int $track_id
 * @property int|null $task_template_id
 * @property array|null $checklist
 * @property array|null $structure
 * @property array|null $files
 * @property int $status_id
 * @property string $priority
 * @property string|null $deadline
 * @property int|null $assignee_user_id
 * @property int|null $assignee_contractor_id
 * @property int $created_by_id
 * @property string|null $completed_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'track_id', 'task_template_id', 'checklist', 'structure', 'files',
        'status_id', 'priority', 'deadline', 'assignee_user_id', 'assignee_contractor_id',
        'created_by_id', 'completed_at'
    ];

    protected $casts = [
        'checklist' => 'array',
        'structure' => 'array',
        'files' => 'array',
        'deadline' => 'date',
        'completed_at' => 'datetime',
    ];

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    public function taskTemplate()
    {
        return $this->belongsTo(TaskTemplate::class);
    }

    public function status()
    {
        return $this->belongsTo(ProcessStatus::class, 'status_id');
    }

    public function assigneeUser()
    {
        return $this->belongsTo(User::class, 'assignee_user_id');
    }

    public function assigneeContractor()
    {
        return $this->belongsTo(Contractor::class, 'assignee_contractor_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'entity_id')->where('entity_type', 'task');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'task_tag');
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'task_keyword');
    }
}
