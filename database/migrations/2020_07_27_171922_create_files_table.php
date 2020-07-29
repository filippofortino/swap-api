<?php

use Illuminate\Database\Migrations\Migration; 
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('folder_id')->nullable();
            $table->string('name');
            $table->string('preview')->nullable();
            $table->json('info')->nullable();
             // Make hash nullable so it can be left empty if files are bigger than 2GB
             // see (https://www.php.net/manual/en/function.hash-file.php)
            $table->string('hash')->nullable();
            $table->timestamps();

            $table->foreign('folder_id')->references('id')->on('folders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
