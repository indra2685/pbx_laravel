<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dialer_group extends Model
{
    use HasFactory;

    protected $table = 'dialer_groups';
    protected $fillable = [
        'id_parent',
        'group_name',
        'status'
    ];

    // public function audio_name(){
    //     return $this->hasOne(Dialer_audio::class,'id','ivr_message');
    // }
}
