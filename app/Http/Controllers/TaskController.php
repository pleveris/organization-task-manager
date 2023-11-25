<?php

namespace App\Http\Controllers;

use App\Notifications\InvitationAccepted;
use App\Notifications\InvitationRejected;
use App\Notifications\TaskAssigned;
use App\Notifications\InvitationToTaskSent;
use App\Models\User;
use App\Models\Task;
use App\Models\Organization;
use App\Models\InvitationToTask;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\EditTaskRequest;
use App\Http\Requests\CreateTaskRequest;
use App\Mail\TaskAssigned as MailTaskAssigned;
use App\Mail\InvitedToTask as MailInvitedToTask;

class TaskController extends Controller
{
    public function index()
    {
        $currentOrganizationId = currentUser()->current_organization_id;
        
        if(! $currentOrganizationId) {
            return redirect()->back()->with('error', 'No default organization chosen!');
        }

        /*$createdIds = $currentOrganizationId ? Task::where('create_user_id', currentUser()->id)
        *->where('organization_id', $currentOrganizationId)
        *->get()
        *->pluck('id')
        *->all()
        *: Task::where('create_user_id', currentUser()->id)
        *->whereNotNull('organization_id')
        *->get()
        *->pluck('id')
        *->all();

        *$memberIds = $currentOrganizationId ? Task::where('user_id', currentUser()->id)
        *->where('organization_id', $currentOrganizationId)
        *->get()
        *->pluck('user_id')
        *->all()
        *: Task::where('user_id', currentUser()->id)
        *->whereNotNull('organization_id')
        *->get()
        *->pluck('user_id')
        *->all();

        *if(! $createdIds && ! $memberIds) {
            *$tasks = Task::whereNull('id')->paginate(10);
            *return view('tasks.index', compact('tasks'));
        *}*/

        $tasks = Task::with(['user', 'organization'])
        ->where('organization_id', $currentOrganizationId)
        //->whereNull('parent_id')
        //->when($createdIds, function ($query) use ($createdIds) {
            //$query->whereIn('id', $createdIds);
        //})
        //->when($memberIds, function ($query) use ($memberIds) {
            //$query->whereIn('user_id', $memberIds);
        //})
        //->filterStatus(request('status'))
        //->filterAssigned(request('assigned'))
        ->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $currentOrganizationId = currentUser()->current_organization_id;
        /*$createdIds = Organization::where('create_user_id', currentUser()->id)
        *->get()
        *->pluck('id')
        *->all();
        *$memberIds = User::where('id', currentUser()->id)
        *->whereNotNull('organization_id')
        *->get()
        *->pluck('organization_id')
        *->all();
        *$organizations = Organization::with('users')
        *->whereIn('id', $memberIds)
        *->get();
        *$organizations = Organization::with('users')
        *->where('id', $currentOrganizationId)
        *->get();

        *$userIds = [];

        *foreach($organizations as $organization) {
            *$ids = $organization->users->pluck('id');

            *foreach($ids as $id) {
                *$userIds[] = $id;
            *}
        *}

        *$organizations = $organizations->pluck('title', 'id');

        *if(! $userIds) {
            *return redirect()->back()->with('error', 'You must add at least one person to the organization in order to create a task!');
        *}

        *$users = User::whereIn('id', $userIds)->get()->pluck('full_name', 'id');

        *if($organizations->isEmpty()) {
            *return redirect()->back()->with('error', 'You don\'t have any organizations!');
        *}*/

        $organization = Organization::find($currentOrganizationId);
        $users = $organization->users->pluck('full_name', 'id');
        $parentId = null;
        $headline = 'Create task';

        return view('tasks.create', compact('users', 'currentOrganizationId', 'parentId', 'headline'));
    }

    public function createSubtask(Task $task)
    {
        $currentOrganizationId = currentUser()->current_organization_id;
        $organization = Organization::find($currentOrganizationId);
        $users = $organization->users->pluck('full_name', 'id');
        $parentId = $task->id ?? null;
        $headline = 'Add subtask to: ' . $task->title;

        return view('tasks.create', compact('users', 'currentOrganizationId', 'parentId', 'headline'));
    }

    public function store(CreateTaskRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = null;
        $data['status'] = 'unsetup';
        $data['organization_id'] = currentUser()->current_organization_id;
        $task = Task::create($data);

        if($request->has('parent_id')) {
            $parentTask = Task::find($request->input('parent_id'));

            if($parentTask) {
                $parentTask->update(['hidden' => 0]);
            }
            }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully');
    }

    public function show(Task $task)
    {
        if(! $task->create_user_id === currentUser()->id
        || ! $task->user_id === currentUser()->id) {
            return redirect()->route('tasks.index')->with('error', 'You cannot view this task.');
        }

        $task->load('user');

        $task->load('subtasks');

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        if(! $task->create_user_id === currentUser()->id
        || ! $task->user_id === currentUser()->id) {
            return redirect()->route('tasks.index')->with('error', 'You cannot view this task.');
        }

        if($task->hidden) {
            return redirect()->route('tasks.addSubtask', $task);
        }

        $organizationId = $task->organization_id;
        $organization = Organization::findOrFail($organizationId);
        $users = $organization->users->pluck('full_name', 'id');
        $users->forget($task->create_user_id);

        //if (! $task->parent_id) {
            //$task->load('subtasks');
        //}

        $headline = $task->parent_id ? 'Edit subtask' : 'Edit task';
        $allTasks = $task->parent_id ?
        Task::with(['user', 'organization'])
        ->where('organization_id', currentUser()->current_organization_id)
        //->whereNull('parent_id')
        ->paginate(10)
        : [];

        return view('tasks.edit', compact('task', 'users', 'organizationId', 'headline', 'allTasks'));
    }

