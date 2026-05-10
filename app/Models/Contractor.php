<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $telegram
 * @property string $password
 * @property float $rating
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Contractor extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'telegram', 'password', 'rating'
    ];

    protected $hidden = [
        'password',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_contractor')->withPivot('role_id')->withTimestamps();
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assignee_contractor_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
