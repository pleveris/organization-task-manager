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
        $tasks = Task::with(['user', 'organization'])
        ->filterStatus(request('status'))
        ->filterAssigned(request('assigned'))
        ->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $users = User::all()->pluck('full_name', 'id');
        $organizations = Organization::all()->pluck('title', 'id');

        return view('tasks.create', compact('users', 'organizations'));
    }

    public function store(CreateTaskRequest $request)
    {
        $task = Task::create($request->validated());

        $user = User::find($request->user_id);

        //$user->notify(new TaskAssigned($task));

        //Mail::to($user)->send(new MailTaskAssigned($task));

        return redirect()->route('tasks.index');
    }

    public function show(Task $task)
    {
        $task->load('user');

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $users = User::all()->pluck('full_name', 'id');
        $organizations = Organization::all()->pluck('title', 'id');

        return view('tasks.edit', compact('task', 'users', 'organizations'));
    }

    public function update(EditTaskRequest $request, Task $task)
    {
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
        abort_if(Gate::denies('delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
