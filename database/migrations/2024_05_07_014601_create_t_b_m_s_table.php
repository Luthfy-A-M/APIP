<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTBMSTable extends Migration
{
    public function up()
    {
        Schema::create('t_b_m_s', function (Blueprint $table) {
            $table->id();
            $table->string('dept_code');
            $table->text('section')->nullable(); // Changed to text and nullable
            $table->text('shift')->nullable(); // Changed to text and nullable
            $table->date('date')->nullable(); // Changed to date and nullable
            $table->time('time')->nullable(); // Changed to time and nullable
            $table->text('title')->nullable(); // Changed to text and nullable
            $table->text('pot_danger_point')->nullable(); // Changed to text and nullable
            $table->text('most_danger_point')->nullable(); // Changed to text and nullable
            $table->text('countermeasure')->nullable(); // Changed to text and nullable
            $table->text('key_word')->nullable(); // Changed to text and nullable
            $table->string('prepared_by');
            $table->dateTime('prepared_by_sign_date')->nullable();
            $table->string('checked_by')->nullable();
            $table->dateTime('checked_by_sign_date')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->dateTime('reviewed_by_sign_date')->nullable();
            $table->string('approved1_by')->nullable();
            $table->dateTime('approved1_by_sign_date')->nullable();
            $table->string('approved2_by')->nullable();
            $table->dateTime('approved2_by_sign_date')->nullable();
            $table->string('status')->default("draft");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_b_m_s');
    }
}
