<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $project_id
 * @property string $title
 * @property string $period_start
 * @property string $period_end
 * @property array|null $content
 * @property string|null $pdf_path
 * @property int $generated_by_id
 * @property string|null $generated_at
 * @property string|null $sent_to_client_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'title', 'period_start', 'period_end', 'content', 'pdf_path',
        'generated_by_id', 'generated_at', 'sent_to_client_at'
    ];

    protected $casts = [
        'content' => 'array',
        'period_start' => 'date',
        'period_end' => 'date',
        'generated_at' => 'datetime',
        'sent_to_client_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by_id');
    }
}
