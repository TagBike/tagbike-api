<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Plan;

class PlanController extends Controller
{

    private $plan;
    private $loggedUser;

    public function __construct(Plan $plan){
        $this->middleware('auth:api');
        $this->loggedUser = Auth::user();
        $this->plan = $plan;

    }

    public function index(){

        $data = $this->plan->all();
        return response()->json($data);
    }

    public function show($id){

        $plan = $this->plan->find($id);
        if (! $plan) return response()->json('error!', 404);

        $data = ['data' => $plan];
        return response()->json($data);
    }

    public function update(Request $request, $id) {

        $plan = $this->plan->find($id);

        if($plan){
            $name = $request->input('name');
            $price = $request->input('price');
            $description = $request->input('description');

            if(!empty($name)){
                $plan->name = $name;
            }
            if(!empty($price)){
                $plan->price = $price;
            }
            if(!empty($description)){
                $plan->description = $description;
            }
            $plan->update();
            return response()->json('success', 202);

        } else {
            return response()->json('error', 412);
        }
    }   

    public function create(Request $request) {

        $name = $request->input('name');
        $price = $request->input('price');
        $description = $request->input('description');

        if($name == '') {
            return response()->json('Por favor informe seu nome!');
        }
        if($price == '') {
            return response()->json('Por favor informe o preço!');
        }

        $newPlan = new Plan();
        if ($newPlan) {
            $newPlan->name = $name;
            $newPlan->price = $price;
            $newPlan->description = $description;
            $newPlan->save();
            return response()->json("success", 202);
        } else {
            return response()->json("error", 412);
        }
        
        
    } 

    public function delete(Plan $id){
        try {
            $id->delete();

            return response()->json(['data' => ['msg' => 'success']], 200);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json('error', 412);
            }
            return response()->json('error', 412);
        }
    }

}
