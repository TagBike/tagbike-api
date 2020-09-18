<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventType extends Model
{
    use SoftDeletes;

    protected $table = 'event_types';

    public function getTypeByKey($typeId) {
        return $this->where('key','=', $typeId)->distinct()->get();
    }
}
