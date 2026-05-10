<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $website_id
 * @property string $keyword
 * @property int|null $frequency
 * @property int|null $difficulty
 * @property int|null $current_position
 * @property int|null $target_position
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Keyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id', 'keyword', 'frequency', 'difficulty', 'current_position', 'target_position'
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_keyword');
    }
}
