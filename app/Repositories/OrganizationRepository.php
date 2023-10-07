<?php

namespace App\Repositories;

use App\Models\Organization;
use App\Models\User;

class OrganizationRepository
{
    public function createDefaultOrganization(User $user): void
    {
        if(! Organization::where('create_user_id', $user->id)->exists()) {
            Organization::create([
                'title'          => $user->full_name . ': Personal',
                'description'    => 'This is a Personal organization for ' . $user->full_name . '.',
                'primary'        => true,
                'create_user_id' => $user->id,
            ]);
        }
    }
}