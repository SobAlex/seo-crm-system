<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $business_process_id
 * @property string $title
 * @property int $sort_order
 * @property bool $is_start_status
 * @property bool $is_end_status
 * @property string $color
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ProcessStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_process_id', 'title', 'sort_order', 'is_start_status', 'is_end_status', 'color'
    ];

    protected $casts = [
        'is_start_status' => 'boolean',
        'is_end_status' => 'boolean',
    ];

    public function businessProcess()
    {
        return $this->belongsTo(BusinessProcess::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'status_id');
    }
}
