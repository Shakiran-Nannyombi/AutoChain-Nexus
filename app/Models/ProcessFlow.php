<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessFlow extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'current_stage',
        'status',
        'entered_stage_at',
        'completed_stage_at',
        'failure_reason',
    ];

    /**
     * Check if the process flow is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the process flow is failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if the process flow is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Calculate the duration of the current stage in minutes.
     */
    public function getDurationInMinutes(): ?int
    {
        if (!$this->entered_stage_at) {
            return null;
        }

        $start = \Carbon\Carbon::parse($this->entered_stage_at);
        $end = null;

        if ($this->isCompleted() && $this->completed_stage_at) {
            $end = \Carbon\Carbon::parse($this->completed_stage_at);
        } elseif ($this->isFailed()) {
            $end = \Carbon\Carbon::parse($this->updated_at);
        } elseif ($this->isInProgress()) {
            $end = \Carbon\Carbon::now();
        }

        if ($end) {
            return $end->diffInMinutes($start);
        }

        return null;
    }
} 