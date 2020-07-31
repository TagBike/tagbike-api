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
        $cpf = $request->input('cpf');
        $email = $request->input('email');
        $password = $request->input('password');
        $cep = $request->input('cep');
        $uf = $request->input('uf');
        $city = $request->input('city');
        $neighborhood = $request->input('neighborhood');
        $address = $request->input('address');
        $number = $request->input('number');
        $complement = $request->input('complement');
        $phone = $request->input('phone');
        $cellphone = $request->input('cellphone');
        $birthday = $request->input('birthday');

        $client = $this->client->find($id);
        $emailExists = Client::where('email', $email)->count();
        

        if($client){

            if(!empty($name)){
                $client->name = $name;
            }
            if(!empty($cpf)){
                $client->cpf = $cpf;
            }
            if(!empty($email)){
                if($email != $user->email){
                    if ($emailExists === 0) {
                        $user->email = $email;
                    } else {
                        return response()->json("Email já cadastrado", 202); 
                    }
                } 
            }
            if(!empty($password)){
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $client->password =$hash;
            }
            if(!empty($cep)){
                $client->cep = $cep;
            }
            if(!empty($uf)){
                $client->uf = $uf;
            }
            if(!empty($city)){
                $client->city = $city;
            }
            if(!empty($neighborhood)){
                $client->neighborhood = $neighborhood;
            }
            if(!empty($address)){
                $client->address = $address;
            }
            if(!empty($phone)){
                $client->phone = $phone;
            }
            $client->complement = $complement;
            $client->cellphone = $cellphone;
            $client->birthday = $birthday;
            $client->update();
                
            return response()->json('Client update successfully!', 202);

        } else {
            return response()->json('Error update Client!', 400);
        }
    }

    public function create(Request $request) {

        $name = $request->input('name');
        $cpf = $request->input('cpf');
        $email = $request->input('email');
        $password = $request->input('password');
        $cep = $request->input('cep');
        $uf = $request->input('uf');
        $city = $request->input('city');
        $neighborhood = $request->input('neighborhood');
        $address = $request->input('address');
        $number = $request->input('number');
        $complement = $request->input('complement');
        $phone = $request->input('phone');
        $cellphone = $request->input('cellphone');
        $birthday = $request->input('birthday');

        $emailExists = Client::where('email', $email)->count();

        if($name == '') {
            return response()->json('Por favor informe seu nome!');
        }
        if($cpf == '') {
            return response()->json('Por favor informe seu cpf!');
        }
        if($email == '') {
            return response()->json('Por favor informe seu email!');
        }
        if($password == '') {
            return response()->json('Por favor informe sua senha!');
        }
        if($cep == '') {
            return response()->json('Por favor informe sseu cep!');
        }
        if($uf == '') {
            return response()->json('Por favor informe seu estado!');
        }
        if($city == '') {
            return response()->json('Por favor informe sua cidade!');
        }
        if($neighborhood == '') {
            return response()->json('Por favor informe seu bairro!');
        }
        if($address == '') {
            return response()->json('Por favor informe seu Endereço!');
        }
        if($number == '') {
            return response()->json('Por favor informe o número!');
        }
        if($phone == '') {
            return response()->json('Por favor informe o número de telefone!');
        }
        
        if ($emailExists === 0) {

            $hash = password_hash($password, PASSWORD_DEFAULT);

        $newClient = new Client();
        $newClient->name = $name;
        $newClient->cpf = $cpf;
        $newClient->email = $email;
        $newClient->password =$hash;
        $newClient->cep = $cep;
        $newClient->uf = $uf;
        $newClient->city = $city;
        $newClient->neighborhood = $neighborhood;
        $newClient->address = $address;
        $newClient->number = $number;
        $newClient->complement = $complement;
        $newClient->phone = $phone;
        $newClient->cellphone = $cellphone;
        $newClient->birthday = $birthday;
        $newClient->save();

        return response()->json("Client registered successfully", 202);

        } else {
            return response()->json("Email já cadastrado", 202); 
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
