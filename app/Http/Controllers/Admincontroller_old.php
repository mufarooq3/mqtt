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

class Admincontroller extends BaseController {

    public function __construct() {
        
    }
    
    public function dashboard(){
         $users = DB::select("select count(*) as cnt from users");
         $data['cnt_users'] = $users[0]->cnt; 
         
         $noti = DB::select("select count(*) as cnt from notifications");
         $data['cnt_noti'] = $noti[0]->cnt; 
         
         $cat = DB::select("select count(*) as cnt from categories");
         $data['cnt_cat'] = $cat[0]->cnt; 
         
         $mnth_user = DB::select("select count(*) as cnt from users where MONTH(time) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)");
         $data['cnt_last_mnth_user'] = $mnth_user[0]->cnt; 
         
         return View::make('admin.dashboard', compact('result'))->with('data', $data);
    }

    public function login() {
        $res = DB::select("select * from admin where (username =  '" . $_POST['username'] . "' OR email = '" . $_POST['username'] . "') and password = md5('" . $_POST['password'] . "')");
        if (!empty($res)) {
            Session::put('AdminData', $res[0]);
            return Redirect::to('dashboard');
        } else {
            Session::put('msg', 'Invalid Email or Password.');
            Session::put('type', 'error');
            return Redirect::to('/');
        }
    }

    public function main() {
        if (Session::get('AdminData')) {
            $categories = DB::select("select cat_name,id from categories");
            return View::make('admin.send_noty', compact('categories'))->with('categories', $categories);
        } else {
            return Redirect::to('/')->with('loginError', 'Invalid Email or Password.');
        }
    }


    public function categories($par = NULL, $par2 = NULL) {
        if (Session::get('AdminData')) {
            if (!empty($par)) {
                if ($par == "status") {
                    $par2 == 'true' ? $number = 1 : $number = 0;
                    DB::table('categories')
                            ->where('id', $_GET['id'])
                            ->update(['is_active' => $number]);
                    echo 'true';
                    exit();
                }

                if ($par == "edit") {
                    $categories = DB::table('categories')->select('*')->where('id', $_GET['id'])->first();
                    return View::make('admin.categories_edit')->with('data', $categories);
                }
                if ($par == "delete") {
                    DB::table('categories')->where('id', $_GET['id'])->delete();
                    echo 'true';
                    exit();
                }
            }
            if (!empty($_POST)) {
                if ($par == "add") {
                    if (!empty($_POST['page'])) {
                        $srno = $_POST['page'] * 5 - 5 + 1;
                        $page = $_POST['page'];
                    }
                    $data['cat_name'] = $_POST['cat_name'];
                    if (empty($_POST['image_path'])) {
                        $file = array('image' => Input::file('image'));
// setting up rules
                        $rules = array('image' => 'required',); //mimes:jpeg,bmp,png and for max size max:10000
// doing the validation, passing post data, rules and the messages
                        $validator = Validator::make($file, $rules);
                        if ($validator->fails()) {
// send back to the page with the input data and errors
                            echo json_encode(array("msg", 'Error in upload: try with different image'));

                            exit();
//return Redirect::to('users')->withInput()->withErrors($validator);
                        } else {
// checking file is valid.
                            if (Input::file('image')->isValid()) {
                                $destinationPath = 'upload_image'; // upload path
                                $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
                                $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
                                Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
// sending back with message

                                $_POST['image'] = url() . '/upload_image/' . $fileName;
                            } else {
// sending back with error message.
                                echo json_encode(array("msg", 'uploaded file is not valid'));
                                exit();
// return Redirect::to('users');
                            }
                        }
                    }

                    $id = DB::table('categories')->insertGetId(
                            ['cat_name' => $_POST['cat_name'], 'cat_slug' => str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9]+/', '_', $_POST['cat_name'])), 'cat_desc' => $_POST['cat_desc'], 'image' => $_POST['image']]
                    );

                    Session::put('msg', 'successfully inserted');
                    Session::put('type', 'success');
                    return redirect(url() . '/categories/?page=' . $page);
                }

                if ($par == "update") {
                    if (!empty($_POST['page'])) {
                        $srno = $_POST['page'] * 5 - 5 + 1;
                        $page = $_POST['page'];
                    }
                    if (!empty($_FILES['name']) && empty($_POST['image_path'])) {
                        $file = array('image' => Input::file('image'));
// setting up rules
                        $rules = array('image' => 'required',); //mimes:jpeg,bmp,png and for max size max:10000
// doing the validation, passing post data, rules and the messages
                        $validator = Validator::make($file, $rules);
                        if ($validator->fails()) {
// send back to the page with the input data and errors
                            echo json_encode(array("msg", 'Error in upload: try with different image'));

                            exit();
//return Redirect::to('users')->withInput()->withErrors($validator);
                        } else {
// checking file is valid.
                            if (Input::file('image')->isValid()) {
                                $destinationPath = 'upload_image'; // upload path
                                $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
                                $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
                                Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
// sending back with message

                                $_POST['image'] = url() . '/upload_image/' . $fileName;
                            } else {
// sending back with error message.
                                echo json_encode(array("msg", 'uploaded file is not valid'));
                                exit();
// return Redirect::to('users');
                            }
                        }
                    }
                    unset($_POST['page']);
                    unset($_POST['_token']);
                    // $_POST['cat_slug'] = str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9]+/', '_', $_POST['cat_name']));
                    DB::table('categories')
                            ->where('id', $_POST['id'])
                            ->update($_POST);
                    Session::put('msg', 'successfully updated');

                    Session::put('type', 'success');
                    return redirect(url() . '/categories/?page=' . $page);
                }
            }
            empty($_GET['page']) && empty($_POST['page']) ? $data = "" : '';
            if (!empty($_GET['page'])) {
                $data['srno'] = $_GET['page'] * 5 - 5 + 1;
                $data['page'] = $_GET['page'];
            }

            $query = DB::table('categories');
            $results = $query->paginate(5);
            $countBuilds = count($results);
            return View::make('admin.categories', compact('results'))->with("data", $data);
        } else {
            return Redirect::to('/')->with('loginError', 'Invalid Email or Password.');
        }
    }

