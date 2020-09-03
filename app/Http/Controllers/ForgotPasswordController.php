<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Carbon\Carbon;
use App\User;

class ForgotPasswordController extends Controller
{
    public function emailRequest(Request $request) {
        $name = $request->input('name');
        $email = $request->input('email');
        $token = Str::random(60);

        $idUser = User::where('email', $email)->get();
    
        $user = User::find($idUser[0]['id']);

        if($user) {
            $user->activation_token = $token;
            $user->status = 1;
            $user->update();

            Mail::send('auth.reset_password', ['name' => $name, 'token' =>$token], function ($message) use ($email, $name) {
                $message->from('suporte@tagbike.com.br', 'Tag Bike')
                    ->to($email, $name)
                    ->subject('Tag Bike - Solicitação de redefinição de senha');
            });
                return response()->json('E-mail enviado com sucesso');
        } else {
            return response()->json('error', 400);
        }
    }

    public function find($token)
    {
        $passwordReset = User::where('activation_token', $token)->first();

        if (!$passwordReset)
            return response()->json([
                'error' => true,
                'message' => 'Não encontramos um pedido de mudança com essa chave :(.'
            ], 404);

        if (Carbon::parse($passwordReset->updated_at)->addHours(6)->isPast()) {
            User::update($passwordReset[0]['status'] = 0);

            return response()->json([
                'error' => true,
                'message' => 'O tempo para mudar a senha já passou :(.'
            ], 404);
        }

        return response()->json(['error' => false, 'data' => $passwordReset]);
    }

    public function reset(Request $request)
    {
        $token = $request->token;
        $email = $request->email;
        $name = $request->name;
       
        $passwordReset = User::where('activation_token', $token)->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => 'Esse token não pode ser usado pra mudar senha!'
            ], 404);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'message' => "Não encontramos nenhum registro com esse e-mail :("
            ], 404);
        }

        if($user) { 
            $password = $request->input('password');
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $user->password = $hash;
            $user->status = 0;
            $user->activation_token = "";
            $user->update();

        Mail::send('auth.password_change', ['name' => $name], function ($message) use ($email, $name) {
            $message->from('suporte@tagbike.com.br', 'Tag Bike')
                ->to($email, $name)
                ->subject('Tag Bike - A senha foi mudada com sucesso');
            });
                return response()->json('E-mail enviado com sucesso');
                } else {
                    return response()->json('error', 400);
                }

        return response()->json([
            'error' => false,
            'message' => 'A senha senha foi mudada com sucesso :)'
        ], 200);
    }
}
