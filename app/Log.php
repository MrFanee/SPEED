<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $primaryKey = 'id_log';
    protected $fillable = ['table_name', 'action', 'record_id', 'old_value', 'new_value'];
}
