<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $filename
 * @property string $path
 * @property int $size
 * @property string $mime_type
 * @property string $entity_type
 * @property int $entity_id
 * @property bool $is_template
 * @property int $uploaded_by_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename', 'path', 'size', 'mime_type', 'entity_type', 'entity_id', 'is_template', 'uploaded_by_id'
    ];

    protected $casts = [
        'is_template' => 'boolean',
    ];

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by_id');
    }

    public function entity()
    {
        return $this->morphTo();
    }

    // Связь с Project для логотипа
    public function projectLogo()
    {
        return $this->hasOne(Project::class, 'logo_attachment_id');
    }

    // Связь с Client для логотипа
    public function clientLogo()
    {
        return $this->hasOne(Client::class, 'logo_attachment_id');
    }
}
