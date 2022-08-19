<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('book_tile')->nullable();
            $table->string('category_id')->nullable();
            $table->string('author')->nullable();
            $table->double('price')->nullable();
            $table->integer('qty')->nullable();
            $table->date('publication_date')->nullable();
            $table->text('image')->nullable();
            $table->string('book_status')->nullable();
            $table->string('donner_name')->nullable();
            $table->integer('school_id')->nullable()->default(1);
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
        Schema::dropIfExists('books');
    }
}
