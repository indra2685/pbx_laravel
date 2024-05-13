<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dialer_audio extends Model
{
    use HasFactory;
    protected $table = 'dialer_audios';
    protected $fillable = [
        'id_parent',
        'name',
        'time_out',
        'prefix',
        'file_name',
        'created_by'
    ];
}
