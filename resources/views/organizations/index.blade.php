@extends('layouts.app')

@section('content')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('organizations.create') }}">
                Create organization
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Organizations list</div>

        <div class="card-body">
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

            <table class="table table-responsive-sm table-striped">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($organizations as $organization)
                    <tr>
                        <td><a href="{{ route('organizations.show', $organization) }}">{{ $organization->title }}</a></td>
                        <td>{{ $organization->description }}</td>
                        <td>
                        {{-- <!-- @if($currentOrganizationId !== $organization->id)
                            <form action="{{ route('organizations.setCurrent', $organization) }}" method="POST"
                                      style="display: inline-block;">
                                    <input type="hidden" name="_method" value="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-sm btn-danger" value="Set as current">
                                </form>
                            @endif --> --}}
                            @if($organization->createdByLoggedInUser())
                            <a class="btn btn-sm btn-info" href="{{ route('organizations.edit', $organization) }}">
                                Edit
                            </a>
                            @if(! $organization->primary)
                                <form action="{{ route('organizations.destroy', $organization) }}" method="POST"
                                      onsubmit="return confirm('Are you sure?');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-sm btn-danger" value="Delete">
                                </form>
                                @endif
                                @else
                                <form action="{{ route('organizations.leave', $organization) }}" method="POST"
                                      onsubmit="return confirm('Are you sure?');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-sm btn-danger" value="Leave">
                                </form>
@endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $organizations->withQueryString()->links() }}
        </div>
    </div>

@endsection
