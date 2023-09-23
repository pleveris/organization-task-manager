@extends('layouts.app')

@section('content')
    <form action="" >

        <div class="card">
            <div class="card-header">Invite user to organization</div>

            <div class="card-body">
                <p>Please use the following URL to join the organization: </p>
                <div class="form-group">
                    <input  type="text" class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" readonly="readonly" name="url" id="url" value="{{ $url }}"/>
                    <span class="help-block"> </span>
                </div>

                <a class="btn btn-link" href="{{ route('organizations.index') }}">
                                        {{ __('Back to organizations') }}
                                    </a>

            </div>
        </div>
    </form>

@endsection
