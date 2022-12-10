<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterJsonPostContentsAddedRewiteId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('json_post_contents', function (Blueprint $table) {
            $table->integer('rewrite_id')->default('0')->after('post_id');
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
            $table->dropColumn('rewrite_id');
        });
    }
}
