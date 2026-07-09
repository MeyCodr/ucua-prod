<?php

use App\Models\Branch;
use App\Models\Department;
use App\Models\Location;
use App\Models\Plant;
use App\Models\State;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntryTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id')->unique();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('staff_id')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('site_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('sub_department_id')->nullable();
            $table->string('department_other')->nullable();
            $table->text('affected_area')->nullable();
            $table->unsignedBigInteger('dept_res_id')->nullable();
            $table->unsignedBigInteger('sub_dept_res_id')->nullable();
            $table->string('dept_res_other')->nullable();
            $table->string('plant_inv_id')->nullable();
            $table->string('gm_res_id')->nullable();
            $table->string('ucua_id')->nullable();
            $table->string('ucua_type')->nullable();
            $table->text('ucua_other')->nullable();
            $table->text('description')->nullable();
            $table->string('stop_cult_id')->nullable();
            $table->string('zero_harm_id')->nullable();
            $table->string('rank_id')->nullable();
            $table->string('bbs_action')->nullable();
            $table->text('action_taken')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('dateline')->nullable();
            $table->dateTime('dateline_extension')->nullable();
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
        Schema::dropIfExists('entry_tickets');
    }
}
