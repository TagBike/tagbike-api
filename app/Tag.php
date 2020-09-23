<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    public static function register ($bikeId) {

        if(!empty($bikeId)) {
            $hash = self::generateHash(8);
            self::insert([
                'id_bike' => $bikeId,
                'hash' => $hash
            ]);
        } else {
            return false;
        }
    }

    protected static function generateHash($length) {
        $key = '';
        $pool = array_merge(range(0,9),range('A', 'Z'));
    
        for($i=0; $i < $length; $i++) {
            $key .= $pool[mt_rand(0, count($pool) - 1)];
        }
        return $key;
    }
}
