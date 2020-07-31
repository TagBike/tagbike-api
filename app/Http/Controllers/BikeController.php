<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Bike;
use App\User;

class BikeController extends Controller
{

    private $bike;
    private $user;

    public function __construct(Bike $bike, User $user){

        $this->bike = $bike;
        $this->user = $user;

    }

    public function index(){
        
        $data = ['data' => $this->bike->with('user')->paginate(10)];

        // fazer retorna so a bike do usuario logado
        return response()->json($data);
    }

    public function show($id){

        $bike = $this->bike->find($id);
        if (! $bike) return response()->json('Bicicleta não encontrado!', 404);

        $data = ['data' => $bike];
        return response()->json($data);
    }

    public function create(Request $request) {

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
        $newBike->id_user = $userId;
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

        return response()->json("Bike registered successfully", 202);

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

                return response()->json("Bike update successfully ", 202);

            } else {
                return response()->json('Error update', 400);
            }
    }    

    public function delete(Bike $id){
        try {
            $id->delete();

            return response()->json('Bicicleta Excluída com sucesso!', 200);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json('error', 1012);
            }
            return response()->json('Error ao realizar operação de exclusão', 1012);
        }
    }
}
