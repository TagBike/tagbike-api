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
    
}
