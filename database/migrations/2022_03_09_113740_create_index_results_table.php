<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndexResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('index_results', function (Blueprint $table) {
            $table->id();
            $table->string('search_engine', 191)->nullable();
            $table->string('notifyTime', 191)->nullable();
            $table->string('type', 191)->nullable();
            $table->string('url', 191)->nullable();
            $table->string('status_code', 191)->nullable();
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
        Schema::dropIfExists('index_results');
    }
}
