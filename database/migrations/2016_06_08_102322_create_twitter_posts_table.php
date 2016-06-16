<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwitterPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twitter_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('socialwall_id');
            $table->string('post_id');
            $table->string('post_username');
            $table->longText('post_text');
            $table->string('post_media');
            $table->string('approved');
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
        Schema::drop('twitter_posts');
    }
}
