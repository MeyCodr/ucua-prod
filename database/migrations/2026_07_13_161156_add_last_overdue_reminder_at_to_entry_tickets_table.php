<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastOverdueReminderAtToEntryTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entry_tickets', function (Blueprint $table) {
            $table->dateTime('last_overdue_reminder_at')->nullable()->after('dateline_extension');
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
            $table->dropColumn('last_overdue_reminder_at');
        });
    }
}