    public function users($par = NULL, $par2 = NULL) {
        if (Session::get('AdminData')) {
            if (!empty($par)) {
                if ($par == "status") {
                    $par2 == 'true' ? $number = 1 : $number = 0;
                    DB::table('users')
                            ->whereIn('uid', explode(',', $_GET['ids']))
                            ->update(['is_active' => $number]);
                    echo 'true';
                    exit();
                }
                if ($par == "delete") {
                    DB::table('users')->whereIn('uid', explode(',', $_GET['ids']))->delete();
                    echo 'true';
                    exit();
                }
            
            } else {
                empty($_GET['page']) && empty($_POST['page']) ? $data = "" : '';
                if (!empty($_GET['page'])) {
                    $data['srno'] = $_GET['page'] * 5 - 5 + 1;
                    $data['page'] = $_GET['page'];
                }

                $query = DB::table('users');
                $users = $query->paginate(10);
                $countBuilds = count($users);

                $data['groups'] = DB::select("select * from `group`");
                $data['categories'] = DB::select(
                                "select * from `categories`");

                return View::make('admin.users', compact('users'))->with("data", $data);
            }
        } else {
            return Redirect::to('/')->with('loginError', 'Invalid Email or Password.');
        }
    }

    public function search_cat() {
        $query = $_GET['term'];
//table('categories')->where('cat_name', 'LIKE', '%' . $_GET['query'] . '%')
        $categories = DB::select(
                        "select cat_name,id from categories where cat_name like '%" . $query . "%'");
        echo json_encode($categories);
    }

    public function searchterm_handler($searchterm) {
        if ($searchterm) {
            $_SESSION['searchterm'] = $searchterm;
            return $searchterm;
        } elseif ($_SESSION['searchterm']) {
//print_r ($_SESSION['searchterm']);exit;
            $searchterm = $_SESSION['searchterm'];

            return $searchterm;
        } else {
            $searchterm = "";
            return $searchterm;
        }
    }

