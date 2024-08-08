<?php
namespace App\Repositories\Manager;

use App\Models\History;
use App\Models\Service;
use Carbon\Carbon;
use App\Repositories\Manager\InvoiceRepository;
use Illuminate\Support\Facades\DB;

class HistoryRepository{

    public function addHistory($data){
        $history = new History();
        $history->customer_id = $data['customer_id'];
        $history->doctor_id = $data['doctor_id'];
        $history->noted = $data['note'];
        $history->date= $data['date'];
        $history->time= $data['time'];
        $history->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        if ($history->save()) {
            if(isset($data['services'])){
                $historyDetail = $this->dataHistoryDetail($data['services']);
                $history->services()->attach($historyDetail);
            }
            return $history;
        }
        return false;
    }
    
    public function updateHistory($data, $history) {
        $history->noted = $data['note'];
        $history->status = $data['status'];
        $history->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        if ($history->save()) {
            if (isset($data['services'])) {
                $historyDetail = $this->dataHistoryDetail($data['services']);
                $history->services()->sync($historyDetail);
            } else $history->services()->detach();
                    
            return $history;
        }
        return false;
    }
    
    public function dataHistoryDetail($service){
            $historyDetail = [];
            foreach ($service as $serviceData) {
                $serviceId = $serviceData['id'];
                $service = Service::find($serviceId);
                $price = $serviceData['price'] ?? $service->min_price;
                $quantity = $serviceData['quantity'] ?? 1;
                $service->quantity_sold += $quantity;
                $service->save();
                $historyDetail[$serviceId] = ['quantity' => $quantity, 'price' => $price];
            }
            return $historyDetail;
    }
}