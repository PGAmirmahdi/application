<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuideVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guide_videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('نام آپلود کننده');
            $table->unsignedBigInteger('product_id')->comment('نام محصول');
            $table->string('title')->comment('موضوع');
            $table->longText('text')->comment('متن ویدئو');
            $table->string('main_video')->comment('آدرس ویدئو');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guide_videos');
    }
}
