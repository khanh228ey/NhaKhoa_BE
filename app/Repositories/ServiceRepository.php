<?php
namespace App\Repositories;

use App\Models\History;
use App\Models\Service;
use Carbon\Carbon;

class ServiceRepository{

    public function addService($data){
        $service = new Service();
        $service->name = $data['name'];
        $service->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $service->description = $data['description'];
        $service->status= $data['status'];
        $service->image = $data['image'];
        $service->category_id = $data['category_id'];
        $service->max_price = $data['max_price'];
        $service->min_price = $data['min_price'];
        $service->unit = $data['unit'];
        $service->quantity_sold = 0;
        if($service->save()){
            return $service;
        }
        return false;
    }
    Public function updateService($data){
        $service = Service::find($data['id']);
        $service->name = $data['name'];
        $service->description = $data['description'];
        $service->status= $data['status'];
        $service->image = $data['image'];
        $service->category_id = $data['category_id'];
        $service->max_price = $data['max_price'];
        $service->min_price = $data['min_price'];
        $service->unit = $data['unit'];
        $service->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        if($service->save()){
            return $service;
        }
        return false;
    }
    
}