<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceinfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('From');
            $table->string('Browser_Name')->nullable();
            $table->text('User_Agent')->nullable();
            $table->string('Platform')->nullable();
            $table->string('App_Version')->nullable();
            $table->string('Brand')->nullable();
            $table->string('Model')->nullable();
            $table->string('Android_Version')->nullable();
            $table->string('Manufactor')->nullable();
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
        Schema::dropIfExists('deviceinfo');
    }
}
