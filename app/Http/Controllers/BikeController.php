<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Bike;

class BikeController extends Controller
{

    private $bike;

    public function __construct(Bike $bike){

        $this->bike = $bike;

    }

    public function index(){

        $data = ['data' => $this->bike::paginate(10)];
        return response()->json($data);
    }

    public function show($id){

        $bike = $this->bike->find($id);
        if (! $bike) return response()->json(ApiError::errorMessage('Bicicleta não encontrado!', 4040), 404);

        $data = ['data' => $bike];
        return response()->json($data);
    }

    public function create(Request $request) {
        $array = ['error' => ''];

        $name = $request->input('name');

        if ($name) {
            $newBike = new Bike();
            $newBike->name = $name;
            $newBike->save();
            return response()->json("sucess");
        } else {
            $array['error'] = "não enviou todos os campos";
            return $array;
        }
    } 

    public function update(Request $request, $id) {
        $name = $request->input('name');

        if($id && $name){
            $bike = $this->bike->find($id);

            if($bike){

                $bike->name = $name;
                $bike->save();

            } else {
                return response()->json('error');
            }
        }
    }    

    public function delete(Bike $id){
        try {
            $id->delete();

            return response()->json(['data' => ['msg' => 'Bicicleta Excluída com sucesso!']], 200);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1012));
            }
            return response()->json(ApiError::errorMessage('Error ao realizar operação de exclusão', 1012));
        }
    }
}
