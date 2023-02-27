<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFontJudulToHeroSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hero_section', function (Blueprint $table) {
            $table->string('font_judul')->after('overlay')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hero_section', function (Blueprint $table) {
            $table->dropColumn('font_judul');
        });
    }
}