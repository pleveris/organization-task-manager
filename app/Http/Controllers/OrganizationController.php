<?php

namespace App\Http\Controllers;

use App\Notifications\InvitationAccepted;
use App\Notifications\InvitationRejected;
use App\Models\InvitationToOrganization;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\EditOrganizationRequest;
use App\Http\Requests\CreateOrganizationRequest;

class OrganizationController extends Controller
{
    public function index()
    {
        $createdIds = Organization::where('create_user_id', currentUser()->id)
        ->get()
        ->pluck('id')
        ->all();
        $memberIds = User::where('id', currentUser()->id)
        ->whereNotNull('organization_id')
        ->get()
        ->pluck('organization_id')
        ->all();
        $organizations = Organization::with('users')
        ->whereIn('id', array_merge($createdIds, $memberIds))
        ->paginate(10);

        return view('organizations.index', compact('organizations'));
    }

    public function create()
    {
        //$users = User::all()->pluck('full_name', 'id');

        return view('organizations.create');
    }

    public function store(CreateOrganizationRequest $request)
    {
        $organization = Organization::create($request->validated());

        return redirect()->route('organizations.index');
    }

    public function show(Organization $organization)
    {
        if(! $organization->accessibleToUser(currentUser()->id)) {
            return redirect()->route('organizations.index')->with('error', 'You are not a member of this organization.');
        }

        $organization->load('tasks');

        return view('organizations.show', compact('organization'));
    }

    public function edit(Organization $organization)
    {
        if(! $organization->accessibleToUser(currentUser()->id)) {
            return redirect()->route('organizations.index')->with('error', 'You are not a member of this organization.');
        }

        return view('organizations.edit', compact('organization'));
    }

    public function update(EditOrganizationRequest $request, Organization $organization)
    {
        if(! $organization->accessibleToUser(currentUser()->id)) {
            return redirect()->route('organizations.index')->with('error', 'You are not a member of this organization.');
        }

        $organization->update($request->validated());

        return redirect()->route('organizations.index');
    }

    public function destroy(Organization $organization)
    {
        //abort_if(Gate::denies('delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if(! $organization->accessibleToUser(currentUser()->id)) {
            return redirect()->route('organizations.index')->with('error', 'You are not a member of this organization.');
        }

        try {
            $organization->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if($e->getCode() === '23000') {
                return redirect()->back()->with('status', 'organization belongs to task. Cannot delete.');
            }
        }

        return redirect()->route('organizations.index');
    }

    public function inviteUser(Organization $organization)
    {
        $code = md5(uniqid(rand(), true));
        InvitationToOrganization::create([
            'organization_id' => $organization->id,
            'code'            => $code,
        ]);

        $url = route('organizations.handle-invitation', $code);

        return view('organizations.invite', compact('url'));
    }

    public function acceptInvitation(string $code)
    {
        $invitation = InvitationToOrganization::where('code', $code)->first();

        if(! $invitation) {
            return redirect()->route('organizations.index')->with('error', 'Sorry, invitation does not exist!');
        }

        $organization = Organization::find($invitation->organization_id);

        if(! $organization) {
            return redirect()->route('organizations.index')->with('error', 'Sorry, organization does not exist!');
        }

        if($organization->create_user_id === currentUser()->id) {
            return redirect()->route('organizations.index')->with('error', 'Sorry, you are a creator of organization, you cannot invite yourself!');
        }

        if(currentUser()->organization_id === $organization->id) {
            return redirect()->route('organizations.index')->with('error', 'Sorry, you are already a member of this organization!');
        }

        User::where('id', currentUser()->id)->update([
            'organization_id' => $organization->id,
        ]);

        $notifyUser = User::find($invitation->create_user_id);
        $message = currentUser()->getFullNameAttribute() . ' has accepted the invitation to join ' . $organization->title . ' organization.';
        $notifyUser->notify(new InvitationAccepted(['title' => $message]));
        $invitation->delete();
        return redirect()->route('organizations.show', $organization)->with('success', 'Invitation accepted.');
    }

    public function rejectInvitation(string $code)
    {
        $invitation = InvitationToOrganization::where('code', $code)->first();

        if(! $invitation) {
            return redirect()->route('organizations.index')->with('error', 'Sorry, invitation does not exist!');
        }

        $organization = Organization::find($invitation->organization_id);

        if(! $organization) {
            return redirect()->route('organizations.index')->with('error', 'Sorry, organization does not exist!');
        }

        if($organization->create_user_id === currentUser()->id) {
            return redirect()->route('organizations.index')->with('error', 'Sorry, you are a creator of organization, you cannot reject this invitation!');
        }

        if(currentUser()->organization_id === $organization->id) {
            return redirect()->route('organizations.index')->with('error', 'Sorry, you are already a member of this organization!');
        }

        $notifyUser = User::find($invitation->create_user_id);
        $message = currentUser()->getFullNameAttribute() . ' has rejected the invitation to join ' . $organization->title . ' organization.';
        $notifyUser->notify(new InvitationRejected(['title' => $message]));

        $invitation->delete();
        return redirect()->route('organizations.index')->with('success', 'Invitation rejected.');
    }

    public function handleInvitation(string $code)
    {
        $invitation = InvitationToOrganization::where('code', $code)->first();

        if(! $invitation) {
            return redirect()->route('organizations.index')->with('error', 'Sorry, invitation does not exist!');
        }

        $organization = Organization::find($invitation->organization_id);

        if(! $organization) {
            return redirect()->route('organizations.index')->with('error', 'Sorry, organization does not exist!');
        }

        if($organization->create_user_id === currentUser()->id) {
            return redirect()->route('organizations.index')->with('error', 'Sorry, you are a creator of organization, you cannot invite yourself!');
        }

        return view('organizations.handle_invitation', compact('code', 'organization'));
    }

    public function removeUser(Organization $organization, User $user)
    {
        User::where('id', $user->id)->update([
            'organization_id' => null,
        ]);

        return redirect()->route('organizations.show', $organization)->with('success', 'User successfullly removed.');
    }
}