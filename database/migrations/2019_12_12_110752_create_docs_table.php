<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('用户ID');
            $table->string('doc_name')->nullable()->comment('文档人的名称');
            $table->string('desc')->nullable()->comment('文件描述');
            $table->string('doc_at')->nullable()->comment('文档人的时间');
            $table->json('pics')->nullable()->comment('图片');
            $table->integer('pic_nums')->nullable()->comment('图片数量');

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
        Schema::dropIfExists('docs');
    }
}
