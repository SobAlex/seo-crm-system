<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property array|null $permissions
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Role extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'permissions'];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function projectUsers()
    {
        return $this->hasMany(ProjectUser::class);
    }

    public function projectContractors()
    {
        return $this->hasMany(ProjectContractor::class);
    }
}
