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
        Schema::create('dialer_queues_members', function (Blueprint $table) {
            $table->string('queue_name', 80)->nullable();
            $table->string('interface', 80)->nullable();
            $table->string('membername', 80)->nullable();
            $table->string('state_interface', 80)->nullable();
            $table->integer('penalty')->nullable();
            $table->integer('paused')->nullable();
            $table->bigIncrements('uniqueid');
            $table->integer('wrapuptime')->nullable();
            $table->enum('ringinuse',['0','1','off','on','false','true','no','yes'])->nullable();
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dialer_queues_members');
    }
};
