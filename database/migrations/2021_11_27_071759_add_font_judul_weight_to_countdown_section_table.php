<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFontJudulWeightToCountdownSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('countdown_section', function (Blueprint $table) {
            $table->integer('font_judul_weight')->after('font_judul')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('countdown_section', function (Blueprint $table) {
            $table->dropColumn('font_judul_weight');
        });
    }
}
