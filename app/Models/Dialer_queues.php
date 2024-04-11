<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dialer_queues extends Model
{
    use HasFactory;
    public $primaryKey = 'name';
    public $incrementing = false;

    protected $table = 'dialer_queues';
    protected $fillable = [

        'member',
        'id_parent',
        'caller_id',
        'ivr_message',
        'extension',
        'moh_pro',
        'langugae',
        'gree_pro',
        'recording',
        'dis_coller',
        'exit_no_agent',
        'ply_posi',
        'ply_posi_peri',
        'auto_ans',
        'call_back',
        'per_ann',
        'per_ann_pro',
        'name',
        'musiconhold',
        'announce',
        'context',
        'timeout',
        'ringinuse',
        'setinterfacevar',
        'setqueuevar',
        'setqueueentryvar',
        'membermacro',
        'monitor_format',
        'membergosub',
        'queue_youarenext',
        'queue_thereare',
        'queue_callswaiting',
        'queue_quantity1',
        'queue_quantity2',
        'queue_holdtime',
        'queue_minutes',
        'queue_minute',
        'queue_seconds',
        'queue_thankyou',
        'queue_callerannounce',
        'queue_reporthold',
        'announce_frequency',
        'announce_to_first_user',
        'min_announce_frequency',
        'announce_round_seconds',
        'announce_holdtime',
        'announce_position',
        'announce_position_limit',
        'periodic_announce',
        'periodic_announce_frequency',
        'relative_periodic_announce',
        'random_periodic_announce',
        'retry',
        'wrapuptime',
        'penaltymemberslimit',
        'autofill',
        'monitor_type',
        'autopause',
        'autopausedelay',
        'autopausebusy',
        'autopauseunavail',
        'maxlen',
        'servicelevel',
        'strategy',
        'joinempty',
        'leavewhenempty',
        'reportholdtime',
        'memberdelay',
        'weight',
        'timeoutrestart',
        'defaultrule',
        'timeoutpriority'
    ];

    public function audio_name(){
        return $this->hasOne(Dialer_audio::class,'id','ivr_message');
    }
}
