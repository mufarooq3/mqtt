<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\DB;
use DateTime;

class AdminModel extends Model
{
// *********************************** Login
    static function chk_login($data)
    {
        $res = DB::table('admin_login')
            ->where('email', $data['email'])
            ->where('password', $data['password'])
            ->get();

//        $res = DB::select("select * from user where email='".$data['email']."' and password='".$data['password']."'");
        $val = json_decode(json_encode($res), true);
        $val = array_filter($val);
        if (!empty($val)) {
            return $val[0];
        } else {
            return false;
        }
    }

// ************************************** PC Details
    static function getAllCat()
    {
        $res = DB::table('categories')->get()->paginate(3);
        $res = json_decode(json_encode($res), true);
       
        if (!empty($res)) {
            return $res;
        } else {
            return false;
        }
    }

    static function getPcDetailSrc($data)
    {
        $res = DB::table('pc_details')->where('pc_no', $data['srcPcNo'])->get();
        $res = json_decode(json_encode($res), true);
        if (!empty($res)) {
            return $res;
        } else {
            return false;
        }
    }

    static function chkPcNumber($pcno)
    {
        $res = DB::table('pc_details')->where('pc_no', $pcno['cPCNo'])->get();
        $res = json_decode(json_encode($res), true);
        if (!empty($res)) {
            return 1;
        } else {
            return 0;
        }
    }

    static function getPCEdit($data)
    {
        unset($data['_token']);
        $res = DB::table('pc_details')->where('id', $data['id'])->first();
        $res = json_decode(json_encode($res), true);
        return $res;
    }

    static function postAddPcData($data)
    {
        $dt = new DateTime();
        unset($data['_token']);
        $inData = $data;
        $inData['add_date'] = $dt->format('Y-m-d');
        try {
            DB::table('pc_details')->insert($inData);
            return 1;
        } catch (\Exception $e) {
            return "Error";
        }
    }

    static function updatePCData($data)
    {
        unset($data['_token']);
        try {
            DB::table('pc_details')->where('id', $data['id'])->update($data);
            return 1;
        } catch (\Exception $e) {
            return "Error";
        }
    }

    static function deletePcData($data)
    {
        unset($data['_token']);
        try {
            DB::table('pc_details')->delete($data['id']);
            return 1;
        } catch (\Exception $e) {
            return 0;
        }

    }

// ************************ Current Online Employee
    static function getCurrEmp()
    {

        $res = DB::select('SELECT t.*, ed.firstname FROM `emp_login` el LEFT JOIN emp_detail ed ON el.emp_id = ed.emp_id LEFT JOIN timetrack t ON t.emp_id = ed.emp_id where el.status = 1 GROUP BY el.id ORDER BY t.id DESC');
        $res = json_decode(json_encode($res), true);
        return $res;

//        $res = DB::table('timetrack')
//            ->join('emp_detail', 'timetrack.emp_id', '=', 'emp_detail.emp_id')
//            ->join('emp_login', 'emp_login.emp_id', '=', 'emp_detail.emp_id')
//            ->where('emp_login.status', 1)
//            ->select('timetrack.*', 'emp_detail.firstname')
//            ->groupBy('emp_id')
//            ->orderBy('id', 'desc')
//            ->take(1)->get();

//        SELECT t.* FROM `emp_login` el LEFT JOIN emp_detail ed ON el.emp_id = ed.emp_id LEFT JOIN timetrack t ON t.emp_id = ed.emp_id where el.status = 1 GROUP BY el.id ORDER BY t.id DESC
    }

    static function srcCurrEmpData($id){
        $res = DB::select("SELECT t.*, ed.firstname FROM `emp_login` el LEFT JOIN emp_detail ed ON el.emp_id = ed.emp_id LEFT JOIN timetrack t ON t.emp_id = ed.emp_id where el.status = 1 and t.emp_id = '" . $id . "' GROUP BY el.id ORDER BY t.id DESC");
        $res = json_decode(json_encode($res), true);
        return $res;
    }

// *********************************** File Page
    static function getAllfile()
    {
        $res = DB::table('file_info')->get();
        $res = json_decode(json_encode($res), true);
        return $res;
    }

    static function addFileData($data){
        unset($data['_token']);
        try{
            DB::table('file_info')->insert($data);
            return 1;
        }catch (\Exception $e){
            return 0;
        }
    }

    static function deleteFileData($data){
        
        unset($data['_token']);
        try {
            DB::table('file_info')->delete($data['id']);
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

// *********************************** Time Track Page
    static function getTimeTrackData()
    {
        $res = DB::table('timetrack')->get();
        $res = json_decode(json_encode($res), true);
        if (!empty($res)) {
            return $res;
        } else {
            return false;
        }
    }

    static function getAllEmp()
    {
        $res = DB::table('emp_login')->select('emp_id')->get();
        $res = json_decode(json_encode($res), true);
        return $res;
    }

// ******************************* Work Allocation Page
    static function getAllWork()
    {
        $res = DB::table('work_allocation')->get();
        $res = json_decode(json_encode($res), true);
        return $res;
    }

// ******************************* Photos Page
    static function getAllPhotos()
    {
        $res = DB::table('photos')->get();
        $res = json_decode(json_encode($res), true);
        return $res;
    }

// ***************************** View Employee Page
    static function getAllEmpData()
    {
        $res = DB::table('emp_detail')->get();
        $res = json_decode(json_encode($res), true);
        return $res;
    }
}
