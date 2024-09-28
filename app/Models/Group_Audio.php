<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group_Audio extends Model
{
    use HasFactory;


    protected $table = 'group_audios';

    protected $fillable = ['group_id', 'audio_id'];

    public function group_name()
    {
        return $this->hasOne('App\Models\Dialer_audio', 'id', 'audio_id');
    }
}
