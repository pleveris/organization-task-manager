<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationsToOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations_to_organizations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('organization_id');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->string('code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitations_to_organizations');
    }
}
