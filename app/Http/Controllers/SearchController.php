<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Plan;
use App\Bike;

class SearchController extends Controller
{
    private $loggedUser;

    public function __construct(){
        $this->middleware('auth:api');
        $this->loggedUser = Auth::user();
    }

    public function searchPlan(Request $request) {

        $data = $request->input('data');

        if($data){
            $planList =  Plan::where('name', 'LIKE', "%{$data}%")->get();
        } else {
            return response()->json('Digite alguma coisa para buscar.');
        }

        return response()->json($planList); 
    }

    public function searchBike(Request $request) {

        $data = $request->input('data');

        if($data){
            $listBike = Bike::where('serialNumber', 'like', "%{$data}%")
            ->orWhere('model', 'like', "%{$data}%")
            ->orWhere('brand', 'like', "%{$data}%")
            ->get();

        } else {
            return response()->json('Digite alguma coisa para buscar.');
            
        }
        return response()->json($listBike);
        
    }
}
