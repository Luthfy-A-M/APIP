
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tbm_attendants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tbm_id');
            $table->unsignedBigInteger('attendant_id');
            $table->dateTime('signed_date')->nullable();
            $table->timestamps();

            // Define foreign key constraints if needed
            // $table->foreign('tbm_id')->references('id')->on('tbms')->onDelete('cascade');
            // $table->foreign('attendant_id')->references('id')->on('attendants')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbm_attendants');
    }
};
