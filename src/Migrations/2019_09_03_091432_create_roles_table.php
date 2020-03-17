<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('label')->nullable();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->boolean('assignable')->default(false);
        });

        Schema::create('role_relation', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('relation');
            $table->integer('relation_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
