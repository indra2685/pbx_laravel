<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue_filter extends Model
{
    use HasFactory;
    protected $table = 'queue_filters';
    protected $fillable = [
    	'id_parent',
    	'queues_id',
        'filter_by',
        'duration',
        'created_by'
    ];
    public function queue_name() {
        return $this->hasOne('App\Models\Dialer_group', 'id', 'queues_id');
    }

}
