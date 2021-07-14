<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuchPositionensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buch__positionens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buch_kopf_id');
            $table->mediumInteger('ls_nummer');
            $table->timestamps();
            
            $table->foreign('buch_kopf_id')->references('id')->on('buch__kopfs')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buch__positionens');
    }
}
