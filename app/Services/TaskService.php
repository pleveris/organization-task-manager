<?php

namespace App\Services;

use App\Enums\LogicTestEnum;
use App\Models\User;
use App\Models\Task;

class TaskService
{
    public function getStatus(Task $task): string
    {
        if($task->completed_at) {
            return 'Completed';
        }

        if (! $task->logic_test) {
            return 'Unsetup';
        }

        if ($task->logic_test === LogicTestEnum::AllSubtasksMustBeCompleted->value) {
            if ($task->subtasks->isEmpty()) {
                return 'unsetup';
            }

            $completed = [];

            foreach ($task->subtasks as $subtask) {
                if ($subtask->logic_test === LogicTestEnum::Expiration->value && $subtask->expiration_date < now()) {
                    return 'Logic not satisfied';
                } elseif ($subtask->logic_test === LogicTestEnum::Expiration->value && $subtask->expiration_date > now()) {
                    $completed[] = $subtask->id;
                }
            }

            if ($task->subtasks->count() === count($completed)) {
                return 'completed';
            }
        } elseif ($task->logic_test === LogicTestEnum::Expiration->value && ! $task->expiration_date) {
            return 'Unsetup';
        } elseif ($task->logic_test === LogicTestEnum::Expiration->value && $task->expiration_date < now()) {
            return 'Logic not satisfied';
        } elseif ($task->logic_test === LogicTestEnum::Expiration->value && $task->expiration_date > now()) {
            return 'Completed';
        }

        return 'unspecified';
    }

    public function getAssignees(Task $task): ?string
    {
        $assignees = '';

        $totalAssignees = $task->assignees->count();

        if ($totalAssignees === 0) {
            return null;
        } elseif ($totalAssignees === 1) {
            $user = $task->assignees->first();
            $assignees = $user->getFullNameAttribute();
        } elseif ($totalAssignees === 2) {
            $userIds = $task->assignees->pluck('id');
            $users = User::whereIn('id', $userIds)->get();

            $assignees = $users->map(function ($user) {
                return $user->getFullNameAttribute();
            })->implode(' and ');
        } elseif ($totalAssignees === 3) {
            $userIds = $task->assignees->pluck('id');
            $users = User::whereIn('id', $userIds)->get();
            $assignees = $users->map(function ($user, $key) use ($users) {
                if ($key === $users->count() - 1) {
                    return 'and ' . $user->getFullNameAttribute();
                }
                elseif ($key === $users->count() - 2) {
                    return $user->getFullNameAttribute() . ' ';
                }
                return $user->getFullNameAttribute() . ', ';
            })->implode('');
        } elseif ($totalAssignees > 3) {
            $userIds = $task->assignees->pluck('id');
            $users = User::whereIn('id', $userIds)->get();
            $firstNames = $users->slice(0, 3)->map(function ($user) {
                return $user->getFullNameAttribute();
            })->implode(', ');
            $remaining = $totalAssignees -= 3;
            $assignees = "$firstNames and $remaining more";
        }

        return $assignees;
    }
}
