<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Event;
use App\EventType;
use App\Bike;
use App\User;
use App\Customer;

class EventController extends Controller
{

    private $bike;
    private $user;
    private $loggedUser;

    public function __construct(Event $event, Bike $bike, User $user, Customer $customer){
        $this->middleware('auth:api');
        $this->loggedUser = Auth::user();
        $this->event = $event;
        $this->bike = $bike;
        $this->user = $user;
        $this->customer = $customer;
    }

    public function index() {
        $data = $this->event
            ->join('event_types', 'events.eventType', '=', 'event_types.id')
            ->select('events.*', 'event_types.name as eventName', 'event_types.key as eventKey')
            ->get();

        return response()->json($data);
    }

    public function show($id) {
        $data = $this->event
            ->join('event_types', 'events.eventType', '=', 'event_types.id')
            ->select('events.*', 'event_types.name as eventName', 'event_types.key as eventKey')
            ->find($id);

        if (! $data) return response()->json('error', 404);
        return response()->json($data);
    }

    public function create(Request $request) {

        $ownerId = $request->input('ownerId');
        $eventType = $request->input('eventType');
        $createdBy = $request->input('createdBy');
        $data = $request->input('data');
        $eventTime = $request->input('eventTime');        
        

        if($ownerId == '') {
            return response()->json('Por favor informe o usuário responsável!');
        }

        if($eventType == '') {
            return response()->json('Por favor informe o tipo de evento!');
        }

        if($createdBy === '') {
            $userId = Auth::user()->id;
        }

        $model = new Event();
        $model->ownerId = $ownerId;
        $model->eventType = $eventType;
        $model->createdBy = $createdBy;
        $model->data = $data;
        $model->eventTime = $eventTime;
        
        $model->save();

        return response()->json("success", 202);

    } 

    public function update(Request $request, $id) {

        $ownerId = $request->input('ownerId');
        $eventType = $request->input('eventType');
        $createdBy = $request->input('createdBy');
        $data = $request->input('data');
        $eventTime = $request->input('eventTime');         

        $event = $this->event->find($id);
        if($event) {

            if(!empty($ownerId)) {
                $event->ownerId = $ownerId;
            }
    
            if(!empty($eventType)) {
                $event->eventType = $eventType;
            }
    
            if(!empty($createdBy)) {
                $event->createdBy = $createdBy;
            }

            if(!empty($data)) {
                $event->data = $data;
            }

            if(!empty($eventTime)) {
                $event->eventTime = $eventTime;
            }
            
            $event->update();
    
            return response()->json("success", 202);
        } else {
            return response()->json('error', 400);
        }
       

    } 

    public function read_types() {
        $data = $this->event->types()->all();
        return response()->json($data);
    }

    public function show_type($id) {
        $data = $this->event->types()->find($id);
        if (! $data) return response()->json('error', 404);
        return response()->json($data);
    }

    public function create_type(Request $request) {

        $name = $request->input('name');
        $key = $request->input('key');
        $parentType = $request->input('parentType');
               
        if($name == '') {
            return response()->json('Por favor informe o nome do tipo de evento!');
        }

        if($key == '') {
            return response()->json('Por favor informe o valor chave do evento!');
        }

        if($parentType == '') {
            $parentType = 1;
        }


        $model = $this->event->types();
        $model->name = $name;
        $model->key = $key;
        $model->parentType = $parentType;
        
        $model->update();

        return response()->json("successs", 202);

    } 

    public function update_type(Request $request, $id) {

        $name = $request->input('name');
        $key = $request->input('key');
        $parentType = $request->input('parentType');        

        $type = $this->event->types()->find($id);
        if($type) {

            if(!empty($name)) {
                $type->name = $name;
            }
    
            if(!empty($key)) {
                $type->key = $key;
            }
    
            if(!empty($parentType)) {
                $type->parentType = $parentType;
            }
            
            $type->update();
    
            return response()->json("success", 202);
        } else {
            return response()->json('error', 400);
        }
       

    } 

    public function delete_type(EventType $id){
        try {
            $id->delete();

            return response()->json("success", 200);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json('error', 1012);
            }
            return response()->json('error', 1012);
        }
    }

    public function delete(Event $id){
        try {
            $id->delete();

            return response()->json("success", 200);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json('error', 1012);
            }
            return response()->json('error', 1012);
        }
    }
}