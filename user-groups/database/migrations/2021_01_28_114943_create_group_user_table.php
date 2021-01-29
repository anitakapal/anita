<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('group_user', function (Blueprint $table) {
            $table->primary(['group_id', 'user_id']);
            $table->integer('group_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('joined_by', ['admin', 'user']);
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onCascade('delete');

        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('group_user');
        Schema::enableForeignKeyConstraints();
    }
}
