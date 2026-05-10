<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $color
 * @property bool $is_system
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class CommentTag extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'color', 'is_system'];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    public function comments()
    {
        return $this->belongsToMany(Comment::class, 'comment_comment_tag');
    }
}
