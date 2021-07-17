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
            $table->unsignedMediumInteger('lieferschein')->primary();
            $table->string('kundennummer', 6);
            $table->string('kundenname', 35);
            $table->string('bestellnummer', 10);
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
