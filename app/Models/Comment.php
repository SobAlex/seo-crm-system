<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $text
 * @property int $task_id
 * @property int|null $user_id
 * @property int|null $contractor_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'task_id', 'user_id', 'contractor_id'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
    }

    public function tags()
    {
        return $this->belongsToMany(CommentTag::class, 'comment_comment_tag');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'entity_id')->where('entity_type', 'comment');
    }
}
