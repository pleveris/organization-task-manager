@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Notifications</div>

        <div class="card-body">
            <div style="margin-bottom: 10px;" class="row">
                @if ($unreadNotifications->count())
                    <div class="col-lg-12">
                        <form action="{{ route('notifications.destroy') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="submit" class="btn btn-success" value="Mark all as read">
                        </form>
                    </div>
                @endif
            </div>
            <table class="table table-responsive-sm table-striped">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Title</th>
                        <th>Sent at</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if ($allNotifications->count())
                        @foreach($allNotifications as $notification)
                            <tr>
                                <td>{{ ucfirst(str_replace('_', ' ', $notification->data['type'])) }}</td>
                                <td>{{ $notification->data['title'] }}</td>
                                <td>{{ $notification->created_at->diffForHumans() }}</td>
                                <td>
                                    @if($notification->unread())
                                    <form action="{{ route('notifications.update', $notification) }}" method="POST"
                                          style="display: inline-block;">
                                        @csrf
                                        @method('PUT')
                                        <input type="submit" class="btn btn-sm btn-info" value="Mark as read">
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">
                                <div class="alert alert-info" role="alert">
                                    You have no notifications.
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
