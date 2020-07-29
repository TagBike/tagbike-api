<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class UserController extends Controller
{
    private $user;
    private $loggedUser;

    public function __construct(User $user) {
        
        $this->user = $user;
        $this->loggedUser = auth()->user();
    }

    public function index(){

        $data = ['data' => $this->user::paginate(10)];
        return response()->json($data);
    }

    public function show($id){

        $user = $this->user->find($id);
        if (! $user) return response()->json(ApiError::errorMessage('Usuário não encontrado!', 4040), 404);

        $data = ['data' => $user];
        return response()->json($data);
    }

    public function update(Request $request) {
        $array = ['error' => ''];

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $password_confirm = $request->input('password_confirm');

        $user = User::find($this->loggedUser['id']);

        if($name) {
            $user->name = $name;
        }

        if($email) {
            if($email != $user->email) {
                $emailExists = User::where('email', $email)->count();
                if($emailExists === 0) {
                    $user->email = $email;
                } else {
                    $array['error'] = 'E-mail já existe no sistema';
                    return $array;
                }
            }
        }

        if($password && $password_confirm) {
            if($password === $password_confirm) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $user->password = $hash;
            } else {
                $array['error'] = "As senhas não conferem!";
                return $array;
            }
        }

        $user->save();

        return $array;
    }

    public function delete(User $id){
        try {
            $id->delete();

            return response()->json(['data' => ['msg' => 'Usuário Excluidor com sucesso!']], 200);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1012));
            }
            return response()->json(ApiError::errorMessage('Error ao realizar operação de exclusão', 1012));
        }
    }
}
