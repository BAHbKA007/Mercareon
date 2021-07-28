<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDirektlieferantToGromasLieferscheins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gromas_lieferscheins', function (Blueprint $table) {
            $table->string('direktlieferant_nummer', 6)->nullable()->default('1');
            $table->string('direktlieferant_name', 35)->nullable()->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gromas_lieferscheins', function (Blueprint $table) {
            $table->dropColumn('direktlieferant_nummer');
            $table->dropColumn('direktlieferant_name');
        });
    }
}