    public function users_search() {
        if (Session::get('AdminData')) {
            session_start();

            $data = array();
            if (!empty($_GET['email']) || !empty($_GET['is_active'])) {
                $data['email'] = $_GET['email'];
//            $data['app_type'] = $_GET['app_type'];
                $_GET['is_active'] == 2 ? $data['is_active'] = 0 : $data['is_active'] = $_GET['is_active'];
            }
            $search_term = $this->searchterm_handler($data, TRUE);
//!empty($_GET['is_active']) ? $_GET['is_active'] == 2 ? $data['is_active'] = 2 : $data['is_active'] = $_GET['is_active'] : '';
            $str = array();
            if (!empty($search_term['email'])) {
                $str['email'] = $search_term['email'];
            }
//        if (!empty($search_term['app_type'])) {
            //            $str['app_type'] = $search_term['app_type'];
//        }
            if (isset($search_term['is_active'])) {
                $str['is_active'] = $search_term['is_active'];
            }
            $search_term['groups'] = DB::select("select * from `group`");
            $search_term['categories'] = DB::select("select * from `categories`");

            $users = DB::table('users')->where($str)->paginate(10);


            $search_term['search'] = 'true';
            return View::make('admin.users', compact('users'))->with('data', $search_term);
        } else {
            return Redirect::to('/')->with('loginError', 'Invalid Email or Password.');
        }
    }

    public function getUser() {
        $lat = $_POST['lat'];
        $long = $_POST['long'];
        $d = $_POST['d'];
        $q = "SELECT last_lat as lat, last_long as `long`, uid, ( 6371 * acos( cos( radians($lat) ) * cos( radians( last_lat ) ) * cos( radians( last_long ) - radians($long) ) + sin( radians($lat) ) * sin( radians( last_lat ) ) ) ) AS distance
            FROM users
            HAVING distance < $d ORDER BY distance ";
        $users = DB::select($q);
        $result = array();

        $result['status'] = 200;
        $result['marker'] = $users;
        echo json_encode($result);
    }

    public function send_noty() {
        if (Session::get('AdminData')) {
            if ($_POST['type'] == 1) {
                $load = array("title" => $_POST['title'], "msg" => $_POST['msg'], "type" => 1);
            }
            if ($_POST['type'] == 2) {
                if (empty($_POST['image_path'])) {
                    $file = array('image' => Input::file('image'));
// setting up rules
                    $rules = array('image' => 'required',); //mimes:jpeg,bmp,png and for max size max:10000
                    // doing the validation, passing post data, rules and the messages
                    $validator = Validator::make($file, $rules);
                    if ($validator->fails()) {
// send back to the page with the input data and errors
                        echo json_encode(array("msg", 'Error in upload: try with different image'));

                        exit();
//return Redirect::to('users')->withInput()->withErrors($validator);
                    } else {
// checking file is valid.
                        if (Input::file('image')->isValid()) {
                            $destinationPath = 'upload_image'; // upload path
                            $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
                            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
                            Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
// sending back with message

                            $_POST['image_path'] = url() . '/upload_image/' . $fileName;
                        } else {
// sending back with error message.
                            echo json_encode(array("msg", 'uploaded file is not valid'));
                            exit();
// return Redirect::to('users');
                        }
                    }
                }
                $load = array("title" => $_POST['title'], "msg" => $_POST ['msg'], "image" => $_POST['image_path'], "link" => $_POST ['link'], "type" => 2);
            }
            if ($_POST['type'] == 3) {
                 $_POST['link'] = $this->addhttp($_POST['link']);
                $load = array("title" => $_POST['title'], "msg" => $_POST['msg'], "link" => $_POST['link'], "type" => 3);
            }
            if ($_POST['type'] == 4 || $_POST['type'] == 5) {
                if (empty($_POST['image_path'])) {
                    $file = array('image' => Input::file('image'));
// setting up rules
                    $rules = array('image' => 'required',); //mimes:jpeg,bmp,png and for max size max:10000
                    // doing the validation, passing post data, rules and the messages
                    $validator = Validator::make($file, $rules);
                    if ($validator->fails()) {
// send back to the page with the input data and errors
                        echo json_encode(array("msg", 'Error in upload: try with different image'));
                        exit();
//return Redirect::to('users')->withInput()->withErrors($validator);
                    } else {
// checking file is valid.
                        if (Input::file('image')->isValid()) {
                            $destinationPath = 'upload_image'; // upload path
                            $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
                            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
                            Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
// sending back with message
// Session::flash('success', 'Upload successfully');
                            $_POST['image_path'] = url() . '/upload_image/' . $fileName;
                        } else {
// sending back with error message.
                            echo json_encode(array("msg", 'Error in upload: uploaded file is not valid'));
                            exit();
// Session::flash('error', 'uploaded file is not valid');
//return Redirect::to('users');
                        }
                    }
                }
                $_POST['link'] = $this->addhttp($_POST['link']);
                $load = array("title" => $_POST['title'], "msg" => $_POST['msg'], "image" => $_POST['image_path'], "link" => $_POST ['link'], "type" => $_POST ['type'] == 4 ? 4 : 5);
            }



            $tokens = DB::select("select gcm_id from users where uid in(" . $_POST['to'] . ")");
            foreach ($tokens as $tk) {
                $token[] = $tk->gcm_id;
            }

            $this->notification($token, $load);
        } else {
            return Redirect::to('/')->with('loginError', 'Invalid Email or Password.');
        }
    }

