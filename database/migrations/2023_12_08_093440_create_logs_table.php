<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\HasUserFields;

class CreateLogsTable extends Migration
{
    use HasUserFields;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('task_id')->nullable(true);
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->string('message');
            $table->timestamps();
        });

        $this->addUserFields('logs');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
