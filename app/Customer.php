<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

use App\Bike;
use App\Event;

class Customer extends Model implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;

    protected $hidden = ['password', 'token'];

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public function bikes($id) {
        return Bike::where('customer_id', '=',$id);
    }

    public function events($id) {
        return Event::where('ownerId', '=',$id);
    }
}
