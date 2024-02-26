<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\HasUserFields;

class CreateTasksAssigneesTable extends Migration
{
    use HasUserFields;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks_assignees', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('task_id');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->timestamps();
        });

        $this->addUserFields('tasks_assignees');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks_assignees');
    }
}
