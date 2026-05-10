<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property int|null $logo_attachment_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'phone', 'email', 'address', 'logo_attachment_id'
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function logo()
    {
        return $this->belongsTo(Attachment::class, 'logo_attachment_id');
    }
}
