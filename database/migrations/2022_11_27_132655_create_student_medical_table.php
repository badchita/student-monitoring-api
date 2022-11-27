<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_medical', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id');
            $table->integer('children_id');
            $table->integer('teacher_id');
            $table->string('note')->nullable();
            $table->string('status')->nullable();
            $table->string('medical_number')->nullable();
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
        Schema::dropIfExists('student_medical');
    }
};
