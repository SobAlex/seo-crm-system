<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int $client_id
 * @property string $status
 * @property string|null $start_date
 * @property string|null $end_date
 * @property int|null $logo_attachment_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'description', 'client_id', 'status', 'start_date', 'end_date', 'logo_attachment_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function websites()
    {
        return $this->hasMany(Website::class);
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }

    public function logo()
    {
        return $this->belongsTo(Attachment::class, 'logo_attachment_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user')->withPivot('role_id')->withTimestamps();
    }

    public function contractors()
    {
        return $this->belongsToMany(Contractor::class, 'project_contractor')->withPivot('role_id')->withTimestamps();
    }
}
