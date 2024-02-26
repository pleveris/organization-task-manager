@extends('layouts.app')

@section('content')
        <div class="card">
            <div class="card-header">Invitation</div>

            <div class="card-body">
                <p>{{ $invitationCreator->first_name }} asks you to join the organization named {{ $organization->title }}.</p><br/>
                <p>Description: {{ $organization->description }}</p><br/>
                <p>Do you wish to get in?</p><br/>
                <a class="btn btn-link" href="{{ route('organizations.acceptInvitation', $code) }}">
                                        {{ __('Yes') }}
                                    </a>
                <a class="btn btn-link" href="{{ route('organizations.rejectInvitation', $code) }}">
                                        {{ __('No') }}
                                    </a>

                <a class="btn btn-link" href="{{ route('organizations.index') }}">
                                        {{ __('Back to organizations') }}
                                    </a>

            </div>
        </div>
    </form>

@endsection
