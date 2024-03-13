<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dialer_callerid', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_parent');
            $table->integer('user_id')->nullable();
            $table->string('number')->nullable();
            $table->string('country_code')->nullable();
            $table->string('country_name')->nullable();
            $table->string('area_code')->nullable();
            $table->string('status', 20)->nullable();
            $table->integer('assign_to')->nullable();
            $table->string('f_num', 20)->nullable();
            $table->string('type', 20)->nullable();
            $table->string('action', 50)->nullable();
            $table->string('que', 100)->nullable();
            $table->string('ivr', 100)->nullable();
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dialer_callerid');
    }
};
