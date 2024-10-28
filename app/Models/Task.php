<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'priority',
        'type',
        'due_date',
        'start_date',
        'end_date',
        'days_of_week',
        'time',
        'is_completed',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
