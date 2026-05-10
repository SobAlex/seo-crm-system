<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $telegram
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
#[Fillable(['name', 'email', 'password', 'phone', 'telegram'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Проекты, в которых участвует пользователь
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user')
            ->withPivot('role_id')
            ->withTimestamps();
    }

    /**
     * Задачи, назначенные на пользователя
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assignee_user_id');
    }

    /**
     * Задачи, созданные пользователем
     */
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by_id');
    }

    /**
     * Комментарии пользователя
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Загруженные файлы
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'uploaded_by_id');
    }
}
