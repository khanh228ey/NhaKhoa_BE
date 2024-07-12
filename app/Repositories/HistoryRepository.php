<?php
namespace App\Repositories;

use App\Models\History;
use App\Models\Service;
use Carbon\Carbon;
use App\Repositories\InvoiceRepository;
use Illuminate\Support\Facades\DB;

class HistoryRepository{

    public function addHistory($data){
        $history = new History();
        $history->customer_id = $data['customer_id'];
        $history->doctor_id = $data['doctor_id'];
        $history->noted = $data['note'];
        $nowInHCM = Carbon::now('Asia/Ho_Chi_Minh');
        $date = $nowInHCM->toDateString(); 
        $time = $nowInHCM->format('H');
        $startHour = $time . ':00';
        $endHour = str_pad($time + 1, 2, '0', STR_PAD_LEFT) . ':00';
        $timeFrame = $startHour . ' - ' . $endHour;
        $history->date = $date;
        $history->time = $timeFrame;
        $history->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        if ($history->save()) {
            return $history;
        }
    
        return false;
    }
    
    public function updateHistory($data, $history) {
                    $history->noted = $data['note'];
                    $history->status = $data['status'];
                    $history->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
                    if ($history->save()) {
                        if (isset($data['services']) && is_array($data['services'])) {
                            $historyDetail = [];
                            foreach ($data['services'] as $serviceData) {
                                $serviceId = $serviceData['id'];
                                $service = Service::find($serviceId);
                                $price = $serviceData['price'] ?? $service->min_price;
                                $quantity = $serviceData['quantity'] ?? 1;
                                $service->quantity_sold += $quantity;
                                $service->save();
                                $historyDetail[$serviceId] = ['quantity' => $quantity, 'price' => $price];
                            }
                            $history->services()->sync($historyDetail);
                        } else {
                            $history->services()->detach();
                        }
                        return $history;
                    }
                    return false;
    }
    
    
}