@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ $user->full_name }}</div>

                <div class="card-body">
                    <p class="mb-0">{{ $user->email }}</p>
                </div>
            </div>
        </div>

                <div class="card-footer">
                    <p class="mb-0">Registered: {{ $user->created_at->format('M d, Y H:m') }}</p>
                </div>
            </div>

@endsection
