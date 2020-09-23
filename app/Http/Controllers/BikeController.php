<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Bike;
use App\User;
use App\Customer;
use App\Event;
use App\Tag;

class BikeController extends Controller
{

    private $bike;
    private $user;
    private $loggedUser;

    public function __construct(Bike $bike, User $user, Customer $customer){
        $this->middleware('auth:api');
        $this->loggedUser = Auth::user();
        $this->bike = $bike;
        $this->user = $user;
        $this->customer = $customer;

    }

    public function index(){
        
        $data = $this->bike->get();
        return response()->json($data);
    }

    public function show($id){

        $bike = $this->bike
            ->select('bikes.*', 'tags.hash as hash')
            ->leftJoin('tags', 'tags.id_bike', '=', 'bikes.id')
            ->find($id);
        if (! $bike) return response()->json('error', 404);

        $data = $bike;
        return response()->json($data);
    }

    public function create(Request $request) {

        $serialNumber = $request->input('serialNumber');
        $customer_id = $request->input('customer_id');
        $biketype = $request->input('biketype');
        $brand = $request->input('brand');
        $model = $request->input('model');
        $color = $request->input('color');
        $photoBike = $request->input('photoBike');
        $forwardExchange = $request->input('forwardExchange');
        $rearDerailleur = $request->input('rearDerailleur');
        $brakeType = $request->input('brakeType');
        $typeSuspension = $request->input('typeSuspension');
        $wheelType = $request->input('wheelType');
        $forkType = $request->input('forkType');
        $frametype = $request->input('frametype');

        $serialNumberExists = $this->bike->where('serialNumber', $serialNumber)->count();

        if($serialNumber == '') {
            return response()->json('Por favor informe o número de série!');
        }
        if($biketype == '') {
            return response()->json('Por favor informe o tipo da bike!');
        }
        if($brand == '') {
            return response()->json('Por favor informe a marca!');
        }
        if($model == '') {
            return response()->json('Por favor informe o modelo!');
        }
        if($color == '') {
            return response()->json('Por favor informe a cor!');
        }
        
        if ($serialNumberExists === 0) {

            $userId = Auth::user()->id;

            $newBike = new Bike();
            $newBike->serialNumber = $serialNumber;
            $newBike->customer_id = $customer_id;
            $newBike->biketype = $biketype;
            $newBike->brand = $brand;
            $newBike->model = $model;
            $newBike->color = $color;
            $newBike->photoBike = $photoBike;
            $newBike->forwardExchange = $forwardExchange;
            $newBike->rearDerailleur = $rearDerailleur;
            $newBike->brakeType = $brakeType;
            $newBike->typeSuspension = $typeSuspension;
            $newBike->wheelType = $wheelType;
            $newBike->forkType = $forkType;
            $newBike->frametype = $frametype;
            $newBike->save();

            Tag::register($newBike->id);

            Event::register([
                'ownerId' => $customer_id,
                'eventType' => 'event.action.user.customer.bike.add',
                'createdBy' => Auth::id(),
                'data' => json_encode([
                    'userId' => Auth::id(),
                    'customerId' => $customer_id,
                    'bikeId' => $newBike->id
                ])
            ]);



            return response()->json("success", 202);

        } else {
            return response()->json("Número de série já cadastrado", 400); 
        }
    } 

    public function update(Request $request, $id) {
        
        $serialNumber = $request->input('serialNumber');
        $biketype = $request->input('biketype');
        $brand = $request->input('brand');
        $model = $request->input('model');
        $color = $request->input('color');
        $photoBike = $request->input('photoBike');
        $forwardExchange = $request->input('forwardExchange');
        $rearDerailleur = $request->input('rearDerailleur');
        $brakeType = $request->input('brakeType');
        $typeSuspension = $request->input('typeSuspension');
        $wheelType = $request->input('wheelType');
        $forkType = $request->input('forkType');
        $frametype = $request->input('frametype');

        $bike = $this->bike->find($id);

        if($bike){ 

            if(!empty($serialNumber)){
                $bike->serialNumber = $serialNumber;
            }
            if(!empty($biketype)){
                $bike->biketype = $biketype;
            }
            if(!empty($brand)){
                $bike->brand = $brand;
            }
            if(!empty($model)){
                $bike->model = $model;
            }
            if(!empty($color)){
                $bike->color = $color;
            }
            
            $bike->photoBike = $photoBike;
            $bike->forwardExchange = $forwardExchange;
            $bike->rearDerailleur = $rearDerailleur;
            $bike->brakeType = $brakeType;
            $bike->typeSuspension = $typeSuspension;
            $bike->wheelType = $wheelType;
            $bike->forkType = $forkType;
            $bike->frametype = $frametype;
            $bike->update();

            Event::register([
                'ownerId' => $bike->customer_id,
                'eventType' => 'event.action.user.customer.bike.update',
                'createdBy' => Auth::id(),
                'data' => json_encode([
                    'userId' => Auth::id(),
                    'customerId' => $bike->customer_id,
                    'bikeId' => $bike->id
                ])
            ]);

            return response()->json("success", 202);

        } else {
            return response()->json('error', 400);
        }
    }    

    public function delete(Request $response, $id){
        try {
            $bike = $this->bike->find($id);
            $data = $bike->first();
            
            Event::register([
                'ownerId' => $data->customer_id,
                'eventType' => 'event.action.user.customer.bike.archive',
                'createdBy' => Auth::id(),
                'data' => json_encode([
                    'userId' => Auth::id(),
                    'customerId' => $data->customer_id,
                    'bikeId' => $id
                ])
            ]);

            $bike->delete();

            return response()->json('success', 200);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json('error', 1012);
            }
            return response()->json('error', 1012);
        }
    }

    public function events($id){
        $data = $this->bike->events($id)
            ->select(
                'events.*',
                'event_types.name as eventName', 
                'event_types.key as eventKey',
                'customers.name as customerName',
                'data->userId as userId',
                'data->bikeId as bikeId',
                'users.name as userName',
            )
            ->join('event_types', 'events.eventType', '=', 'event_types.id')
            ->leftJoin('customers', 'events.ownerId', '=', 'customers.id')
            ->leftJoin('users', 'data->userId', '=', 'users.id')
            ->join('bikes', 'data->bikeId', '=', 'bikes.id')
            ->get();

        
        if (! $data) return response()->json('error', 404);
        return response()->json($data);
    }
}
