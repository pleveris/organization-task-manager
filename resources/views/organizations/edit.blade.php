@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <form action="{{ route('organizations.update', $organization) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">Edit organization</div>

                    <div class="card-body">
                        <div class="form-group">
                            <label class="required" for="title">Title</label>
                            <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text"
                                   name="title" id="title" value="{{ old('title', $organization->title) }}" required>
                            @if($errors->has('title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </div>
                            @endif
                            <span class="help-block"> </span>
                        </div>

                        <div class="form-group">
                            <label class="required" for="description">Description</label>
                            <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                      rows="10" name="description"
                                      id="description">{{ old('description', $organization->description) }}</textarea>
                            @if($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                            <span class="help-block"> </span>
                        </div>

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
                    <form action="{{ route('media.upload', ['Organization', $organization]) }}" method="POST"
                          enctype="multipart/form-data">
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
                        @foreach($organization->getMedia() as $media)
                            <tr>
                                <th scope="row">{{ $media->file_name }}</th>
                                <td>{{ $media->human_readable_size }}</td>
                                <td>
                                    <a class="btn btn-xs btn-info" href="{{ route('media.download', $media) }}">
                                        Download
                                    </a>
                                    <form action="{{ route('media.delete', ['Organization', $organization, $media]) }}"
                                          method="POST" onsubmit="return confirm('Are your sure?');"
                                          style="display: inline-block;">
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

@endsection
