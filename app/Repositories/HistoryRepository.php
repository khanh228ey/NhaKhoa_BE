<?php
namespace App\Repositories;

use App\Models\History;
use App\Models\Service;
use Carbon\Carbon;

class HistoryRepository{

    public function addHistory($data){
        $history = new History();
        $history->customer_id = $data['customer_id'];
        $history->doctor_id = $data['doctor_id'];
        $history->noted = $data['note'];
        $nowInHCM = Carbon::now('Asia/Ho_Chi_Minh');
        $date = $nowInHCM->toDateString(); // Định dạng ngày: 'YYYY-MM-DD'
        $time = $nowInHCM->format('H');
        $startHour = $time . ':00';
        $endHour = str_pad($time + 1, 2, '0', STR_PAD_LEFT) . ':00';
        $timeFrame = $startHour . ' - ' . $endHour;
        $history->date = $date;
        $history->time = $timeFrame;
        $history->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        if ($history->save()) {
            if (isset($data['services']) && is_array($data['services'])) {
                foreach ($data['services'] as $serviceData) {
                    $serviceId = $serviceData['id'];
                    $quantity = $serviceData['quantity'];
                    $price = $serviceData['price'];
                    $history->services()->attach($serviceId, ['quantity' => $quantity, 'price' => $price]);
                    $service = Service::find($serviceId);
                    if ($service) {
                        $service->quantity_sold += $quantity;
                        $service->save();
                    }
                }
            }
            return $history;
        }
    
        return false;
    }
    

    public function updateHistory($data, $history) {
        if ($data['status'] == 1) {
            $nowInHCM = Carbon::now('Asia/Ho_Chi_Minh');
            $date = $nowInHCM->toDateString(); // Định dạng ngày: 'YYYY-MM-DD'
            $time = $nowInHCM->format('H:i'); // Định dạng giờ: 'HH:mm:ss'
            $history->date = $date;
            $history->time = $time;
        }
        $history->noted = $data['note'];
        $history->status = $data['status'];
        $history->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        if ($history->save()) {
            if (isset($data['services']) && is_array($data['services'])) {
                $syncData = [];
                foreach ($data['services'] as $serviceData) {
                    $serviceId = $serviceData['id'];
                    $quantity = $serviceData['quantity'];
                    $price = $serviceData['price'];
                    $syncData[$serviceId] = ['quantity' => $quantity, 'price' => $price];
                }
                $history->services()->sync($syncData);
                foreach ($data['services'] as $serviceData) {
                    $serviceId = $serviceData['id'];
                    $quantity = $serviceData['quantity'];
                    $service = Service::find($serviceId);
                    if ($service) {
                        $service->quantity_sold += $quantity;
                        $service->save();
                    }
                }
            } else {
                $history->services()->detach();
            }
            return $history;
        }
    
        return false;
    }
    
}