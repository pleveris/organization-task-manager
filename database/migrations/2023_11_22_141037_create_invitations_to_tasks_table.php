<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\HasUserFields;

class CreateInvitationsToTasksTable extends Migration
{
    use HasUserFields;
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations_to_tasks', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('task_id');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->string('code');
            $table->timestamps();
        });

        $this->addUserFields('invitations_to_tasks');
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
