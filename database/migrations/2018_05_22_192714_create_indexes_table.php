<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndexesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_paths', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path');
        });
        Schema::table('ebooks', function (Blueprint $table) {
            $table->foreign('indexid')->references('id')->on('search_paths');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ebooks', function (Blueprint $table) {
            $table->dropForeign(['indexid']);
        });

        Schema::dropIfExists('search_paths');
    }
}
