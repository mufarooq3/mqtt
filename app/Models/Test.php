<?php

namespace App\models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Test extends Model {

    public static function getUsers() {
        $results = DB::select('select * from users');
        return $results;
    }

    public static function findOrFail($id) {
        $results = DB::select("select * from users where id = $id");
        return $results;
    }

}

?>