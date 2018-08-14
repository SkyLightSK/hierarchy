<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userable_id')->default(1);
            $table->string('userable_type')->default('');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('position')->default('unemployed');
            $table->integer('salary_size')->default(0);
            $table->string('recruitment_date')->default(date('Y-m-d'));
            $table->string('ruler_name')->default('');
            $table->string('avatar')->default('avatar.png');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
