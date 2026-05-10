<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $task_id
 * @property float $amount
 * @property string $currency
 * @property string|null $description
 * @property int|null $contractor_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id', 'amount', 'currency', 'description', 'contractor_id'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
    }
}
