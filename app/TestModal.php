<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestModal extends Model
{
    protected $primaryKey="id";
    protected $table="test";
    public $timestamps=false;
}
