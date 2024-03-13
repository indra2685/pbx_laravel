<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dialer_queues_member extends Model
{
    use HasFactory;

    public $primaryKey = 'queue_name';
    public $incrementing = false;
    protected $table = 'dialer_queues_members';
    protected $fillable = [
        'queue_name',
        'interface ',
        'membername',
        'state_interface',
        'penalty',
        'paused',
        'uniqueid ',
        'wrapuptime',
        'created_by',
        'ringinuse'
    ];
}
