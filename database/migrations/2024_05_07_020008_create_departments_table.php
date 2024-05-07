<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('dept_code');
            $table->string('dept_name');
            $table->string('GL1');
            $table->string('GL2');
            $table->string('GL3');
            $table->string('SPV1');
            $table->string('SPV2');
            $table->string('SPV3');
            $table->string('MGR1');
            $table->string('MGR2');
            $table->string('Dept_Head');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('departments');
    }
}
