<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
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

    public function reset() {
        $credentials = request()->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        $reset_password_status = Password::reset($credentials, function ($user, $password) {
            $user->password = $password;
            $user->save();
        });

        if ($reset_password_status == Password::INVALID_TOKEN) {
            return response()->json(["msg" => "Invalid token provided"], 400);
        }

        return response()->json(["msg" => "Password has been successfully changed"]);
    }
}
