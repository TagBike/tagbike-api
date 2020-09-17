<?php

use Illuminate\Database\Seeder;
use App\EventType;

class EventTypeSeeder extends Seeder
{
     /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        if (!EventType::count()) {
            $arrEventNames = ['Administrador', 'resetou senha de cliente', 'null', 'null', 'null', 'null'];
            $arrEventKeys = ['event', 'action', 'user', 'customer', 'account', 'resetpassword'];
            $arrEvent = array_map(null, $arrEventNames, $arrEventKeys);

            foreach ($arrEvent as $event) {
                list($name, $key) = $event;
                EventType::insert([
                    'name' => $name,
                    'key' => $key, 
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                ]);
            }
        }
    }
}