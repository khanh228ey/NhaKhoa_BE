<?php
namespace App\Repositories\Client;
use App\Models\Service;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ServiceRepository{

    public function getServices(){
        $query = Service::where('status',1);
        return $query;
    }

    public function findById($id){
        try{
            $query = Service::where('status',1)->findOrFail($id);
            return $query;
        }catch(ModelNotFoundException $e){
            return false;
        }
    }


}