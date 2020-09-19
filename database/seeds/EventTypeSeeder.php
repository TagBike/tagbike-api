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
            $arrEventNames = [
                "event.action.administration.customer.account.created",
                "event.action.administration.customer.blockedaccount",
                "event.action.administration.customer.blockedaccount.reason",
                "event.action.customer.account.new",
                "event.action.customer.account.update",
                "event.action.customer.bike.add",
                "event.action.customer.bike.archive",
                "event.action.customer.bike.update",
                "event.action.customer.bike.search",
                "event.action.customer.bike.search.ownbike",
                "event.action.customer.bike.changeproperty",
                "event.action.sos.customer.accident",
                "event.action.sos.customer.wasstolen",
                "event.action.sos.bike.wasstolen",
                "event.action.user.customer.account.new",
                "event.action.user.customer.account.update",
                "event.action.user.customer.account.resetpassword",
                "event.action.user.customer.bike.add",
                "event.action.user.customer.bike.archive",
                "event.action.user.customer.bike.update",
                "event.action.user.customer.bike.changeproperty",
                "event.action.user.account.new",
                "event.action.user.account.update",
                "event.action.user.account.resetpassword",
                "event.system.administration.customer.blockedaccount",
                "event.system.administration.customer.blockedaccount.reason",
                "event.log.customer.web.access",
                "event.log.customer.web.app.android",
                "event.log.customer.web.app.ios"              
            ];
            $arrEventKeys = [
                "event.action.administration.customer.account.created",
                "event.action.administration.customer.blockedaccount",
                "event.action.administration.customer.blockedaccount.reason",
                "event.action.customer.account.new",
                "event.action.customer.account.update",
                "event.action.customer.bike.add",
                "event.action.customer.bike.archive",
                "event.action.customer.bike.update",
                "event.action.customer.bike.search",
                "event.action.customer.bike.search.ownbike",
                "event.action.customer.bike.changeproperty",
                "event.action.sos.customer.accident",
                "event.action.sos.customer.wasstolen",
                "event.action.sos.bike.wasstolen",
                "event.action.user.customer.account.new",
                "event.action.user.customer.account.update",
                "event.action.user.customer.account.resetpassword",
                "event.action.user.customer.bike.add",
                "event.action.user.customer.bike.archive",
                "event.action.user.customer.bike.update",
                "event.action.user.customer.bike.changeproperty",
                "event.action.user.account.new",
                "event.action.user.account.update",
                "event.action.user.account.resetpassword",
                "event.system.administration.customer.blockedaccount",
                "event.system.administration.customer.blockedaccount.reason",
                "event.log.customer.web.access",
                "event.log.customer.web.app.android",
                "event.log.customer.web.app.ios"
            ];
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