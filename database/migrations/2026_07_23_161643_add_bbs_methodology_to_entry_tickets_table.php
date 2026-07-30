<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBbsMethodologyToEntryTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entry_tickets', function (Blueprint $table) {
            $table->string('bbs_methodology')->nullable()->after('bbs_action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entry_tickets', function (Blueprint $table) {
            $table->dropColumn('bbs_methodology');
        });
    }
}
