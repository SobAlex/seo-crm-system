<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $project_id
 * @property int $contractor_id
 * @property int $role_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ProjectContractor extends Model
{
    use HasFactory;

    protected $table = 'project_contractor';

    protected $fillable = ['project_id', 'contractor_id', 'role_id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
