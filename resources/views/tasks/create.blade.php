@extends('layouts.app')

@section('content')
    <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card">
            <div class="card-header">{{ $headline }}</div>

            <div class="card-body">
                <div class="form-group">
                    <label class="required" for="title">Title</label>
                    <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title') }}" required>
                    @if($errors->has('title'))
                        <div class="invalid-feedback">
                            {{ $errors->first('title') }}
                        </div>
                    @endif
                    <span class="help-block"> </span>
</div>

@if($parentId)
<div class="form-group">
    <input class="form-control" type="hidden" name="parent_id" id="parent_id" value="{{ old('parent_id', $parentId) }}">
    <span class="help-block"> </span>
    </div>
@endif

                <div class="form-group">
                    <input class="form-control" type="hidden" name="organization_id" id="organization_id" value="{{ old('organization_id', $currentOrganizationId) }}">
                    <span class="help-block"> </span>
                </div>

                <button class="btn btn-primary" type="submit">
                    Save
                </button>
            </div>
        </div>
    </form>

@endsection
