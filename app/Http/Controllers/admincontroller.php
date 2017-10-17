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
        if (Session::get('AdminData')) {
         $users = DB::select("select count(*) as cnt from users");
         $data['cnt_users'] = $users[0]->cnt; 
         
         $noti = DB::select("select count(*) as cnt from notifications");
         $data['cnt_noti'] = $noti[0]->cnt; 
         
         $cat = DB::select("select count(*) as cnt from categories");
         $data['cnt_cat'] = $cat[0]->cnt; 
         
         $mnth_user = DB::select("select count(*) as cnt from users where MONTH(time) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)");
         $data['cnt_last_mnth_user'] = $mnth_user[0]->cnt; 
		 
		 $device_os = DB::select("select device_os as label ,count(uid) as data from users where device_api != 'IOS' group by device_os order by device_os desc");
		 $data['cnt_device_os'] = $device_os;

		 $device_os = DB::select("select device_os as label ,count(uid) as data from users where device_api = 'IOS' group by device_os order by device_os desc");
		 $data['cnt_device_os_ios'] = $device_os;
		 
		 $device_os = DB::select("SELECT concat(' ', CAST((COUNT(*) / (SELECT COUNT(*) FROM users)) * 100 AS UNSIGNED),'% ',SUBSTR(device_model,1,20))as label,CAST((COUNT(*) / (SELECT COUNT(*) FROM users)) * 100 AS UNSIGNED) as data FROM `users` where device_api != 'IOS' group by device_model order by data desc");
		 
			$arr_2 = array();
			if(!empty($device_os)){
				$i = 0;
				$arr = array();
				$other = 0;
				foreach($device_os as $key => $val){
					if($i <= 11){
						$arr[] = $val;
					}else{
						$other = $other + $val->data;
					}
					$i++;
				}
				$arr_new = array("13"=>(object)array("label" => " $other% other","data"=>$other));
				$arr_2 = array_merge($arr,$arr_new);
			}
			
			$data['cnt_device_model'] = json_encode($arr_2,true);	
			
			$device_os = DB::select("SELECT concat(' ', CAST((COUNT(*) / (SELECT COUNT(*) FROM users)) * 100 AS UNSIGNED),'% ',SUBSTR(device_model,1,20))as label,CAST((COUNT(*) / (SELECT COUNT(*) FROM users)) * 100 AS UNSIGNED) as data FROM `users` where device_api = 'IOS' group by device_model order by data desc");
		 
			$arr_2 = array();
			if(!empty($device_os)){
				$i = 0;
				$arr = array();
				$other = 0;
				foreach($device_os as $key => $val){
					if($i <= 3){
						$arr[] = $val;
					}else{
						$other = $other + $val->data;
					}
					$i++;
				}
				$arr_new = array("5"=>(object)array("label" => " $other% other","data"=>$other));
				$arr_2 = array_merge($arr,$arr_new);
			}
			
			$data['cnt_device_model_ios'] = json_encode($arr_2,true);
			
			$cnt_iphone = DB::select("select count(*) as cnt from users where device_api = 'IOS'");
			$data['cnt_iphone'] = $cnt_iphone[0]->cnt;
			
			$cnt_android = DB::select("select count(*) as cnt from users where device_api != 'IOS'");
			$data['cnt_android'] = $cnt_android[0]->cnt;
			
			
			return View::make('admin.dashboard', compact('result'))->with('data', $data);
          } else {
            Session::put('msg', 'Invalid Email or Password.');
            Session::put('type', 'error');
            return Redirect::to('/');
        }
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
    
    public function Location_Noty() {
        if (Session::get('AdminData')) {
            $admin_data = DB::select("select * from admin");
			return View::make('admin.send_loc_noty', compact('admin_data'))->with('admin_data', $admin_data[0]);
            //return View::make('admin.send_loc_noty');
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
                    if (!empty($_FILES['image']['name']) && empty($_POST['image_path'])) {
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
                dd($users);
				
				
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
            if (!empty($_GET['email']) || !empty($_GET['is_active']) || !empty($_GET['device'])) {
                $data['email'] = $_GET['email'];
				$data['device'] = $_GET['device'];
                $_GET['is_active'] == 2 ? $data['is_active'] = 0 : $data['is_active'] = $_GET['is_active'];
            }
            $search_term = $this->searchterm_handler($data, TRUE);

            $str = '';
			
            if (!empty($search_term['email'])) {
                $str .= ' AND email like "%' . $search_term['email'] . '%"';
            }
			if (!empty($search_term['device'])) {
				if($search_term['device'] == 'iphone'){
					 $str .= ' AND device_api = "IOS"';
				}else if ($search_term['device'] == 'android'){
					$str .= ' AND device_api != "IOS"';
				}
               
            }
			if ($search_term['is_active'] == 1) {
                $str .= ' AND is_active = "' . $search_term['is_active'] . '"';
            }else if($_GET['is_active'] == 2){
					$str .= ' AND is_active = "' . $search_term['is_active'] . '"';
			}
           
		$users = DB::table('users')   
				->selectRaw('*')
				->whereRaw('1=1'.$str)
				->paginate(10);
           
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
               $file = array('image' => Input::file('image'));
				
               if (empty($_POST['image_path']) && !empty($file['image'])) {
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
				$_POST['link'] = $this->addhttp($_POST['link']);
                $load = array("title" => $_POST['title'], "msg" => $_POST ['msg'], "image" => $_POST['image_path'], "link" => $_POST ['link'], "type" => 2);
            }
            if ($_POST['type'] == 3) {
                 $_POST['link'] = $this->addhttp($_POST['link']);
                $load = array("title" => $_POST['title'], "msg" => $_POST['msg'], "link" => $_POST['link'], "type" => 3);
            }
            if ($_POST['type'] == 4 || $_POST['type'] == 5) {
                $file = array('image' => Input::file('image'));
				
               if (empty($_POST['image_path']) && !empty($file['image'])) {
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
            if(!empty($tokens)){
				foreach ($tokens as $tk) {
					$token[] = $tk->gcm_id;
				}
			}

            $this->notification($token, $load);
        } else {
            return Redirect::to('/')->with('loginError', 'Invalid Email or Password.');
        }
    }

    
    public function mass_noty_loc(){
                if (Session::get('AdminData')) {
            
            if ($_POST['type'] == 1) {
                $load = array("title" => $_POST['title'], "msg" => $_POST['msg'], "type" => 1);
            }
            if ($_POST['type'] == 2) {
				$file = array('image' => Input::file('image'));
				
               if (empty($_POST['image_path']) && !empty($file['image'])) {
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
                $file = array('image' => Input::file('image'));
				
               if (empty($_POST['image_path']) && !empty($file['image'])) {
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

            

                $lat = $_POST['lat'];
                $long = $_POST['long'];
                $d = $_POST['d'];

               
                    $q = "SELECT  gcm_id,( 6371 * acos( cos( radians($lat) ) * cos( radians( last_lat ) ) * cos( radians( last_long ) - radians($long) ) + sin( radians($lat) ) * sin( radians( last_lat ) ) ) ) AS distance
            FROM users where is_active = 1 HAVING distance < $d ORDER BY distance";

                    $users = DB::select($q);
                    $arr_tk[] = $users;
                

                if (!empty($arr_tk[0])) {
                    foreach ($arr_tk as $arr) {
                        foreach ($arr as $a) {
                            $token[] = $a->gcm_id;
                        }
                    }
                   
                    $this->notification($token, $load);
                }
           
          
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
				$file = array('image' => Input::file('image'));
				
               if (empty($_POST['image_path']) && !empty($file['image'])) {
                   
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
				$_POST['link'] = $this->addhttp($_POST['link']);
                $load = array("title" => $_POST['title'], "msg" => $_POST ['msg'], "image" => $_POST['image_path'], "link" => $_POST ['link'], "type" => 2);
            }
            if ($_POST['type'] == 3) {
                $_POST['link'] = $this->addhttp($_POST['link']);
                
                $load = array("title" => $_POST['title'], "msg" => $_POST['msg'], "link" => $_POST['link'], "type" => 3);
            }
            if ($_POST['type'] == 4 || $_POST['type'] == 5) {
                $file = array('image' => Input::file('image'));
				
               if (empty($_POST['image_path']) && !empty($file['image'])) {
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

                
                $q = "SELECT cat_slug from categories where id in(" . implode(',', $_POST['cat_id']) . ")";
                $cats = DB::select($q);
                $this->topic_noty($cats, $load);
               
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
		$load['content_available'] = true;
		$load['body'] = $load['msg'];
		$id = DB::table('notifications')->insertGetId(
                            ['title' => $load['title']]);
		
        $url = 'https://fcm.googleapis.com/fcm/send';
         $var = Session::get('AdminData'); 
         
        $key = $var->api_key;
        $headers = array(
            'Authorization: key=' . $key,
            'Content-Type: application/json'
        );
		
		$curl_arr = array();
		$master = curl_multi_init();
		$i = 0;
		$str = array();
        
        foreach ($grps as $val) {
           if(!empty($val->cat_slug)){
                $str[]=  "'$val->cat_slug' in topics";
           }
        }
		
		foreach (array_chunk($str, 3) as $courseRow){
			//print_r ($courseRow); die;
				$fields = array(
					'condition' => implode(' || ',$courseRow),
					'content_available'=>true,
					'priority'=>"high",
					'data' => $load,
					'notification' => $load
				);
				
			
			$curl_arr[$i] = curl_init($i);
			
			curl_setopt($curl_arr[$i], CURLOPT_URL, $url);
			curl_setopt($curl_arr[$i], CURLOPT_POST, true);
			curl_setopt($curl_arr[$i], CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_arr[$i], CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl_arr[$i], CURLOPT_POSTFIELDS, json_encode($fields,true));
			
			curl_multi_add_handle($master, $curl_arr[$i]);
			$i++;
		}
		
       
			do {
				curl_multi_exec($master,$running);
			} while($running > 0);
		
        $i = 0;
		
       foreach (array_chunk($str, 3) as $courseRow)
		{
			$results[] = curl_multi_getcontent  ( $curl_arr[$i]  );
			$i++;
		}
		
		echo json_encode(array("status"=>"success"));
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
        $load['content_available'] = true;
		$load['body'] = $load['msg'];
		$id = DB::table('notifications')->insertGetId(
                            ['title' => $load['title']]);
		
		$var = Session::get('AdminData'); 
         
        $key = $var->api_key;
        $url = 'https://fcm.googleapis.com/fcm/send';
        $curl_arr = array();
		$master = curl_multi_init();
		$i = 0;
		$headers = array(
					'Authorization: key=' . $key,
					'Content-Type: application/json'
				);
			
		foreach (array_chunk($token, 999) as $courseRow){
			//print_r ($courseRow); die;
				$fields = array(
				'registration_ids' => $courseRow,
					"content_available"=>true,
					"priority"=>"high",
					'data' => $load,
					'notification' => $load
				);
				
			
			$curl_arr[$i] = curl_init($i);
			
			curl_setopt($curl_arr[$i], CURLOPT_URL, $url);
			curl_setopt($curl_arr[$i], CURLOPT_POST, true);
			curl_setopt($curl_arr[$i], CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_arr[$i], CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl_arr[$i], CURLOPT_POSTFIELDS, json_encode($fields,true));
			
			curl_multi_add_handle($master, $curl_arr[$i]);
			$i++;
		}
		
       
			do {
				curl_multi_exec($master,$running);
			} while($running > 0);
		
        $i = 0;
		
       foreach (array_chunk($token, 999) as $courseRow)
		{
			$results[] = curl_multi_getcontent  ( $curl_arr[$i]  );
			$i++;
		}
		
		echo json_encode(array("status"=>"success"));
        die;
     
		/*
        $var = Session::get('AdminData'); 
         
        $key = $var->api_key;
         $url = 'https://fcm.googleapis.com/fcm/send';
        
         $fields = array(
            'registration_ids' => $token,
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
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields,true));
        $result = curl_exec($ch);
        echo $result;
        die;
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch); */
    }
    
    public function setting($par = NULL){
      if($par == "add"){
             if(!empty($_POST['old_password']) && !empty($_POST['new_password'])){
				 $mnth_user = DB::select("select * from admin where password = md5('".$_POST['old_password']."')");
					if(!empty($mnth_user)){
						 DB::table('admin')
                             ->update(['password'=>md5($_POST['new_password'])]);
							 Session::put('msg', 'password successfully changed');
							 Session::put('type', 'success');
							 
					}else{
						 Session::put('msg', 'old password are wrong');
						 Session::put('type', 'error');
						 return Redirect::to('settings');
					}
			
		  }else{
			  DB::table('admin')
                             ->update(['api_key' => $_POST['api_key'],"google_api_key"=>$_POST['google_api_key']]);
							 Session::put('msg', 'Keys successfully changed');
							 Session::put('type', 'success');
            return Redirect::to('settings');
		  }
        }
        $setting = DB::table('admin')->select('*')->first();
        Session::put('AdminData', $setting);
        return View::make('admin.setting')->with('data', $setting);
    }


}
