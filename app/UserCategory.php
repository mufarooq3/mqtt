<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model
{
    protected $table='user_categories';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $guarded=[];
}
