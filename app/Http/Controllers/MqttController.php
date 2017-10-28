<?php

namespace App\Http\Controllers;

use App\Category;
use App\TestModal;
use App\notification;
use App\User;
use App\user_notification;
use App\UserCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MqttController extends Controller
{
    public function publish($load, $cats){
        $len=sizeof($cats);
        for ($i=0; $i<$len; ++$i) {
            $n=new notification();
            $n->type=$load['type'];
            $n->title=$cats[$i]->cat_slug;
            $n->message="Thank for using wing service";
            $n->payload=json_encode($load);
            $n->saved=1;
//            $n->deletion_date=time();
            $n->save();
            $load["message_id"]=$n->nid;
            $payload["topic"]=$cats[$i]->cat_slug;
            $payload["payload"]=json_encode($load);
            $payload["qos"]=2;
            $payload["retain"]=false;
            $payload["client_id"]="";
//            dd((json_decode(json_encode($payload))));
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_PORT => "18083",
                CURLOPT_URL => "http://174.138.30.204:18083/api/v2/mqtt/publish",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
//                CURLOPT_POSTFIELDS => '{"topic": "' . $cats[$i]->cat_slug . '","payload": \''.json_encode($load).'\',"qos": 2,"retain": false,"client_id": ""}',
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/x-www-form-urlencoded",
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
//            dd($response);

            curl_close($curl);

//            if ($err) {
//                echo json_encode($err);
//            } else {
//                echo json_encode($response);
//            }
        }
    }

    public function publish_single($load, $users){
        $n=new notification();
        $n->type=$load['type'];
        $n->title=$load['title'];
        $n->message="Thank for using wing service";
        $n->payload=json_encode($load);
        $n->saved=1;
        $n->save();
        $load["message_id"]=$n->nid;

        $len=sizeof($users);
        for ($i=0; $i<$len; ++$i) {
            $payload["topic"]='$client/' . $users[$i];
            $payload["payload"]=$load;
            $payload["qos"]=2;
            $payload["retain"]=false;
            $payload["client_id"]="";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_PORT => "18083",
                CURLOPT_URL => "http://174.138.30.204:18083/api/v2/mqtt/publish",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/x-www-form-urlencoded",
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
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


            if(Input::file('user_list')){
                $file=file_get_contents(Input::file('user_list'));
                $users=explode(',',$file);
                $this->publish_single($load,$users);
            }
            else {
                $q = "SELECT cat_slug from categories where id in(" . implode(',', $_POST['cat_id']) . ")";
                $cats = DB::select($q);
                $this->publish($load, $cats);
            }
            //$this->topic_noty($cats, $load);

        } else {
            return Redirect::to('/')->with('loginError', 'Invalid Email or Password.');
        }

        return response("sucess",200);
    }

    function addhttp($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }

    public function send_noty() {
//        dd($request);
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



            if(isset($_POST['cat_id'])){
                $q = "SELECT cat_slug from categories where id in(" . implode(',', $_POST['cat_id']) . ")";
                $cats = DB::select($q);
                $this->publish($cats, $load);
            }
            else{
                $client_id=DB::select("SELECT gcm_id from users where email='".$_POST['to']."'");
                if($client_id) {
                    $this->publish_single($load, array($client_id[0]->gcm_id));
                    return response("message sent", 200);
                }
                else{
                    return response("Email not found!",500);
                }
            }


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

            $users=[];

            if (!empty($arr_tk[0])) {
                foreach ($arr_tk as $arr) {
                    foreach ($arr as $a) {
                        array_push($users,$a->gcm_id);
                    }
                }

                $this->publish_single($load,$users);
            }


        } else {
            return Redirect::to('/')->with('loginError', 'Invalid Email or Password.');
        }
    }

    public function emqhook(Request $request){
        //dummy insertion for testing
//        DB::select("insert into test(`web_hook`) values('".$request->getContent()."')");
//        $r=str_replace(":\"{",":{",$request->getContent());
//        $r=str_replace("}\",","},",$r);
//        $r=str_replace(" \"","\"",$r);

        $r=$request->getContent();
        $test=new TestModal();
        $test->web_hook=$r;
        $test->save();
        $r=json_decode($r);

//        $id = DB::select("insert into test(`web_hook`) values('Ch: ".$r."')");
//        dd($id);
//        DB::select("insert into test(`web_hook`) values('".$r."')");
//        DB::select("insert into test(`web_hook`) values('".gettype($r)."')");
//        $str = utf8_encode($r);
//        DB::select("insert into test(`web_hook`) values('".$str."')");
//        $lo = json_decode($str);

        DB::select("insert into test(`web_hook`) values('".json_last_error()."')");
//
//        DB::select("insert into test(`web_hook`) values('".$lo."')");
//        DB::select("insert into test(`web_hook`) values('1')");

        if($r->action == "client_connected"){
            $this->client_connected($r);
        }
        else if($r->action == "client_disconnected"){
            $this->client_disconnected($r);
        }
        else if($r->action == "client_subscribe"){
            $this->client_subscribe($r);
        }
        else if($r->action == "client_unsubscribe"){
            $this->client_unsubscribe($r);
        }
        else if($r->action == "message_delivered"){
            $this->message_delivered($r);
        }
        else if($r->action == "message_acked"){
            $this->message_acked($r);
        }
    }

    function client_connected($r){
        $user=User::where('gcm_id',$r->client_id)->first();
        if(!$user){
            $user=new User();
            $user->gcm_id=$r->client_id;
            $user->is_active=1;
            $user->username=$this->get_username($r->client_id);

//Here we write code for getting other fields of database from another API
            $user->wing_acc=$this->generateRandomString(7);
            $user->phone = rand()*1000;
            $user->last_long=rand();
            $user->last_lat=rand();


            $user->save();
        }
        else{
            $user->is_active=1;
            $user->update();
        }
    }

    function get_username($client_id){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => "18083",
            CURLOPT_URL => "http://174.138.30.204:18083/api/v2/clients/".$client_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                ),
            ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err)
            echo "cURL Error #:" . $err;

        return (json_decode($response)->result->objects[0]->username);
    }

    function client_disconnected($r){
        $user=User::where("gcm_id",$r->client_id)->first();
//        dd($user);
        if($user){
            $user->is_active=0;
            $user->update();
        }
    }

    function client_subscribe($r){

        $user = User::where('gcm_id',$r->client_id)->first();
        if($user){
            $user->category=$r->topic;
            $cat=Category::where('cat_slug',$r->topic)->first();
            if($cat) {
                UserCategory::firstOrCreate(['cat_id'=>$cat->id,'user_id'=>$user->uid]);
                $user->update();
            }
        }
    }

    function client_unsubscribe($r){
        $user = User::where('gcm_id',$r->client_id)->first();
        if($user){
            $user->category=null;
            $cat=Category::where('cat_slug',$r->topic)->first();
            if($cat) {
                UserCategory::destroy(['cat_id'=>$cat->id,'user_id'=>$user->uid]);
                $user->update();
            }
        }
    }

    function message_delivered($r){
        $gsm=$r->client_id;
        $msg_id=$r->payload->message_id;
//        dd($msg_id, $gsm);
        $user_notification=new user_notification();
        $user_notification->user_gsm_id=$gsm;
        $user_notification->notification_id=$msg_id;
        $user_notification->status='delivered';
        $user_notification->save();
//        user_notification::updateOrCreate(['user_gsm_id'=>$gsm, 'notification_id'=>$msg_id, 'status'=>'delivered']);
    }

    function message_acked($r){
        $gsm=$r->client_id;
        $msg_id=$r->payload->message_id;
        user_notification::where(['user_gsm_id'=>$gsm, 'notification_id'=>$msg_id])->update(['status'=>'Send']);
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
