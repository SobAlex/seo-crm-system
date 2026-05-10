<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $color
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'color'];

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_tag');
    }
}
