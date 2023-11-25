@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Organization</div>

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

                <div class="card-body d-flex justify-content-between">
                    <div>
                        <div class="text-primary">Title: {{ $organization->title }}</div>
                    </div>
                    <div>
                        <p class="mb-0">Description: {{ $organization->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-accent-primary">
                <div class="card-body">
                    <p class="mb-0">Created at {{ $organization->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card card-accent-primary">
                <div class="card-header">Tasks</div>

                <div class="card-body">
                    @if($organization->tasks->count())
                        <table class="table table-sm table-responsive-sm">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Assigned to</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($organization->tasks as $task)
                                    <tr>
                                        <td><a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a></td>
                                        <td>{{ $task?->user?->first_name }}</td>
                                        <td>{{ $task?->deadline }}</td>
                                        <td>{{ $task?->status }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-info" href="{{ route('tasks.edit', $task) }}">
                                                Edit
                                            </a>
                                            @if($task->createdByLoggedInUser())
                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST"
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
                            No tasks added to this organization.
                            {{-- <!-- <a href="{{ route('tasks.create') }}">Create task now.</a> --> --}}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card card-accent-primary">
                <div class="card-header">Users</div>

                <div class="card-body">
                    @if($organization->users->count())
                        <table class="table table-sm table-responsive-sm">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($organization->users as $user)
                                    <tr>
                                        <td><a href="{{ route('users.show', $user) }}">{{ $user->id }}</a></td>
                                        <td>{{ $user->getFullNameAttribute() }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <form action="{{ route('organizations.removeUser', [$organization, $user]) }}" method="POST"
                                                      onsubmit="return confirm('Are you sure?');"
                                                      style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-sm btn-danger" value="Delete">
                                                </form>
</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info" role="alert">
                            There are no users in this organization.
                        </div>
                    @endif

                    <div class="alert alert-info" role="alert">
                            <a href="{{ route('organizations.invite', $organization) }}">Invite user</a>
                        </div>
                </div>
            </div>
        </div>


    </div>

@endsection
