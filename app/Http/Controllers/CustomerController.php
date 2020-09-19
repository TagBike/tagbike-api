<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Customer;
use App\User;
use App\Event;

class CustomerController extends Controller
{

    private $customer;
    private $event;
    private $user;
    private $loggedUser;

    public function __construct(Customer $customer){
        $this->middleware('auth:api', ['except' => ['login', 'unauthorized']]);
        $this->customer = $customer;
        $this->event = new Event();
        $this->user = new User();
    }

    public function unauthorized() {
        return response()->json(['error' => 'Não autorizado'], 401);
    }

    public function index(){

        $data = $this->customer->all();
        return response()->json($data);
    }

    public function show($id){

        $customer = $this->customer->find($id);
        if (! $customer) return response()->json('error', 404);

        $data = ['data' => $customer];
        return response()->json($data);
    }

    public function update(Request $request, $id) {

        $name = $request->input('name');
        $cpf = $request->input('cpf');
        $rg = $request->input('rg');
        $email = $request->input('email');
        $password = $request->input('password');
        $gender = $request->input('gender');
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

        $customer = $this->customer->find($id);
        $emailExists = Customer::where('email', $email)->count();
        

        if($customer){

            if(!empty($name)){
                $customer->name = $name;
            }
            if(!empty($cpf)){
                $customer->cpf = $cpf;
            }
            if(!empty($rg)){
                $customer->rg = $rg;
            }
            if(!empty($email)){
                if($email != $customer->email){
                    if ($emailExists === 0) {
                        $customer->email = $email;
                    } else {
                        return response()->json("Email já cadastrado", 202); 
                    }
                } 
            }
            if(!empty($password)){
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $customer->password =$hash;
            }
            if(!empty($gender)){
                $customer->gender = $gender;
            }
            if(!empty($cep)){
                $customer->cep = $cep;
            }
            if(!empty($uf)){
                $customer->uf = $uf;
            }
            if(!empty($city)){
                $customer->city = $city;
            }
            if(!empty($neighborhood)){
                $customer->neighborhood = $neighborhood;
            }
            if(!empty($address)){
                $customer->address = $address;
            }
            if(!empty($phone)){
                $customer->phone = $phone;
            }        

            $customer->complement = $complement;
            $customer->cellphone = $cellphone;
            $customer->birthday = $birthday;
            $customer->update();
            $eventData = [
                'userId' => Auth::id(),
                'customerId' => $customer->id
            ];

            Event::register([
                'ownerId' => $customer->id,
                'eventType' => 'event.action.user.customer.account.update',
                'createdBy' => Auth::id(),
                'data' => json_encode($eventData)
                ]);

            return response()->json('success', 202);

        } else {
            return response()->json('error', 400);
        }
    }

    public function create(Request $request) {

        $name = $request->input('name');
        $cpf = $request->input('cpf');
        $rg = $request->input('rg');
        $email = $request->input('email');
        $password = $request->input('password');
        $gender = $request->input('gender');
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

        $emailExists = Customer::where('email', $email)->count();

        if($name == '') {
            return response()->json('Por favor informe seu nome!');
        }
        if($cpf == '') {
            return response()->json('Por favor informe seu cpf!');
        }
        if($rg == '') {
            return response()->json('Por favor informe seu rg!');
        }
        if($email == '') {
            return response()->json('Por favor informe seu email!');
        }
        if($gender == '') {
            return response()->json('Por favor informe o sexo!');
        }
        if($password == '') {
            return response()->json('Por favor informe sua senha!');
        }
        if($cep == '') {
            return response()->json('Por favor informe seu cep!');
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
            $idUser = Auth::id();

        $newCustomer = new Customer();
        $newCustomer->id_user = $idUser;
        $newCustomer->name = $name;
        $newCustomer->cpf = $cpf;
        $newCustomer->rg = $rg;
        $newCustomer->email = $email;
        $newCustomer->password =$hash;
        $newCustomer->gender =$gender;
        $newCustomer->cep = $cep;
        $newCustomer->uf = $uf;
        $newCustomer->city = $city;
        $newCustomer->neighborhood = $neighborhood;
        $newCustomer->address = $address;
        $newCustomer->number = $number;
        $newCustomer->complement = $complement;
        $newCustomer->phone = $phone;
        $newCustomer->cellphone = $cellphone;
        $newCustomer->birthday = $birthday;
        $newCustomer->save();
        

        Event::register([
            'ownerId' => $newCustomer->id,
            'eventType' => 'event.action.user.customer.account.new',
            'createdBy' => Auth::id(),
            'data' => json_encode([
                'userId' => Auth::id(),
                'customerId' => $newCustomer->id
            ])
            ]);


        return response()->json("success", 202);

        } else {
            return response()->json("Email já cadastrado", 202); 
        }
    }
    
    
    public function delete(Customer $id){
        try {
            $id->delete();

            Event::register([
                'ownerId' => $id,
                'eventType' => 'event.action.user.customer.account.delete',
                'createdBy' => Auth::id(),
                'data' => json_encode([
                    'userId' => Auth::id(),
                    'customerId' => $id
                ])
            ]);

            return response()->json('success', 200);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json('error', 1012);
            }
            return response()->json('error', 1012);
        }
    }


    public function bikes($id){
        $bikes = $this->customer->bikes($id)->get();
        if (! $bikes) return response()->json('error', 404);
        return response()->json($bikes);
    }

    public function events($id){
        $data = $this->customer->events($id)
            ->select(
                'events.*',
                'event_types.name as eventName', 
                'event_types.key as eventKey',
                'customers.name as customerName',
                'data->userId as userId',
                'data->bikeId as bikeId',
                'users.name as userName',
            )
            ->join('event_types', 'events.eventType', '=', 'event_types.id')
            ->leftJoin('customers', 'events.ownerId', '=', 'customers.id')
            ->leftJoin('users', 'data->userId', '=', 'users.id')
            ->leftJoin('bikes', 'data->bikeId', '=', 'bikes.id')
            
            ->where('events.ownerId', '=', $id)
            ->get();

        
        if (! $data) return response()->json('error', 404);
        return response()->json($data);
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
}
