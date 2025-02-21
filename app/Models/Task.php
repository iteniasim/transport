<?php

namespace App\Models;

use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;
    use SoftDeletes;

    const STATUS_PENDING = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_COMPLETED = 2;

    // Use guarded to protect specific attributes, or use fillable if you prefer whitelisting
    protected $guarded = [];

    /**
     * The user assigned to the task (optional).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The users interested in the task
     */
    public function requestedUsers()
    {
        return $this->belongsToMany(User::class, 'task_user')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * The user who created the task.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * The user who last updated the task.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Method to check if the task is unclaimed and pending
     */
    public function isAvailable(): bool
    {
        return $this->user_id === null && $this->status === self::STATUS_PENDING;
    }
}