    public function mass_noty() {
        if (Session::get('AdminData')) {
            
            if ($_POST['type'] == 1) {
                $load = array("title" => $_POST['title'], "msg" => $_POST['msg'], "type" => 1);
            }
            if ($_POST['type'] == 2) {
                if (empty($_POST['image_path'])) {
                    $file = array('image' => Input::file('image'));
// setting up rules
                    $rules = array('image' => 'required',); //mimes:jpeg,bmp,png and for max size max:10000
                    // doing the validation, passing post data, rules and the messages
                    $validator = Validator::make($file, $rules);
                    if ($validator->fails()) {
// send back to the page with the input data and errors
                        echo json_encode(array("msg", 'Error in upload: try with different image'));

                        exit();
//return Redirect::to('users')->withInput()->withErrors($validator);
                    } else {
// checking file is valid.
                        if (Input::file('image')->isValid()) {
                            $destinationPath = 'upload_image'; // upload path
                            $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
                            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
                            Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
// sending back with message

                            $_POST['image_path'] = url() . '/upload_image/' . $fileName;
                        } else {
// sending back with error message.
                            echo json_encode(array("msg", 'uploaded file is not valid'));
                            exit();
// return Redirect::to('users');
                        }
                    }
                }
                $load = array("title" => $_POST['title'], "msg" => $_POST ['msg'], "image" => $_POST['image_path'], "link" => $_POST ['link'], "type" => 2);
            }
            if ($_POST['type'] == 3) {
                $_POST['link'] = $this->addhttp($_POST['link']);
                
                $load = array("title" => $_POST['title'], "msg" => $_POST['msg'], "link" => $_POST['link'], "type" => 3);
            }
            if ($_POST['type'] == 4 || $_POST['type'] == 5) {
                if (empty($_POST['image_path'])) {
                    $file = array('image' => Input::file('image'));
// setting up rules
                    $rules = array('image' => 'required',); //mimes:jpeg,bmp,png and for max size max:10000
                    // doing the validation, passing post data, rules and the messages
                    $validator = Validator::make($file, $rules);
                    if ($validator->fails()) {
// send back to the page with the input data and errors
                        echo json_encode(array("msg", 'Error in upload: try with different image'));
                        exit();
//return Redirect::to('users')->withInput()->withErrors($validator);
                    } else {
// checking file is valid.
                        if (Input::file('image')->isValid()) {
                            $destinationPath = 'upload_image'; // upload path
                            $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
                            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
                            Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
// sending back with message
// Session::flash('success', 'Upload successfully');
                            $_POST['image_path'] = url() . '/upload_image/' . $fileName;
                        } else {
// sending back with error message.
                            echo json_encode(array("msg", 'Error in upload: uploaded file is not valid'));
                            exit();
// Session::flash('error', 'uploaded file is not valid');
//return Redirect::to('users');
                        }
                    }
                }
                $_POST['link'] = $this->addhttp($_POST['link']);
                $load = array("title" => $_POST['title'], "msg" => $_POST ['msg'], "image" => $_POST['image_path'], "link" => $_POST['link'], "type" => $_POST ['type'] == 4 ? 4 : 5);
            }

            if ($_POST['multi_sel'] == 'on' || !empty($_POST['cat_id'])) {

                $lat = $_POST['lat'];
                $long = $_POST['long'];
                $d = $_POST['d'];
//                if (!empty($_POST['cat_id']) && $_POST['multi_sel'] == 'on') {
//                    foreach ($_POST['cat_id'] as $id) {
//                        $q = "SELECT  gcm_id,( 6371 * acos( cos( radians($lat) ) * cos( radians( last_lat ) ) * cos( radians( last_long ) - radians($long) ) + sin( radians($lat) ) * sin( radians( last_lat ) ) ) ) AS distance
//            FROM users where is_active = 1 and FIND_IN_SET('$id',categories) HAVING distance < $d ORDER BY distance";
//
//                        $users = DB::select($q);
//                        $arr_tk[] = $users;
//                    }
//                } else 
                if (!empty($_POST ['cat_id']) && $_POST['multi_sel'] == 'off') {
                    $q = "SELECT cat_slug from categories where id in(" . implode(',', $_POST['cat_id']) . ")";
                    $cats = DB::select($q);
                    $this->topic_noty($cats, $load);
                } else if (empty($_POST['cat_id']) && $_POST['multi_sel'] == 'on') {
                    $q = "SELECT  gcm_id,( 6371 * acos( cos( radians($lat) ) * cos( radians( last_lat ) ) * cos( radians( last_long ) - radians($long) ) + sin( radians($lat) ) * sin( radians( last_lat ) ) ) ) AS distance
            FROM users where is_active = 1 HAVING distance < $d ORDER BY distance";

                    $users = DB::select($q);
                    $arr_tk[] = $users;
                }

                if (!empty($arr_tk[0])) {
                    foreach ($arr_tk as $arr) {
                        foreach ($arr as $a) {
                            $token[] = $a->gcm_id;
                        }
                    }
                   
                    $this->notification($token, $load);
                }
            }
          
        } else {
            return Redirect::to('/')->with('loginError', 'Invalid Email or Password.');
        }
    }
    function addhttp($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }
    function topic_noty($grps, $load) {
        $url = 'https://fcm.googleapis.com/fcm/send';
         $var = Session::get('AdminData'); 
         
        $key = $var->api_key;
        $headers = array(
            'Authorization: key=' . $key,
            'Content-Type: application/json'
        );
        
        $str = '';
        
        foreach ($grps as $val) {
           if(!empty($val->cat_slug)){
                $str .=  "'$val->cat_slug' in topics ||";
           }
        }
        
        $str = rtrim($str, '||');
        
        
            $fields = array(
                'condition' => $str,
                'data' => $load
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            echo $result; 
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            curl_close($ch);
       
        die;
    }

    public function page($user) {
        return view('admin.' . $user);
    }

    public function all() {
        $books = Book::all();
        return $this->response->withCollection($books, new BookTransformer);
    }

    public function SendCatNoty() {
        if (Session::get('AdminData')) {
            $categories = DB::select("select cat_name,id from categories");
            return View::make('admin.send_noty', compact('categories'))->with('categories', $categories);
        } else {
            return Redirect::to('/')->with('loginError', 'Invalid Email or Password.');
        }
    }
   
    public function notification($token, $load, $type = NULL) {
        
        $var = Session::get('AdminData'); 
         
        $key = $var->api_key;
         $url = 'https://fcm.googleapis.com/fcm/send';
        $key = "$key";
        
        $token = implode(',', $token);
        
        
        $fields = array(
            'to' => $token,
            'data' => $load
        );
        $headers = array(
            'Authorization: key=' . $key,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        echo $result;
        die;
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
    }
    
    public function setting($par = NULL){
      if($par == "add"){
            DB::table('admin')
                             ->update(['api_key' => $_POST['api_key']]);
            return Redirect::to('settings');
        }
        $setting = DB::table('admin')->select('*')->first();
        Session::put('AdminData', $setting);
        return View::make('admin.setting')->with('data', $setting);
    }

}
