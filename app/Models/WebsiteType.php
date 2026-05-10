<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property array|null $default_metrics
 * @property string|null $icon
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class WebsiteType extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'default_metrics', 'icon', 'sort_order'
    ];

    protected $casts = [
        'default_metrics' => 'array',
    ];

    public function websites()
    {
        return $this->hasMany(Website::class);
    }
}
