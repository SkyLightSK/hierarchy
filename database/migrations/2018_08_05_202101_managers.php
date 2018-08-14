<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Managers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managers', function (Blueprint $table) {


            $table->increments('id');
            $table->string('position')->default('manager');
            $table->string('recruitment_date')->default(date('Y-m-d'));
            $table->integer('salary_size')->default(0);
            $table->integer('director_id')->default(1);
            $table->string('ruler_name')->default('');
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
        //
    }
}
