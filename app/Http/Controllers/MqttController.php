<?php

namespace App\Http\Controllers;

use App\Mqtt\MQTT;
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
}
