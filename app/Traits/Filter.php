<?php

namespace App\Traits;

trait Filter
{
    public function scopeFilterStatus($query)
    {
        $filter = currentUser()->task_filter;

        if ($filter === 'archived') {
            return $query->where('archived', true);
        } else if($filter === 'active') {
            return $query->where('archived', false);
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
