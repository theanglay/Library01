<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Member extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
        $table->Increments('id');
        $table->string('member')->nullable();
        $table->string('first_name')->nullable();
        $table->string('last_name')->nullable();
        $table->enum('gender',['male','female'])->nullable();
        $table->text('image')->nullable();
        $table->text('phone')->nullable();
        $table->string('school_id')->nullable()->default(1);
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
