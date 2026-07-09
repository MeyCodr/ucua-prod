<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id')->nullable();
            $table->string('approver_level')->nullable();
            $table->string('action')->nullable();
            $table->string('group_id')->nullable();
            $table->string('role_name')->nullable();
            $table->string('approver_id')->nullable();
            $table->string('approver_status')->nullable();
            $table->text('approver_remark')->nullable();
            $table->dateTime('respond_at')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('approvals');
    }
}
