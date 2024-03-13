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
        Schema::create('dialer_queues', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->integer('id_parent');
            $table->string('member', 100)->nullable();
            $table->integer('caller_id')->nullable();
            $table->string('ivr_message', 100)->nullable();
            $table->string('extension', 100)->nullable();
            $table->string('moh_pro', 100)->nullable();
            $table->string('langugae', 100)->nullable();
            $table->string('gree_pro', 100)->nullable();
            $table->string('recording', 100)->nullable();
            $table->string('dis_coller', 100)->nullable();
            $table->string('exit_no_agent', 100)->nullable();
            $table->string('ply_posi', 100)->nullable();
            $table->string('ply_posi_peri', 100)->nullable();
            $table->string('auto_ans', 100)->nullable();
            $table->string('call_back', 100)->nullable();
            $table->string('per_ann', 100)->nullable();
            $table->string('per_ann_pro', 100)->nullable();
            $table->integer('created_by');
            $table->string('musiconhold', 128)->nullable();
            $table->string('announce', 128)->default('queue-markq');
            $table->string('context', 128)->default('from-internal');
            $table->integer('timeout')->nullable();
            $table->enum('ringinuse',['yes','no'])->nullable();
            $table->enum('setinterfacevar',['yes','no'])->nullable();
            $table->enum('setqueuevar',['yes','no'])->nullable();
            $table->enum('setqueueentryvar',['yes','no'])->nullable();
            $table->string('monitor_format', 8)->nullable();
            $table->longText('membermacro', 512)->nullable();
            $table->longText('membergosub', 512)->nullable();
            $table->string('queue_youarenext', 128)->nullable();
            $table->string('queue_thereare', 128)->nullable();
            $table->string('queue_callswaiting', 128)->nullable();
            $table->string('queue_quantity1', 128)->nullable();
            $table->string('queue_quantity2', 128)->nullable();
            $table->string('queue_holdtime', 128)->nullable();
            $table->string('queue_minutes', 128)->nullable();
            $table->string('queue_minute', 128)->nullable();
            $table->string('queue_seconds', 128)->nullable();
            $table->string('queue_thankyou', 128)->nullable();
            $table->string('queue_callerannounce', 128)->nullable();
            $table->string('queue_reporthold', 128)->nullable();
            $table->integer('announce_frequency')->nullable();
            $table->enum('announce_to_first_user',['yes', 'no'])->nullable();
            $table->integer('min_announce_frequency')->nullable();
            $table->integer('announce_round_seconds')->nullable();
            $table->string('announce_holdtime', 128)->nullable();
            $table->string('announce_position', 128)->nullable();
            $table->integer('announce_position_limit')->nullable();
            $table->string('periodic_announce', 50)->nullable();
            $table->integer('periodic_announce_frequency')->nullable();
            $table->enum('relative_periodic_announce',['yes','no'])->default('yes');
            $table->enum('random_periodic_announce',['yes','no'])->nullable();
            $table->integer('retry')->nullable();
            $table->integer('wrapuptime')->nullable();
            $table->integer('penaltymemberslimit')->nullable();
            $table->enum('autofill',['yes','no'])->nullable();
            $table->string('monitor_type', 128)->nullable();
            $table->enum('autopause',['yes','no'])->nullable();
            $table->integer('autopausedelay')->nullable();
            $table->enum('autopausebusy',['yes','no'])->nullable();
            $table->enum('autopauseunavail',['yes','no'])->nullable();
            $table->integer('maxlen')->nullable();
            $table->integer('servicelevel')->nullable();
            $table->enum('strategy',['ringall','leastrecent','fewestcalls','random','rrmemory','linear','wrandom','rrordered'])->nullable();
            $table->string('joinempty', 128)->default('yes');
            $table->string('leavewhenempty', 128)->nullable();
            $table->enum('reportholdtime',['yes','no'])->default('yes');
            $table->integer('memberdelay')->nullable();
            $table->integer('weight')->nullable();
            $table->enum('timeoutrestart',['yes','no'])->nullable();
            $table->string('defaultrule', 128)->default('testrule');
            $table->string('timeoutpriority', 128)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dialer_queues');
    }
};
