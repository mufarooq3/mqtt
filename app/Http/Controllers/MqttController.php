<?php

namespace App\Http\Controllers;

use App\Mqtt\MQTT;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MqttController extends Controller
{
    public function publish($load, $cats){
//        dd($load);

        $server = "174.138.30.204";     // change if necessary
        $port = 1883;                     // change if necessary
        $username = "";                   // set your username
        $password = "";                   // set your password
        $client_id = "MQTT-publisher"; // make sure this is unique for connecting to sever - you could use uniqid()

        $mqtt = new MQTT($server, $port, $client_id);


        if ($mqtt->connect(true, NULL, $username, $password)) {
            $mqtt->publish($cats[0]->cat_slug, json_encode($load), 0);
            $mqtt->close();
            echo json_encode(array("status"=>"success"));
            die;
        } else {

            echo "Time out!\n";
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
            $this->publish($load,$cats);
            //$this->topic_noty($cats, $load);

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

    public function emqhook(Request $request){
        DB::select("insert into test(`web_hook`) values('".$request->getContent()."')");
        $r = json_decode($request->getContent());
        if($r->action == "client_connected"){
            $this->client_connected($r);
        }
        else if($r->action == "client_disconnected"){
            $this->client_disconnected($r);
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
}
