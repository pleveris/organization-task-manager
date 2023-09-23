<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait HasUserFields
{
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->create_user_id = auth()->id();
        });
        static::updating(function ($model) {
            $model->update_user_id = auth()->id();
        });
        static::deleting(function ($model) {
            $model->delete_user_id = auth()->id();
            $model->save();
        });
        static::replicating(function ($model) {
            $model->create_user_id = auth()->id();
            $model->update_user_id = auth()->id();
        });
    }

    public function addUserFields($table)
    {
        Schema::table($table, function (Blueprint $table) {
            $table->integer('create_user_id')->nullable();
            $table->integer('update_user_id')->nullable();
            $table->integer('delete_user_id')->nullable();

            $table->foreign('create_user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->foreign('update_user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->foreign('delete_user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
        });
    }

    public function dropUserFields($dbTable)
    {
        Schema::table($dbTable, function (Blueprint $table) use ($dbTable) {
            $table->dropForeign($dbTable.'_create_user_id_foreign');
            $table->dropForeign($dbTable.'_update_user_id_foreign');
            $table->dropForeign($dbTable.'_delete_user_id_foreign');

            $table->dropColumn(['create_user_id', 'update_user_id', 'delete_user_id']);
        });
    }

    public function createUser()
    {
        return $this->belongsTo(User::class, 'create_user_id');
    }

    public function updateUser()
    {
        return $this->belongsTo(User::class, 'update_user_id');
    }

    public function deleteUser()
    {
        return $this->belongsTo(User::class, 'delete_user_id');
    }
}
