<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doc_images', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->tinyInteger('index')->comment('图片的顺序');

            $table->bigInteger('doc_id')->comment('文档的描述符号');

            $table->string('doc_image_url')->nullable()->comment('图片的地址');

            $table->json('content')->nullable()->comment('图片识别的结果');

            $table->tinyInteger('status')->nullable()->comment('状态码');

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
        Schema::dropIfExists('doc_images');
    }
}
