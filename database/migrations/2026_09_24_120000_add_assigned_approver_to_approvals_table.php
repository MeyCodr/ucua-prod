<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignedApproverToApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * An admin can manually assign one or more people to a ticket's level-1 approval.
     * The people live in approval_assignees; who assigned them and when is kept on the approval.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_by_id')->nullable()->after('approver_id');
            $table->dateTime('assigned_at')->nullable()->after('assigned_by_id');
        });

        Schema::create('approval_assignees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('approval_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->unique(['approval_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approval_assignees');

        Schema::table('approvals', function (Blueprint $table) {
            $table->dropColumn(['assigned_by_id', 'assigned_at']);
        });
    }
}
