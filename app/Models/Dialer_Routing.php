<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dialer_Routing extends Model
{
    use HasFactory;
    protected $table = 'dialer_routings';
    protected $fillable = [
        'id_parent',
        'queue_id',
        'prefix',
        'status',
        'created_by'
    ];
}
