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

            {{-- <!-- <div class="d-flex justify-content-end">
                <form action="{{ route('tasks.index') }}" method="GET">
                    <div class="form-group row">
                        <label for="status" class="col-form-label">Status:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="status" id="status" onchange="this.form.submit()">
                                <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>All</option>
                                @foreach(App\Models\Task::STATUS as $status)
                                    <option
                                        value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- <!-- <div class="form-group row">
                        <label for="assigned" class="col-form-label">Assigned to:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="assigned" id="assigned" onchange="this.form.submit()">
                                <option value="0" {{ request('assigned') == '-' ? 'selected' : '' }}>-</option>
                                    <option
                                        value="{{ auth()->user()->id }}" {{ request('assigned') == auth()->user()->id ? 'selected' : '' }}>Only to me</option>
                            </select>
                        </div>
                    </div>

                </form>
            </div> --> --}}

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
                        <td>{{ $task?->user?->first_name }}</td>
                        <td>{{ $statuses->get($task->id) }}</td>
                        <td>
                            @if($task->hidden)
                            <a class="btn btn-sm btn-info" href="{{ route('tasks.addSubtask', $task) }}">
                                Add subtask
                    </a>
                            @else
                            <a class="btn btn-sm btn-info" href="{{ route('tasks.edit', $task) }}">
                                Edit
                    </a>
                    @if($task->createdByLoggedInUser())
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this task with all of its subtasks? This action cannot be undone! ?');" style="display: inline-block;">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="submit" class="btn btn-sm btn-danger" value="Delete">
                            </form>
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
