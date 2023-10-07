<?php

use Illuminate\Database\Migrations\Migration;
use App\Traits\HasUserFields;

class AddUserFieldsToInvitationsToOrganizations extends Migration
{
    use HasUserFields;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->addUserFields('invitations_to_organizations');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
