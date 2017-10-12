<?php

namespace App\Http\Controllers;

use App\AdminModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\EmpModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use DateTime;
use Response;

/*
 * Helper functions for building a DataTables server-side processing SQL query
 *
 * The static functions in this class are just helper functions to help build
 * the SQL used in the DataTables demo server-side processing scripts. These
 * functions obviously do not represent all that can be done with server-side
 * processing, they are intentionally simple to show how it works. More complex
 * server-side processing operations will likely require a custom script.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

// REMOVE THIS BLOCK - used for DataTables test environment only!

class Usercontroller extends BaseController {

    public function __construct() {
        
    }

    public function register() {
        $user = DB::select("select * from `users` where device_id = '" . $_POST['device_id'] . "' order by uid desc limit 1");
       
        if (!empty($user[0]->device_id)) {
            DB::table('users')
                    ->where('device_id', $_POST['device_id'])
                    ->update($_POST);
        } else {
            $id = DB::table('users')->insertGetId($_POST);
        }
        $user = DB::select("select * from `users` where device_id = '" . $_POST['device_id'] . "'");
        echo json_encode(array("status" => "success", "data" => $user));
    }

    public function update() {
        DB::table('users')
                ->where('device_id', $_POST['device_id'])
                ->update($_POST);
        
        echo json_encode(array("status" => "success"));
    }

    public function categories() {
        $user = DB::select("select * from `categories` where is_active = 1");
        if (!empty($user)) {
            // echo Response::json(array("status" => "success", "data" => $user));
            echo json_encode(array("status" => "success", "data" => $user));
        } else {
            echo json_encode(array("status" => "fail"));
            //echo Response::json(array("status" => "fail"));
        }
    }

}
