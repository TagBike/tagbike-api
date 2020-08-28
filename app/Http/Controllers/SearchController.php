<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Plan;
use App\Bike;
use App\Client;
use App\Tag;
use App\User;

class SearchController extends Controller
{
    private $loggedUser;

    public function __construct(){
        $this->middleware('auth:api');
        $this->loggedUser = Auth::user();
    }

    public function unauthorized() {
        return response()->json(['error' => 'Não autozidado'], 401);
    }

    public function searchPlan(Request $request) {

        $data = $request->input('data');

        if($data){
            $planList =  Plan::where('name', 'LIKE', "%{$data}%")->get();
        } else {
            $data = Plan::all();
            return response()->json($data);
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
            $data = Bike::all();
            return response()->json($data);
            
        }
        return response()->json($listBike);
        
    }

    public function searchClient(Request $request) {

        $data = $request->input('data');

        if($data){
            $listClient = Client::where('cpf', 'like', "%{$data}%")
            ->orWhere('rg', 'like', "%{$data}%")
            ->orWhere('name', 'like', "%{$data}%")
            ->orWhere('email', 'like', "%{$data}%")
            ->get();

        } else {
            $data = Client::all();
            return response()->json($data);
            
        }
        return response()->json($listClient);
        
    }


    public function searchUSer(Request $request) {

        $data = $request->input('data');

        if($data){
            $listUSer = User::where('cpf', 'like', "%{$data}%")
            ->orWhere('name', 'like', "%{$data}%")
            ->orWhere('email', 'like', "%{$data}%")
            ->get();

        } else {
            $data = User::all();
            return response()->json($data);
            
        }
        return response()->json($listUSer);
        
    }

    public function searchTag(Request $request) {

        $data = $request->input('data');

        if($data){
            $listUSer = Tag::where('name', 'like', "%{$data}%")->get();

        } else {
            $data = Tag::all();
            return response()->json($data);
            
        }
        return response()->json($listUSer);
        
    }
}
