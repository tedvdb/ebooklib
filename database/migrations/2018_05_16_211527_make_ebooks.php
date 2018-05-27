<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeEbooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebooks', function (Blueprint $table) {
            $table->increments('id');
            $table->char("type", 10);
            $table->string("path")->unique();
            $table->unsignedInteger('indexid');
            $table->string("title");
            $table->string("creator")->nullable();
            $table->char("lang", 10)->nullable();
            $table->string("subject")->nullable();
            $table->longText("description")->nullable();
            $table->string("publisher")->nullable();
            $table->bigInteger("mtime");
            $table->bigInteger("size");
            $table->string("cover")->nullable();
            $table->string("coverthumb")->nullable();
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
        Schema::dropIfExists('ebooks');
    }
}
