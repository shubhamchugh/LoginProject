<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_contents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('post_id')->unsigned();
            $table->bigInteger('fake_user_id')->unsigned()->nullable();
            $table->boolean('is_metadata')->default('0');
            $table->boolean('is_image')->default('0');
            $table->string('content_title', 191)->nullable();
            $table->string('domain_name', 191)->nullable();
            $table->mediumText('content_url')->nullable();
            $table->mediumText('content_dec')->nullable();
            $table->string('content_image', 191)->nullable();
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
        Schema::dropIfExists('post_contents');
    }
}
