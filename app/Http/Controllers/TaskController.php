<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Organization;
use Illuminate\Http\Response;
use App\Notifications\TaskAssigned;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\EditTaskRequest;
use App\Http\Requests\CreateTaskRequest;
use App\Mail\TaskAssigned as MailTaskAssigned;

class TaskController extends Controller
{
    public function index()
    {
        $createdIds = Task::where('create_user_id', currentUser()->id)
        ->get()
        ->pluck('id')
        ->all();
        $memberIds = Task::where('user_id', currentUser()->id)
        ->whereNotNull('organization_id')
        ->get()
        ->pluck('user_id')
        ->all();

        if(! $createdIds && ! $memberIds) {
            $tasks = Task::whereNull('id')->paginate(10);
            return view('tasks.index', compact('tasks'));
        }

        $tasks = Task::with(['user', 'organization'])
        ->when($createdIds, function ($query) use($createdIds) {
            $query->whereIn('id', $createdIds);
        })
        ->when($memberIds, function ($query) use($memberIds) {
            $query->whereIn('user_id', $memberIds);
        })
        ->filterStatus(request('status'))
        //->filterAssigned(request('assigned'))
        ->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $createdIds = Organization::where('create_user_id', currentUser()->id)
        ->get()
        ->pluck('id')
        ->all();
        $memberIds = User::where('id', currentUser()->id)
        ->whereNotNull('organization_id')
        ->get()
        ->pluck('organization_id')
        ->all();
        $organizations = Organization::with('users')
        ->whereIn('id', array_merge($createdIds, $memberIds))
        ->get();

        $userIds = [];

        foreach($organizations as $organization) {
            $userIds[] = $organization->create_user_id;
            $ids = $organization->users->pluck('id');

            foreach($ids as $id) {
                $userIds[] = $id;
            }
                }

                $organizations = $organizations->pluck('title', 'id');

                if(! $userIds) {
                    return redirect()->back()->with('error', 'You must add at least one person to the organization in order to create a task!');
                }

        $users = User::whereIn('id', $userIds)->get()->pluck('full_name', 'id');

        if($organizations->isEmpty()) {
            return redirect()->back()->with('error', 'You don\'t have any organizations!');
        }

        return view('tasks.create', compact('users', 'organizations'));
    }

public function store(CreateTaskRequest $request)
    {
        $task = Task::create($request->validated());

        $user = User::find($request->user_id);

        if($user->id !== currentUser()->id) {
            $user->notify(new TaskAssigned($task));
            // Mail::to($user)->send(new MailTaskAssigned($task));
        }

        return redirect()->route('tasks.index');
    }

    public function show(Task $task)
    {
        if(! $task->create_user_id === currentUser()->id
        || ! $task->user_id === currentUser()->id) {
            return redirect()->route('tasks.index')->with('error', 'You cannot view this task.');
        }

        $task->load('user');

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        if(! $task->create_user_id === currentUser()->id
        || ! $task->user_id === currentUser()->id) {
            return redirect()->route('tasks.index')->with('error', 'You cannot view this task.');
        }

        $createdIds = Organization::where('create_user_id', currentUser()->id)
        ->get()
        ->pluck('id')
        ->all();
        $memberIds = User::where('id', currentUser()->id)
        ->whereNotNull('organization_id')
        ->get()
        ->pluck('organization_id')
        ->all();
        $organizations = Organization::with('users')
        ->whereIn('id', array_merge($createdIds, $memberIds))
        ->get();

        $userIds = [];

        foreach($organizations as $organization) {
            $userIds[] = $organization->create_user_id;
            $ids = $organization->users->pluck('id');

            foreach($ids as $id) {
                $userIds[] = $id;
            }
                }

                $organizations = $organizations->pluck('title', 'id');

                if(! $userIds) {
                    return redirect()->back()->with('error', 'There are no users in your organizations!');
                }

        $users = User::whereIn('id', $userIds)->get()->pluck('full_name', 'id');

        if($organizations->isEmpty()) {
            return redirect()->back()->with('error', 'You don\'t have any organizations!');
        }

        return view('tasks.edit', compact('task', 'users', 'organizations'));
    }

    public function update(EditTaskRequest $request, Task $task)
    {
        if(! $task->create_user_id === currentUser()->id
        || ! $task->user_id === currentUser()->id) {
            return redirect()->route('tasks.index')->with('error', 'You cannot view this task.');
        }

        if ($task->user_id !== $request->user_id) {
            $user = User::find($request->user_id);

            //$user->notify(new TaskAssigned($task));

            //Mail::to($user)->send(new MailTaskAssigned($task));
        }

        $task->update($request->validated());

        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task)
    {
        //abort_if(Gate::denies('delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if(! $task->create_user_id === currentUser()->id
        || ! $task->user_id === currentUser()->id) {
            return redirect()->route('tasks.index')->with('error', 'You cannot view this task.');
        }

        try {
            $task->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if($e->getCode() === '23000') {
                return redirect()->back()->with('status', 'Task belongs to organization. Cannot delete.');
            }
        }

        return redirect()->route('tasks.index');
    }
}
