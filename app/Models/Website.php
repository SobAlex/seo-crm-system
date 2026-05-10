<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $url
 * @property int $project_id
 * @property int $website_type_id
 * @property int|null $previous_website_id
 * @property string|null $cms
 * @property string|null $region
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class Website extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'url', 'project_id', 'website_type_id', 'previous_website_id', 'cms', 'region'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function type()
    {
        return $this->belongsTo(WebsiteType::class, 'website_type_id');
    }

    public function previousWebsite()
    {
        return $this->belongsTo(Website::class, 'previous_website_id');
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }

    public function metrikaCounter()
    {
        return $this->hasOne(MetrikaCounter::class);
    }

    public function keywords()
    {
        return $this->hasMany(Keyword::class);
    }

    public function plannings()
    {
        return $this->hasMany(Planning::class);
    }
}
