<?php
namespace App\Repositories;

use App\Http\Resources\ServiceResource;
use App\Models\History;
use App\Models\History_detail;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsRepository{


  
    public function statisticService(Request $request)
    {
        // Lấy tham số ngày từ query
        $dateParam = $request->query('date');
        $date = empty($dateParam) ? Carbon::now('Asia/Ho_Chi_Minh') : Carbon::createFromFormat('Y-m-d', $dateParam, 'Asia/Ho_Chi_Minh');
        $startMonth = $date->startOfMonth()->format('Y-m-d H:i:s');
        $endMonth = $date->endOfMonth()->format('Y-m-d H:i:s');
    
        $services = Service::with(['histories' => function ($query) use ($startMonth, $endMonth) {
            $query->whereBetween('created_at', [$startMonth, $endMonth])
                  ->where('status', 1); 
        }])->get();
        $result = $services->map(function ($service) use ($startMonth, $endMonth) {
            // Tính tổng số lượng và giá từ các chi tiết lịch sử
            $totalQuantity = $service->histories->sum(function ($history) {
                return $history->historyDetails->sum('quantity');
            });
    
            $totalPrice = $service->histories->sum(function ($history) {
                return $history->historyDetails->sum(function ($detail) {
                    return $detail->quantity * $detail->price;
                });
            });
            return [
                'service' => [
                   'id' => $service->id,
                    'name'=> $service->name,
                ],
                'quantity' => $totalQuantity,
                'price' => $totalPrice,
            ];
        });
    
        return $result;
    }
    
    
    
    
}

    



    

