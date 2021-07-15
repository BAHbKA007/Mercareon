<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGromasLieferscheinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gromas_lieferscheins', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('ls_nummer');
            $table->unsignedSmallInteger('mandant');
            $table->date('liefertag');
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
        Schema::dropIfExists('gromas_lieferscheins');
    }
}
