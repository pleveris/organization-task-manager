<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{
    public function getStatus(Task $task)
    {
        if($task->subtasks->isEmpty()) {
            return 'unsetup';
        }

        return 'unspecified';
    }
}
