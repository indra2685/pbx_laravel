<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audio_Group extends Model
{
    use HasFactory;

    protected $table = 'audio_group';

    protected $fillable = ['group_name', 'user_id'];

    public function audios()
    {
        return $this->hasMany('App\Models\Group_Audio', 'group_id', 'id');
    }
}
