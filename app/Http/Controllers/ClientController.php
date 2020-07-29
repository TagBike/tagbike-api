<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;

class ClientController extends Controller
{

    private $client;

    public function __construct(Client $client){

        $this->client = $client;

    }

    public function index(){

        $data = ['data' => $this->client::paginate(10)];
        return response()->json($data);
    }

    public function show($id){

        $client = $this->client->find($id);
        if (! $client) return response()->json(ApiError::errorMessage('Cliente não encontrado!', 4040), 404);

        $data = ['data' => $client];
        return response()->json($data);
    }

    public function update(Request $request, $id) {
        $name = $request->input('name');

        if($id && $name){
            $client = $this->client->find($id);

            if($client){

                $client->name = $name;
                $client->save();

            } else {
                return response()->json('error');
            }
        }
    }

    public function create(Request $request) {
        $array = ['error' => ''];

        $name = $request->input('name');

        if ($name) {
            $newClient = new Client();
            $newClient->name = $name;
            $newClient->save();
            return response()->json("sucess");
        } else {
            $array['error'] = "não enviou todos os campos";
            return response()->json($array);
        }
    }
    
    
    public function delete(Client $id){
        try {
            $id->delete();

            return response()->json(['data' => ['msg' => 'Cliente Excluidor com sucesso!']], 200);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1012));
            }
            return response()->json(ApiError::errorMessage('Error ao realizar operação de exclusão', 1012));
        }
    }
}
