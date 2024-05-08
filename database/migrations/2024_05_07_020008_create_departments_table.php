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
            $table->string('GL1')->nullable();
            $table->string('GL2')->nullable();
            $table->string('GL3')->nullable();
            $table->string('SPV1')->nullable();
            $table->string('SPV2')->nullable();
            $table->string('SPV3')->nullable();
            $table->string('MGR1')->nullable();
            $table->string('MGR2')->nullable();
            $table->string('Dept_Head')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('departments');
    }
}
