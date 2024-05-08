
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tbm_instructors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tbm_id');
            $table->unsignedBigInteger('instructor_id');
            $table->dateTime('signed_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbm_instructors');
    }
};
