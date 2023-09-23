<?php

use Illuminate\Database\Migrations\Migration;
use App\Traits\HasUserFields;

class AddUserFieldsToOrganizationsAndTasks extends Migration
{
    use HasUserFields;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->addUserFields('organizations');
        $this->addUserFields('tasks');
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
