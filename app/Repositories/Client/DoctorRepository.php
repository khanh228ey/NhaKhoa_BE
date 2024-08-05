<?php
namespace App\Repositories\Client;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DoctorRepository{
    public function getDoctors(){
        $doctor = User::where('role_id',3)->where('status',1)->get();
        return $doctor;
    }

    Public function findById($id){
        try{
            $doctor = User::where('role_id',3)->where('status',1)->findOrFail($id);
            return $doctor;
        }catch(ModelNotFoundException $e){
            return false;
        }
    }
}