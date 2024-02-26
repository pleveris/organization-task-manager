@extends('layouts.app')

@section('content')
    <form action="{{ route('tasks.inviteAssignee') }}" method="POST">
        @csrf

        <div class="card">
            <div class="card-header">{{ $headline }}</div>

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


                <div class="form-group">
                    <label class="required" for="user_id">Assignee</label>
                    <select class="form-control {{ $errors->has('user_id') ? 'is-invalid' : '' }}"
                                    name="user_id" id="user_id" required="required">
                                @foreach($users as $id => $entry)
                                    <option
                                        value="{{ $id }}" {{ (old('user_id') ? old('user_id') : '' ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user_id') }}
                                </div>
                            @endif
                            <span class="help-block"> </span>
                        </div>

<div class="form-group">
    <input class="form-control" type="hidden" name="task_id" id="task_id" value="{{ old('task_id', $task->id) }}">
    <span class="help-block"> </span>
    </div>

                <button class="btn btn-primary" type="submit">
                    Save
                </button>
            </div>
        </div>
    </form>

@endsection