    public function update(EditTaskRequest $request, Task $task)
    {
        if(! $task->create_user_id === currentUser()->id
        || ! $task->user_id === currentUser()->id) {
            return redirect()->route('tasks.index')->with('error', 'You cannot view this task.');
        }

        if ($task->user_id !== $request->user_id) {
            $assignee = User::find($request->user_id) ?: currentUser(); //workaround
            $code = md5(uniqid(rand(), true));
            $invitation = InvitationToTask::create([
                'task_id' => $task->id,
                'code'            => $code,
            ]);

            $assignee = $request->user_id ? User::find($request->user_id) : currentUser(); //workaround
    
            if($assignee->id !== currentUser()->id) {
                //$user->notify(new TaskAssigned($task));
                //$assignee->notify(new InvitationToTaskSent(['title' => $message]));
                 Mail::to($assignee)->send(new MailInvitedToTask($task, $invitation));
            }
    
    

            //$user->notify(new TaskAssigned($task));

            //Mail::to($user)->send(new MailTaskAssigned($task));
        }

        $data = $request->validated();
        $data['user_id'] = null;
        $data['hidden'] = 0;
        $data['logic'] = 0;

        if($request->has('logic')) {
            $data['logic'] = 1;
        }

        $task->update($data);

        if($task->logic === 1 && $task->subtasks->isEmpty()) {
            $task->update(['hidden' => 1]);
            return redirect()->route('tasks.addSubtask', $task);
        }

        return redirect()->route('tasks.show', $task);
    }

    public function destroy(Task $task)
    {
        //abort_if(Gate::denies('delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if(! $task->create_user_id === currentUser()->id
        || ! $task->user_id === currentUser()->id) {
            return redirect()->route('tasks.index')->with('error', 'You cannot view this task.');
        }

        try {
            foreach($task->subtasks as $subtask) {
                $subtask->delete();
            }
            $task->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if($e->getCode() === '23000') {
                return redirect()->back()->with('status', 'Task belongs to organization. Cannot delete.');
            }
        }

        return redirect()->route('tasks.index');
    }

    public function inviteUser(Task $task)
    {
        $code = md5(uniqid(rand(), true));
        InvitationToTask::create([
            'task_id' => $task->id,
            'code'            => $code,
        ]);

        $url = route('tasks.handle-invitation', $code);

        return view('tasks.invite', compact('url'));
    }

    public function acceptInvitation(string $code)
    {
        $invitation = InvitationToTask::where('code', $code)->first();

        if(! $invitation) {
            return redirect()->route('tasks.index')->with('error', 'Sorry, invitation does not exist!');
        }

        $task = Task::find($invitation->task_id);

        if(! $task) {
            return redirect()->route('tasks.index')->with('error', 'Sorry, task does not exist!');
        }

        if($task->create_user_id === currentUser()->id) {
            return redirect()->route('tasks.index')->with('error', 'Sorry, you are a creator of the task, you cannot invite yourself!');
        }

        if(currentUser()->tasks->pluck('id')->contains($task->id)) {
            return redirect()->route('tasks.index')->with('error', 'Sorry, you already have this task in your list!');
        }

        $task->update([
            'user_id' => currentUser()->id,
        ]);

        $notifyUser = User::find($invitation->create_user_id);
        $message = currentUser()->getFullNameAttribute() . ' has accepted the invitation to the task ' . $task->title . '.';
        $notifyUser->notify(new InvitationAccepted(['title' => $message]));
        $invitation->delete();
        return redirect()->route('tasks.show', $task)->with('success', 'Invitation accepted.');
    }

    public function rejectInvitation(string $code)
    {
        $invitation = InvitationToTask::where('code', $code)->first();

        if(! $invitation) {
            return redirect()->route('tasks.index')->with('error', 'Sorry, invitation does not exist!');
        }

        $task = Task::find($invitation->task_id);

        if(! $task) {
            return redirect()->route('tasks.index')->with('error', 'Sorry, task does not exist!');
        }

        if($task->create_user_id === currentUser()->id) {
            return redirect()->route('tasks.index')->with('error', 'Sorry, you are a creator of this task, you cannot reject this invitation!');
        }

        if(currentUser()->tasks->pluck('id')->contains($task->id)) {
            return redirect()->route('tasks.index')->with('error', 'Sorry, you already have this task in your list!');
        }

        $notifyUser = User::find($invitation->create_user_id);
        $message = currentUser()->getFullNameAttribute() . ' has rejected the invitation to the task ' . $task->title . '.';
        $notifyUser->notify(new InvitationRejected(['title' => $message]));

        $invitation->delete();
        return redirect()->route('organizations.index')->with('success', 'Invitation rejected.');
    }

    public function handleInvitation(string $code)
    {
        $invitation = InvitationToTask::where('code', $code)->first();

        if(! $invitation) {
            return redirect()->route('tasks.index')->with('error', 'Sorry, invitation does not exist!');
        }

        $task = Task::find($invitation->task_id);

        if(! $task) {
            return redirect()->route('tasks.index')->with('error', 'Sorry, organization does not exist!');
        }

        if($task->create_user_id === currentUser()->id) {
            return redirect()->route('tasks.index')->with('error', 'Sorry, you are a creator of the task, you cannot invite yourself!');
        }

        return view('tasks.handle_invitation', compact('code', 'task'));
    }
}
