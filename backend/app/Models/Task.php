<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string id
 * @property string user_id
 * @property string name
 * @property string category_id
 * @property boolean completed
 * @property \DateTime due_date
 * @property User $user
 * @property TaskCategory $category
 */
class Task extends Model
{
    use HasUuids;

    protected $fillable = [
        'description',
        'due_date',
        'completed',
        'user_id',
        'category_id',
        'name'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TaskCategory::class);
    }
}
