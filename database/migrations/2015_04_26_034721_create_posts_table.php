<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('title')->unique();
            $table->string('slug');
            $table->longText('pcontent');
            $table->string('type');
            $table->integer('user_id');
            $table->string('featuredimage');
            $table->string('status');
            $table->string('extlink');
            $table->integer('commentcount');
            $table->string('mimetype');
            $table->string('excerpt');
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
        Schema::drop('posts');
    }

}
