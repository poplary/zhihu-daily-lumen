<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZhihuDailiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zhihu_dailies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('date');
            $table->integer('story_id')->unique();
            $table->string('title');
            $table->integer('type');
            $table->boolean('multipic');
            $table->string('ga_prefix');
            $table->string('image_origin');
            $table->string('image');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('zhihu_daily');
    }
}
