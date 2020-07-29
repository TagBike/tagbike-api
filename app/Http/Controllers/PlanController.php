<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plan;

class PlanController extends Controller
{

    private $plan;

    public function __construct(Plan $plan){

        $this->plan = $plan;

    }

    public function index(){

        $data = ['data' => $this->plan::paginate(10)];
        return response()->json($data);
    }

    public function show($id){

        $plan = $this->plan->find($id);
        if (! $plan) return response()->json(ApiError::errorMessage('Plano não encontrado!', 4040), 404);

        $data = ['data' => $plan];
        return response()->json($data);
    }

    public function update(Request $request, $id) {
        $name = $request->input('name');

        if($id && $name){
            $plan = $this->plan->find($id);

            if($plan){

                $plan->name = $name;
                $plan->save();

            } else {
                return response()->json('error');
            }
        }
    }   

    public function create(Request $request) {
        $array = ['error' => ''];

        $name = $request->input('name');

        if ($name) {
            $newPlan = new Plan();
            $newPlan->name = $name;
            $newPlan->save();
            return response()->json("sucess");
        } else {
            $array['error'] = "não enviou todos os campos";
            return $array;
        }
    } 

    public function delete(Plan $id){
        try {
            $id->delete();

            return response()->json(['data' => ['msg' => 'Plano Excluidor com sucesso!']], 200);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1012));
            }
            return response()->json(ApiError::errorMessage('Error ao realizar operação de exclusão', 1012));
        }
    }
}
