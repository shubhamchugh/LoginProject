<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBlankPostCheckPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('json_post_contents', function (Blueprint $table) {
            $table->string('BlankPostCheck')->after('is_google_results')->default('pending');
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
            $table->dropColumn('BlankPostCheck');
        });
    }
}
