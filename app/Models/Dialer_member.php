<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dialer_member extends Model
{
    use HasFactory;
    protected $table = 'dialer_member';
    protected $fillable = [
    	'id_parent',
    	'name',
        'username',
        'password',
        'status',
        'extension',
        'exte_pass',
        'ring_timeout',
        'dial_timeout',
        'voice_mail',
        'vs_pass',
        'email',
        'created_by'
    ];
   


}
