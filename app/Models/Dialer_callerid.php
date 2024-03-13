<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dialer_callerid extends Model
{
    use HasFactory;
    protected $table = 'dialer_callerid';
    protected $fillable = [
        'id_parent',
        'user_id',
        'number',
        'country_code',
        'country_name',
        'area_code',
        'status',
        'assign_to',
        'f_num',
        'type',
        'action',
        'que',
        'created_by',
        'ivr'
    ];
}
