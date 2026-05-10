<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $website_id
 * @property string $counter_id
 * @property string|null $token
 * @property string|null $token_expires_at
 * @property array|null $goals
 * @property string|null $last_sync_at
 * @property string|null $sync_status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class MetrikaCounter extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id', 'counter_id', 'token', 'token_expires_at', 'goals', 'last_sync_at', 'sync_status'
    ];

    protected $casts = [
        'goals' => 'array',
        'token_expires_at' => 'datetime',
        'last_sync_at' => 'datetime',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }
}
