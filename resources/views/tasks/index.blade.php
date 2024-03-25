@extends('layouts.app')

@section('content')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('tasks.create') }}">
                Create task
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Tasks list</div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-danger" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="d-flex justify-content-end">
                <form action="{{ route('tasks.index') }}" method="GET">
                    <div class="form-group row">
                        <label for="status" class="col-form-label">Status:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="status" id="status" onchange="this.form.submit()">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                    </div>

                </form>
            </div>

            <table class="table table-responsive-sm table-striped">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Assigned to</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($tasks as $task)
                    <tr>
                        @if($task->hidden)
                        <td>{{ $task->title }}</td>
                        @else
                        <td><a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a></td>
                        @endif
                        <td>{{ $taskAssignees->get($task->id) }}</td>
                        <td>{{ $taskStatuses->get($task->id) }}</td>
                        <td>
                            @if($task->hidden)
                            <a class="btn btn-sm btn-info" href="{{ route('tasks.addSubtask', $task) }}">
                                Add subtask
                    </a>
                            @else
                            <a class="btn btn-sm btn-info" href="{{ route('tasks.edit', $task) }}">
                                Edit
                    </a>
                    @if(! $task->completed_at)
                    <form action="{{ route('tasks.complete', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to complete this task?');" style="display: inline-block;">
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="submit" class="btn btn-sm btn-danger" value="Complete">
                            </form>
@endif
                    {{-- <!-- @if($task->createdByLoggedInUser())
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this task with all of its subtasks? This action cannot be undone! ?');" style="display: inline-block;">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="submit" class="btn btn-sm btn-danger" value="Delete">
                            </form>
                            @endif --> --}}
                            @if($task->createdByLoggedInUser())
                            @if(! $task->archived)
                            <form action="{{ route('tasks.archive', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to archive this task?');" style="display: inline-block;">
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="submit" class="btn btn-sm btn-danger" value="Archive">
                            </form>
                            @endif
                            @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $tasks->withQueryString()->links() }}
        </div>
    </div>

@endsection
