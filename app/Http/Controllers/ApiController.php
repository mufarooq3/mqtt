<?php

namespace App\Http\Controllers;

use App\notification;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{

    public function get_valid_notifications(Request $request){
        $title=$request->category;
        $data=notification::select("message")->where('deletion_date', '>=', date('Y/m/d'))
            ->where('title',$title)
            ->get();
        return json_encode($data);
    }

}
