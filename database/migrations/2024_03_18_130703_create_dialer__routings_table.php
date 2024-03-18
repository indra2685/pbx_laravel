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
        Schema::create('dialer_routings', function (Blueprint $table) {
            $table->id();
            $table->integer('id_parent')->nullable();
            $table->integer('queuq_id')->nullable();
            $table->string('prefix', 100)->nullable();
            $table->enum('status', ['0','1'])->default('0');
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dialer__routings');
    }
};
