<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class user_notification extends Model
{
    protected $primaryKey='nid';
    protected $table="user_notifications";
    public $timestamps=false;
    protected $guarded=['nid'];

    public function notification(){
        return $this->belongsTo(notification::class,'notification_id');
    }
}
