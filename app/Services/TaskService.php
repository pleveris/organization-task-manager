<?php

namespace App\Services;

use App\Enums\LogicTestEnum;
use App\Models\Task;

class TaskService
{
    public function getStatus(Task $task)
    {
        if(! $task->logic_test) {
            return 'Unsetup';
        }

        if($task->logic_test === LogicTestEnum::AllSubtasksMustBeCompleted->value) {
        if($task->subtasks->isEmpty()) {
            return 'unsetup';
        }

        $completed = [];

        foreach($task->subtasks as $subtask) {
            if($subtask->logic_test === LogicTestEnum::Expiration->value && $subtask->expiration_date < now()) {
                return 'Logic not satisfied';
            }
            else if($subtask->logic_test === LogicTestEnum::Expiration->value && $subtask->expiration_date > now()) {
                $completed[] = $subtask->id;
            }
        }

        if($task->subtasks->count() === count($completed)) {
            return 'completed';
        }
    }
    else if($task->logic_test === LogicTestEnum::Expiration->value && ! $task->expiration_date) {
        return 'Unsetup';
    }
    else if($task->logic_test === LogicTestEnum::Expiration->value && $task->expiration_date < now()) {
        return 'Logic not satisfied';
    }
    else if($task->logic_test === LogicTestEnum::Expiration->value && $task->expiration_date > now()) {
        return 'Completed';
    }

        return 'unspecified';
    }
}
