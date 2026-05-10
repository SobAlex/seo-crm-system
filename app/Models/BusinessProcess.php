<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class BusinessProcess extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description'];

    public function statuses()
    {
        return $this->hasMany(ProcessStatus::class);
    }

    public function trackTemplates()
    {
        return $this->hasMany(TrackTemplate::class);
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }
}
