@php
use App\Models\User;
        $currentOrganizationId = currentUser()->current_organization_id;
        $organizations = User::where('id', currentUser()->id)
        ->firstOrFail()
        ->organizations()
        ->paginate(10);

@endphp
<ul class="c-sidebar-nav">
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link" href="{{ route('home') }}">
            <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt"></i>
            Dashboard
        </a>
    </li>
    @can('manage users')
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->is("users/*") ? "c-active" : "" }}" href="{{ route('users.index') }}">
                <i class="c-sidebar-nav-icon fas fa-fw fa-user-alt"></i>
                Users
            </a>
        </li>
    @endcan
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link {{ request()->is("organizations/*") ? "c-active" : "" }}" href="{{ route('organizations.index') }}">
            <i class="c-sidebar-nav-icon fas fa-fw fa-copy"></i>
            Organizations
        </a>
    </li>
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link {{ request()->is("tasks/*") ? "c-active" : "" }}" href="{{ route('tasks.index') }}">
            <i class="c-sidebar-nav-icon fas fa-fw fa-tasks"></i>
            Tasks
        </a>
    </li>
    <li class="c-sidebar-nav-divider"></li>
    <li class="c-sidebar-nav-item mt-auto"></li>
    <li class="c-sidebar-nav-item"><a href="#" class="c-sidebar-nav-link"
                                      onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
            <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt"></i>
            Logout</a>
    </li>
    <li class="c-sidebar-nav-item">
    <div class="d-flex justify-content-end">
                <form action="{{ route('organizations.index') }}" method="GET">
                    <div class="form-group row">
                        <label for="current" class="col-form-label">Current organization:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="current" id="current" onchange="this.form.submit()">
                                @foreach($organizations as $organization)
                                    <option
                                        value="{{ $organization->id }}" {{ $currentOrganizationId == $organization->id ? 'selected' : '' }}>{{ ucfirst($organization->title) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </form>
            </div>
</li>
</ul>
