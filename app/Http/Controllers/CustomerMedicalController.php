<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\CustomerMedical;

class CustomerMedicalController extends Controller
{

    private $medical;
    private $loggedUser;

    public function __construct(CustomerMedical $medical){
        $this->middleware('auth:api', ['except' => ['login', 'unauthorized']]);
        $this->medical = $medical;

    }

    public function unauthorized() {
        return response()->json(['error' => 'Não autorizado'], 401);
    }

    public function index(){

        $data = $this->medical->all();
        return response()->json($data);
    }

    public function show($id){
        $customer_id = $id;
        $medical = $this->medical
            ->where('customer_id', '=', $customer_id)
            ->get();
        if (! $medical->count()) return response()->json('error', 404);

        return response()->json($medical);
    }

    public function update(Request $request, $id) {

        $customer_id = $request->input('customer_id');
        $referral_hospital = $request->input('referral_hospital');
        $observations = $request->input('observations');
        $emergency_contacts = $request->input('emergency_contacts');
        $doctor = $request->input('doctor');
        $bloodtype = $request->input('bloodtype');
        $allergic_reactions = $request->input('allergic_reactions');
        $medicines = $request->input('medicines');
        $additional_notes = $request->input('additional_notes');
        $insurance = $request->input('insurance');
        $insurance_number = $request->input('insurance_number');
    
        if($customer_id == '') {
            return response()->json('Por favor informe código do cliente!');
        }

        $medical = $this->medical->find($id);
        $emailExists = CustomerMedical::where('customer_id', $customer_id)->count();
        

        if($medical){

            if(!empty($customer_id)){
                $medical->customer_id = $customer_id;
            }
            if(!empty($referral_hospital)){
                $medical->referral_hospital = $referral_hospital;
            }
            if(!empty($observations)){
                $medical->observations = $observations;
            }
            if(!empty($emergency_contacts)){
                $medical->emergency_contacts = $emergency_contacts;
            }
            if(!empty($doctor)){
                $medical->doctor = $doctor;
            }
            if(!empty($bloodtype)){
                $medical->bloodtype = $bloodtype;
            }
            if(!empty($allergic_reactions)){
                $medical->allergic_reactions = $allergic_reactions;
            }
            if(!empty($medicines)){
                $medical->medicines = $medicines;
            }
            if(!empty($additional_notes)){
                $medical->additional_notes = $additional_notes;
            }
            if(!empty($insurance)){
                $medical->insurance = $insurance;
            }
            if(!empty($referral_hospital)){
                $medical->referral_hospital = $referral_hospital;
            }
            if(!empty($insurance_number)){
                $medical->insurance_number = $insurance_number;
            }
            if(!empty($emergency_contacts)){
                $medical->emergency_contacts = $emergency_contacts;
            }

            $medical->update();
                
            return response()->json('success', 202);

        } else {
            return response()->json('error', 400);
        }
    }

    public function create(Request $request) {

        $customer_id = $request->input('customer_id');
        $referral_hospital = $request->input('referral_hospital');
        $observations = $request->input('observations');
        $emergency_contacts = $request->input('emergency_contacts');
        $doctor = $request->input('doctor');
        $bloodtype = $request->input('bloodtype');
        $allergic_reactions = $request->input('allergic_reactions');
        $medicines = $request->input('medicines');
        $additional_notes = $request->input('additional_notes');
        $insurance = $request->input('insurance');
        $insurance_number = $request->input('insurance_number');
    
        if($customer_id == '') {
            return response()->json('Por favor informe código do cliente!');
        }

        $registered = CustomerMedical::where('customer_id', $customer_id)->count();
        
        
        if ($registered === 0) {
            $idUser = Auth::id();

            $medicalData = new CustomerMedical();
            $medicalData->customer_id = $customer_id;
            $medicalData->referral_hospital = $referral_hospital;
            $medicalData->observations = $observations;
            $medicalData->emergency_contacts = $emergency_contacts;
            $medicalData->doctor = json_encode($doctor);
            $medicalData->bloodtype = $bloodtype;
            $medicalData->allergic_reactions =$allergic_reactions;
            $medicalData->medicines = $medicines;
            $medicalData->additional_notes = $additional_notes;
            $medicalData->insurance = $insurance;
            $medicalData->insurance_number = $insurance_number;       
            $medicalData->save();

        return response()->json("success", 202);

        } else {
            return response()->json("Dados médicos já cadastrado", 202); 
        }
    }
    
    
    public function delete(CustomerMedical $id){
        try {
            $id->delete();

            return response()->json('success', 200);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json('error', 1012);
            }
            return response()->json('error', 1012);
        }
    }

}
