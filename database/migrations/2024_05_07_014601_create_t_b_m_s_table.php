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
            $table->string('section');
            $table->string('shift');
            $table->dateTime('date');
            $table->string('time');
            $table->string('title');
            $table->string('pot_danger_point');
            $table->string('most_danger_point');
            $table->string('key_word');
            $table->string('prepared_by');
            $table->dateTime('prepared_by_sign_date');
            $table->string('checked_by')->nullable();
            $table->dateTime('checked_by_sign_date')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->dateTime('reviewed_by_sign_date')->nullable();
            $table->string('approved1_by')->nullable();
            $table->dateTime('approved1_by_sign_date')->nullable();
            $table->string('approved2_by')->nullable();
            $table->dateTime('approved2_by_sign_date')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_b_m_s');
    }
}
