@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <form action="{{ route('tasks.update', $task) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">{{ $headline }}</div>

                    <div class="card-body">
                        @if($task->parent_id)
                        <div class="form-group">
                            <label for="parent_id">Belongs to task:</label>
                            <select class="form-control {{ $errors->has('parent_id') ? 'is-invalid' : '' }}"
                                    name="parent_id" id="parent_id">
                                @foreach($allTasks as $parentTask)
                                    <option
                                        value="{{ $parentTask->id }}" {{ (old('parent_id') ? old('parent_id') : $task->parent_id ?? '') == $parentTask->id ? 'selected' : '' }}>{{ $parentTask->title }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('parent_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('parent_id') }}
                                </div>
                            @endif
                            <span class="help-block"> </span>
                        </div>
                        @endif

                        <div class="form-group">
                            <label class="required" for="title">Title</label>
                            <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text"
                                   name="title" id="title" value="{{ old('title', $task->title) }}" required>
                            @if($errors->has('title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </div>
                            @endif
                            <span class="help-block"> </span>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                      rows="10" name="description"
                                      id="description">{{ old('description', $task->description) }}</textarea>
                            @if($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                            <span class="help-block"> </span>
                        </div>

                        {{-- <!-- <div class="form-group">
                            <label for="deadline">Deadline</label>
                            <input class="form-control {{ $errors->has('deadline') ? 'is-invalid' : '' }}" type="date"
                                   name="deadline" id="deadline" value="{{ old('deadline', $task->deadline) }}">
                            @if($errors->has('deadline'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('deadline') }}
                                </div>
                            @endif
                            <span class="help-block"> </span>
                        </div> --> --}}

                        <div class="form-group">
                            <label for="user_id">Assigned user</label>
                            <select class="form-control {{ $errors->has('user_id') ? 'is-invalid' : '' }}"
                                    name="user_id" id="user_id">
                                @foreach($users as $id => $entry)
                                    <option
                                        value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $task->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user') }}
                                </div>
                            @endif
                            <span class="help-block"> </span>
                        </div>

                        {{-- <!-- <div class="form-group">
                            <label for="organization_id">Assigned organization</label>
                            <select class="form-control {{ $errors->has('organization_id') ? 'is-invalid' : '' }}"
                                    name="organization_id" id="organization_id" required>
                                @foreach($organizations as $id => $entry)
                                    <option
                                        value="{{ $id }}" {{ (old('organization_id') ? old('organization_id') : $task->organization->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('organization_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('organization_id') }}
                                </div>
                            @endif
                            <span class="help-block"> </span>
                        </div> --> --}}

                        <div class="form-group">
                    <input class="form-control" type="hidden" name="organization_id" id="organization_id" value="{{ old('organization_id', $organizationId) }}">
                    <span class="help-block"> </span>
                </div>

                        <div class="form-group">
                            <label for="logic_test">Logic test:</label>
                            <select class="form-control {{ $errors->has('logic_test') ? 'is-invalid' : '' }}" name="logic_test"
                                    id="logic_test">
                                @foreach($logicTests as $test)
                                    <option
                                        value="{{ $test->value }}" {{ (old('logic_test') ? old('logic_test') : $task->logic_test ?? '') == $test->value ? 'selected' : '' }}>{{ ucfirst($test->value) }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('logic_test'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('logic_test') }}
                                </div>
                            @endif
                            <span class="help-block"> </span>
                        </div>

                        {{-- <!-- <div class="form-group">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="logic" id="{{ $task->logic }}"
                                    @if($task->logic === 1) checked
                                    @endif >

<label class="form-check-label" for="logic">
    {{ __('Logic test') }}
</label>
</div>
</div>
                        </div> --> --}}

                        <button class="btn btn-primary" type="submit">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- <!-- <div class="col-md-4">
            <div class="card">
                <div class="card-header">Files</div>
                <div class="card-body">
                    <form action="{{ route('media.upload', ['Task', $task]) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label class="required" for="file">File</label>
                            <input class="form-control {{ $errors->has('file') ? 'is-invalid' : '' }}" type="file"
                                   name="file" id="file">
                            @if($errors->has('file'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('file') }}
                                </div>
                            @endif
                            <span class="help-block"> </span>
                        </div>

                        <button class="btn btn-primary" type="submit">
                            Upload
                        </button>
                    </form>

                    <table class="table mt-4">
                        <thead>
                        <tr>
                            <th scope="col">File name</th>
                            <th scope="col">Size</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($task->getMedia() as $media)
                                <tr>
                                    <th scope="row">{{ $media->file_name }}</th>
                                    <td>{{ $media->human_readable_size }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" href="{{ route('media.download', $media) }}">
                                            Download
                                        </a>
                                        <form action="{{ route('media.delete', ['Project', $task, $media]) }}" method="POST" onsubmit="return confirm('Are your sure?');" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> --> --}}
    </div>
@endsection
