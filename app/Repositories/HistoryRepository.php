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
            $history->date = $data['date'];
            $history->time = $data['time'];
            $history->noted = $data['noted'];
            $history->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        }
        if($history->save()){
            return $history;
        }
        return false;
    }
   

}