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
                $array['error'] = 'E-mail e/ou Senha inválidos';
                return $array; 
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
        $array = ['error' => ''];

        $name = $request->input('name');
        $email = $request->input('email');
        $password  = $request->input('password');

        if ($name && $email && $password) {

            $emailExists = User::where('email', $email)->count();
            if ($emailExists === 0) {

                $hash = password_hash($password, PASSWORD_DEFAULT);

                $newUser = new User();
                $newUser->name = $name;
                $newUser->email = $email;
                $newUser->password = $hash;
                $newUser->save();

                $token = Auth()->attempt([
                    'email' => $email,
                    'password'=> $password
                ]);


                if(!$token) {
                    $array['error'] = 'Ocorreu um erro';
                    return $array;
                } 
                
                $array['token'] = $token;
                
            } else {
                $array['error'] = "Email já cadastrador";
                return $array;
            }
            
        } else {
            $array['error'] = "não enviou todos os campos";
            return $array;
        }

        return $array;
    }
}
