@extends('layouts.app')

@section('content')

    <div class="row">
        @if($task->user)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Assigned user</div>

                <div class="card-body">
                    <p class="mb-0">{{ $task->user->full_name }}</p>
                    <p class="mb-0">{{ $task->user->email }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="col-md-8">
            <div class="card card-accent-primary">
                <div class="card-header">{{ $task->title }}</div>

                <div class="card-body">
                    <p>{{ $task->description }}</p>
                </div>

                <div class="card-footer">
                    @if($task->parent_id)
                    <p class="mb-0">Belongs to task: {{ $parentTask->title }}</p>
                    @endif
                    <p class="mb-0">Created: {{ $task->created_at->format('M d, Y H:i') }}</p>
                    <p class="mb-0">Updated: {{ $task->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-accent-primary">
                {{-- <!-- <div class="card-header">Information</div> --> --}}

                <div class="card-body">
                {{-- <!-- <p class="mb-0">Deadline: {{ $task->deadline }}</p> --> --}}
                {{-- <!-- <p class="mb-0">Created at {{ $task->created_at->format('M d, Y') }}</p> --> --}}
                    <p class="mb-0">Status: {{ ucfirst($status) }}</p>
                    <p class="mb-0">Owner: {{ $createdUser->getFullNameAttribute() }}</p>
                    @if($task->logic_test === \App\Enums\LogicTestEnum::Expiration->value && ! empty($task->expiration_date))
                    <p class="mb-0">Expiration date: {{ $task->expiration_date }}</p>
                        @endif
                </div>
            </div>
        </div>

        @if($task->logic_test === \App\Enums\LogicTestEnum::AllSubtasksMustBeCompleted->value)
        <div class="col-md-12">
            <div class="card card-accent-primary">
                <div class="card-header">Subtasks</div>

                <div class="card-body">
                    @if($task->subtasks->count())
                        <table class="table table-sm table-responsive-sm">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($task->subtasks as $subtask)
                                    <tr>
                                        <td><a href="{{ route('tasks.show', $subtask) }}">{{ $subtask->title }}</a></td>
                                        <td>{{ $subtask?->description }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-info" href="{{ route('tasks.edit', $subtask) }}">
                                Edit
                    </a>

                                            @if($subtask->createdByLoggedInUser())
                                            <form action="{{ route('tasks.destroy', $subtask) }}" method="POST"
                                                      onsubmit="return confirm('Are you sure?');"
                                                      style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-sm btn-danger" value="Delete">
                                                </form>
                                                @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info" role="alert">
                            No subtasks are assigned to this task.
                        </div>
                    @endif

                    <div class="alert alert-info" role="alert">
                            <a href="{{ route('tasks.addSubtask', $task) }}">Add subtask</a>
                        </div>
                </div>
            </div>
        </div>
        @endif

        <div class="col-md-12">
            <div class="card card-accent-primary">
                <div class="card-header">Logs</div>

                <div class="card-body">
                    @if($task->logs->count())
                        <table class="table table-sm table-responsive-sm">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Message</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($task->logs as $log)
                                    <tr>
                                        <td> {{ $log->createdUser()->getFullNameAttribute() }}</td>
                                        <td>{{ $log->message }}</td>
                                        <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info" role="alert">
                            No logs for this task are available.
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
@endsection
