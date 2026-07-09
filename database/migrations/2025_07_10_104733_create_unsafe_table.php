<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnsafeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unsafe', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->text('name_my')->nullable();
            $table->string('is_act')->nullable();
            $table->string('is_condition')->nullable();
            $table->string('is_enabled')->nullable();
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
        Schema::dropIfExists('unsafe');
    }
}
