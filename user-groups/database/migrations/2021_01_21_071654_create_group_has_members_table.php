<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupHasMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('group_has_members', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id');
            $table->unsignedBigInteger('member_id');
            $table->enum('joined_by', ['admin', 'user']);
            $table->timestamps();
            $table->foreign('member_id')
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
        Schema::dropIfExists('group_has_members');
        Schema::enableForeignKeyConstraints();
    }
}
