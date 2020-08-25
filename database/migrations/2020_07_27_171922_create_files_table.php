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
            $table->uuid('uuid');
            $table->unsignedBigInteger('folder_id');
            $table->string('name');
            $table->string('path');
            $table->string('preview')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size');
             // Make hash nullable so it can be left empty if files are bigger than 2GB
             // see (https://www.php.net/manual/en/function.hash-file.php)
            $table->char('hash', 96)->nullable();
            $table->timestamps();
            $table->softDeletes();

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
