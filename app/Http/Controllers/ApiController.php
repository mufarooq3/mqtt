<?php

namespace App\Http\Controllers;

use App\notification;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{

    public function get_valid_notifications(){
        $data=notification::where('deletion_date', '>=', date('Y/m/d'))->get();
        return json_encode($data);
    }

}
