<?php

namespace App\Traits;

trait Filter
{
    public function scopeFilterStatus($query, $filter)
    {
        if (in_array($filter, self::STATUS)) {
            return $query->where('status', $filter);
        }

        return $query;
    }

    public function scopeFilterAssigned($query, $filter)
    {
        if ($filter == auth()->user()->id) {
            return $query->where('user_id', $filter);
        }

        return $query;
    }
}
