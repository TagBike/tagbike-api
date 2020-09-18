<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\EventType;

class Event extends Model
{
    use SoftDeletes;

    protected $type;

    public function __construct() {
        $this->type = new EventType();        
    }

    public function types() {
        return $this->type;
    }
    
    public static function register ($event) {
        $types = new EventType();
        $typeKey = '';
        $ownerId = '';
        $data = null;
        if(!empty($event['eventType'])){
            $typeKey = $event['eventType'];
        }
        
        if(!empty($event['ownerId'])){
            $ownerId = $event['ownerId'];
        }

        if(!empty($event['data'])){
            $data = $event['data'];
        }

        if($typeKey) {
            $type = $types->getTypeByKey($typeKey);
            $id = $type[0]->id;
            if($id) {
                $content = [
                    'eventType' => $id,
                    'ownerId' => $ownerId,
                    'data' => $data
                ];

                self::insert($content);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
