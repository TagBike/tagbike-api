<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bike extends Model
{
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function events($id) {
        return Event::where('data->bikeId', '=',$id);
    }
}
