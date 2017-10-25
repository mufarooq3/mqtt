<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class notification extends Model
{
    protected $primaryKey='nid';
    protected $table='notifications';
    public $timestamps = false;

    public function user_notification(){
        return $this->hasMany(user_notification::class,'notification_id','nid');
    }

}
