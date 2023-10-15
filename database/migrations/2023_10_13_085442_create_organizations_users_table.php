<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\HasUserFields;

class CreateOrganizationsUsersTable extends Migration
{
    use HasUserFields;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations_users', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('organization_id');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->timestamps();
        });

        $this->addUserFields('organizations_users');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizations_users');
    }
}
