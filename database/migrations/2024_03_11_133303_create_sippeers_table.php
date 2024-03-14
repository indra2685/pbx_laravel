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
        Schema::create('sippeers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_parent');
            $table->integer('id_member');
            $table->string('name', 40);
            $table->string('ipaddr', 45)->nullable();
            $table->integer('port')->nullable();
            $table->integer('regseconds')->nullable();
            $table->string('defaultuser', 40)->nullable();
            $table->string('fullcontact', 80)->nullable();
            $table->string('regserver', 20)->nullable();
            $table->string('useragent', 255)->nullable();
            $table->integer('lastms')->nullable();
            $table->string('host', 40)->default('dynamic');
            $table->enum('type', ['friend', 'user', 'peer'])->default('friend');
            $table->string('context', 40)->default('public');
            $table->string('permit', 95)->nullable();
            $table->string('deny', 95)->nullable();
            $table->string('secret', 40)->nullable();
            $table->string('md5secret', 40)->nullable();
            $table->string('remotesecret', 40)->nullable();
            $table->enum('transport', ['udp', 'tcp', 'tls', 'ws', 'wss', 'udp,tcp', 'tcp,udp'])->nullable();
            $table->enum('dtmfmode', ['rfc2833', 'info', 'shortinfo', 'inband', 'auto'])->default('inband');
            $table->enum('directmedia', ['yes', 'no', 'nonat', 'update', 'outgoing'])->default('no');
            $table->string('nat', 29)->default('force_rport');
            $table->string('callgroup', 40)->nullable();
            $table->string('pickupgroup', 40)->nullable();
            $table->string('language', 40)->nullable();
            $table->string('disallow', 200)->default('all');
            $table->string('allow', 200)->default('ulaw');
            $table->string('insecure', 40)->nullable();
            $table->enum('trustrpid', ['yes', 'no'])->nullable();
            $table->enum('progressinband', ['yes', 'no', 'never'])->default('yes');
            $table->enum('promiscredir', ['yes', 'no'])->nullable();
            $table->enum('useclientcode', ['yes', 'no'])->nullable();
            $table->string('accountcode', 80)->nullable();
            $table->string('setvar', 200)->nullable();
            $table->string('callerid', 40)->nullable();
            $table->string('amaflags', 40)->nullable();
            $table->enum('callcounter', ['yes', 'no'])->nullable();
            $table->integer('busylevel')->nullable();
            $table->enum('allowoverlap', ['yes', 'no'])->nullable();
            $table->enum('allowsubscribe', ['yes', 'no'])->nullable();
            $table->enum('videosupport', ['yes', 'no'])->nullable();
            $table->integer('maxcallbitrate')->nullable();
            $table->enum('rfc2833compensate', ['yes', 'no'])->nullable();
            $table->string('mailbox', 40);
            $table->enum('session_timers', ['accept', 'refuse', 'originate'])->nullable();
            $table->integer('session_expires')->nullable();
            $table->integer('session_minse')->nullable();
            $table->enum('session_refresher', ['uac', 'uas'])->nullable();
            $table->string('t38pt_usertpsource', 40)->nullable();
            $table->string('regexten', 40)->nullable();
            $table->string('fromdomain', 40)->nullable();
            $table->string('fromuser', 40)->nullable();
            $table->string('qualify', 40)->default('yes');
            $table->string('defaultip', 45)->nullable();
            $table->integer('rtptimeout')->nullable();
            $table->integer('rtpholdtimeout')->nullable();
            $table->enum('sendrpid', ['yes', 'no'])->default('yes');
            $table->string('outboundproxy', 40)->nullable();
            $table->string('callbackextension', 40)->nullable();
            $table->integer('timert1')->nullable();
            $table->integer('timerb')->nullable();
            $table->integer('qualifyfreq')->nullable();
            $table->enum('constantssrc', ['yes', 'no'])->nullable();
            $table->string('contactpermit', 95)->nullable();
            $table->string('contactdeny', 95)->nullable();
            $table->enum('usereqphone', ['yes', 'no'])->nullable();
            $table->enum('textsupport', ['yes', 'no'])->nullable();
            $table->enum('faxdetect', ['yes', 'no'])->nullable();
            $table->enum('buggymwi', ['yes', 'no'])->nullable();
            $table->string('auth', 40)->nullable();
            $table->string('fullname', 40)->nullable();
            $table->string('trunkname', 40)->nullable();
            $table->string('cid_number', 40)->nullable();
            $table->enum('callingpres', ['allowed_not_screened', 'allowed_passed_screen', 'allowed_failed_screen', 'allowed', 'prohib_not_screened', 'prohib_passed_screen', 'prohib_failed_screen', 'prohib'])->nullable();
            $table->string('mohinterpret', 40)->nullable();
            $table->string('mohsuggest', 40)->nullable();
            $table->string('parkinglot', 40)->nullable();
            $table->enum('hasvoicemail', ['yes', 'no'])->default('yes');
            $table->enum('subscribemwi', ['yes', 'no'])->nullable();
            $table->string('vmexten', 40)->nullable();
            $table->enum('autoframing', ['yes', 'no'])->nullable();
            $table->integer('rtpkeepalive')->nullable();
            $table->integer('call_limit')->nullable();
            $table->enum('g726nonstandard', ['yes', 'no'])->nullable();
            $table->enum('ignoresdpversion', ['yes', 'no'])->nullable();
            $table->enum('allowtransfer', ['yes', 'no'])->nullable();
            $table->enum('dynamic', ['yes', 'no'])->default('yes');
            $table->string('path', 256)->nullable();
            $table->integer('created_by')->nullable();
            $table->enum('supportpath', ['yes', 'no'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sippeers');
    }
};
