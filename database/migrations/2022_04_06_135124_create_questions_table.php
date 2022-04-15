<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('form_id');
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
            $table->string('question', 255);
            $table->enum('answer_type', ['TEXTAREA', 'ONE_CHOICE', 'MULTIPLE_CHOICES']);
            $table->boolean('required')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
