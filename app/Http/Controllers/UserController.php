<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;

class UserController extends Controller
{
    private $user;
    private $loggedUser;

    public function __construct(User $user) {
        $this->middleware('auth:api');
        $this->loggedUser = Auth::user();
        $this->user = $user;
        
    }

    public function index(){

        $data = $this->user->all();
        return response()->json($data);
    }

    public function create(Request $request) {

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
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

            if(!$token) {
                return response()->json("erro token invalid", 202);
            } 
                
            $array['token'] = $token;

            return response()->json("success", 202);


        } else {
            return response()->json("Email já cadastrado", 202); 
        }
    }

    public function show($id){

        $user = $this->user->find($id);
        if (! $user) return response()->json('error', 404);

        $data = ['data' => $user];
        return response()->json($data);
    }

    public function update(Request $request, $id) {

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $uf = $request->input('uf');
        $city = $request->input('city');
        $cellphone = $request->input('cellphone');
        $cpf = $request->input('cpf');
        $birthday = $request->input('birthday');
        $sexy = $request->input('sexy');

        $user = $this->user->find($id);
        $emailExists = User::where('email', $email)->count();
        $hash = password_hash($password, PASSWORD_DEFAULT);

        if($user){

            if(!empty($name)){
                $user->name = $name;
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
                $user->password =$hash;
            }
            if(!empty($uf)){
                $user->uf = $uf;
            }
            if(!empty($city)){
                $user->city = $city;
            }
            if(!empty($cpf)){
                $user->cpf = $cpf;
            }
            if(!empty($cellphone)){
                $user->cellphone = $cellphone;
            }
            if(!empty($birthday)){
                $user->birthday = $birthday;
            }
            if(!empty($sexy)){
                $user->sexy = $sexy;
            }
        
            $user->update();
                
            return response()->json('success', 202);

        } else {
            return response()->json('error', 400);
        }
    }

    public function delete(User $id){
        try {
            $id->delete();

            return response()->json('success', 200);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json('error');
            }
            return response()->json('error');
        }
    }
}
