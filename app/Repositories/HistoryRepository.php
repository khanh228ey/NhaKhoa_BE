<?php
namespace App\Repositories;

use App\Models\History;
use Carbon\Carbon;

class HistoryRepository{

    public function addHistory($data){
        $history = new History();
        $history->customer_id = $data['customer_id'];
        $history->doctor_id = $data['doctor_id'];
        if(count($data) > 2){
            $date = Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d');
            $history->date = $date;
            $history->time = $data['time'];
            $history->noted = $data['noted'];
            $history->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        }
        if ($history->save()) {
            // Kiểm tra nếu tồn tại dữ liệu service và quantity
            if (isset($data['service']) && isset($data['quantity'])&& isset($data['price'])) {
                // Duyệt qua cả hai mảng service và quantity
                $count = min(count($data['service']), count($data['quantity'])); 
                for ($i = 0; $i < $count; $i++) {
                    $serviceId = $data['service'][$i];
                    $quantity = $data['quantity'][$i];
                    $price = $data['price'][$i];
                    // Thêm vào bảng pivot giữa History và Service
                    $history->services()->attach($serviceId, ['quantity' => $quantity, 'price' => $price]);
            
                }
            }
    
            return $history;
        }
    
        return false;
    }

    public function updateHistory($data){
        $id = $data['id'];
       
        $history = History::find($id);
        if (count($data) > 2) {
            $date = Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d');
            $history->date = $date;
            $history->time = $data['time'];
            $history->noted = $data['noted'];
            $history->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        }
        if($history->save()){
                if (isset($data['service']) && isset($data['quantity'])) {
                    $count = min(count($data['service']), count($data['quantity']));
                    
                    $syncData = [];
                    for ($i = 0; $i < $count; $i++) {
                        $serviceId = $data['service'][$i];
                        $quantity = $data['quantity'][$i];
                        $syncData[$serviceId] = ['quantity' => $quantity];
                    }
                    
                    $history->services()->sync($syncData);
                } else {
                    $history->services()->detach();
                }
                return $history;
            }
            return false;
      
    }   
   

}