<?php

namespace App\Traits;

trait Filter
{
    public function scopeFilterStatus($query, $filter)
    {
        if ($filter === 'archived') {
            return $query->where('archived', true);
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
