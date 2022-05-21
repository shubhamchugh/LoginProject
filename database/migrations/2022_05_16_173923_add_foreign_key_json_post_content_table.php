<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyJsonPostContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('json_post_contents', function (Blueprint $table) {
            $table->foreign('fake_user_id')->references('id')->on('fake_users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('json_post_contents', function (Blueprint $table) {
            //
        });
    }
}
