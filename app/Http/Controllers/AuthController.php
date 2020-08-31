<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'create', 'unauthorized']]);
    }

    public function unauthorized() {
        return response()->json(['error' => 'Não autozidado'], 401);
    }

    public function login(Request $request) {
        $array = ['error' => ''];

        $email = $request->input('email');
        $password = $request->input('password');

        if($email && $password) {
            $token = auth()->attempt([
                'email' => $email,
                'password' => $password
            ]);
    
            if(!$token) {
                return response()->json("E-mail e/ou Senha inválidos"); 
            }
    
            $array['token'] = $token;
            return $array;
        } 
        
        $array['error'] = 'Dados não informados!';
        return $array;
    }

    public function logout() {
        auth()->logout();
        return ['error' => ''];
    }

    public function refresh() {
        $token = auth()->refresh();
        return [
            'error' => '',
            'token' => $token
        ];
    }

    public function create(Request $request) {

        $name = $request->input('name');
        $email = $request->input('email');
        $password  = $request->input('password');
        $uf = $request->input('uf');
        $city = $request->input('city');
        $cellphone = $request->input('cellphone');
        $cpf = $request->input('cpf');
        $birthday = $request->input('birthday');
        $sexy = $request->input('sexy');

        $emailExists = User::where('email', $email)->count();

        if($name == '') {
            return response()->json('Por favor informe seu nome!');
        }
        if($email == '') {
            return response()->json('Por favor informe seu email!');
        }
        if($password == '') {
            return response()->json('Por favor informe sua senha!');
        }
        if($uf == '') {
            return response()->json('Por favor informe seu estado!');
        }
        if($city == '') {
            return response()->json('Por favor informe sua cidade!');
        }
        if($cellphone == '') {
            return response()->json('Por favor informe seu numéro de celular!');
        }
        if($cpf == '') {
            return response()->json('Por favor informe seu cpf!');
        }
        if($birthday == '') {
            return response()->json('Por favor informe seu data de nascimento!');
        }
        if($sexy == '') {
            return response()->json('Por favor informe seu Sexo!');
        }
        
        if ($emailExists === 0) {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $newUser = new User();
            $newUser->name = $name;
            $newUser->email = $email;
            $newUser->password = $hash;
            $newUser->uf = $uf;
            $newUser->city = $city;
            $newUser->cellphone = $cellphone;
            $newUser->cpf = $cpf;
            $newUser->birthday = $birthday;
            $newUser->sexy = $sexy;
            $newUser->save();

            $token = Auth()->attempt([
                'email' => $email,
                'password'=> $password
            ]);



            return response()->json("success", 202);


            if(!$token) {
                return response()->json("erro token invalid", 202);
            } 
                
            $array['token'] = $token;
                
            } else {
            return response()->json("Email já cadastrado", 202); 
        }
            
        return $array;
    }
}
