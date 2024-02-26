@extends('layouts.app')

@section('content')
        <div class="card">
            <div class="card-header">Invitation</div>

            <div class="card-body">
                <p>{{ $invitationCreator->first_name }} asks you to take on this task.<br/>
                <p>Title: {{ $task->title }}.</p><br/>
                @if($task->description)
                <p>Description: {{ $task->description }}</p><br/>
                @endif
                @if($task->deadline)
                <p>Deadline: {{ $task->deadline }}</p><br/>
                @endif
                @if($task->expiration_date)
                <p>Expiration date: {{ $task->expiration_date }}</p><br/>
                @endif

                <p>Would you like to solve it?</p><br/>
                <a class="btn btn-link" href="{{ route('tasks.acceptInvitation', $code) }}">
                                        {{ __('Yes') }}
                                    </a>
                <a class="btn btn-link" href="{{ route('tasks.rejectInvitation', $code) }}">
                                        {{ __('No') }}
                                    </a>

                <a class="btn btn-link" href="{{ route('tasks.index') }}">
                                        {{ __('Back to tasks') }}
                                    </a>

            </div>
        </div>
    </form>

@endsection
