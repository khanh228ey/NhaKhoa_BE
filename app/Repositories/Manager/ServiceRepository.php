<?php
namespace App\Repositories\Manager;

use App\Models\History;
use App\Models\Service;
use App\Models\ServiceTranslation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ServiceRepository{

    public function addService($data){
        $service = new Service();
        $service->name = $data['name'];
        $service->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $service->description = $data['description'];
        $service->status= $data['status'];
        $service->image = $data['image'];
        $service->servicegory_id = $data['servicegory_id'];
        $service->max_price = $data['max_price'];
        $service->min_price = $data['min_price'];
        $service->unit = $data['unit'];
        if(isset($data['quantity_sold'])) {
            $service->quantity_sold = $data['quantity_sold'];
        }else{
            $service->quantity_sold =0;
        }
        if($service->save()){
            return $service;
        }
        return false;
    }

    Public function updateService(Request $request,$service){
        $data = $request->only(['name', 'status', 'min_price', 'max_price', 'image','unit', 'servicegory_id','quantity_sold']);
            $data['updated_at'] = Carbon::now('Asia/Ho_Chi_Minh');
            if (isset($request->description) && $request->description !== '') {
                $data['description'] = $request->description;
            }
            $service->fill($data);
        if($service->save()){
            return $service;
        }
        return false;
    }


    public function getServiceTrans($id){
        try {
            $serviceTrans = ServiceTranslation::where('service_id', $id)->firstOrFail();
            return $serviceTrans;
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }

    public function addserviceTrans($id,$data){
        $service = new ServiceTranslation();
        $service->name = $data['name'];
        $service->description = $data['description'];
        $service->unit = $data['unit'];
        $service->service_id = $id;
        if($service->save()){
            return $service;
        }
        return false;
    }

    public function updateserviceTrans($service,$data){
        $service->name = $data['name'];
        if (isset($date['description']) && $data['description'] !== '') {
            $service->description = $data['description'];
        }
        $service->unit = $data['unit'];
        $service->save();
        return $service;
    }
    
}