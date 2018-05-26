<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ms = new \Yab\MySQLScout\Services\ModelService();
        $is = new \Yab\MySQLScout\Services\IndexService($ms);
        $is->setModel("App\Ebook");
        $is->createOrUpdateIndex();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $ms = new \Yab\MySQLScout\Services\ModelService();
        $is = new \Yab\MySQLScout\Services\IndexService($ms);
        $is->setModel("App\Ebook");
        $is->dropIndex();
    }
}
