<?php

namespace App\Http\Controllers;

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
        $organizations = Organization::paginate(10);

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
        $organization->load('tasks');

        return view('organizations.show', compact('organization'));
    }

    public function edit(Organization $organization)
    {
        return view('organizations.edit', compact('organization'));
    }

    public function update(EditOrganizationRequest $request, Organization $organization)
    {
        $organization->update($request->validated());

        return redirect()->route('organizations.index');
    }

    public function destroy(Organization $organization)
    {
        abort_if(Gate::denies('delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $organization->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if($e->getCode() === '23000') {
                return redirect()->back()->with('status', 'organization belongs to task. Cannot delete.');
            }
        }

        return redirect()->route('organizations.index');
    }
}
