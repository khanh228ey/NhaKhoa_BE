<?php
namespace App\Repositories;

use App\Http\Resources\ServiceResource;
use App\Models\History;
use App\Models\History_detail;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticsRepository{


    // public function statisticService(){
    //     $date = Carbon::now('Asia/Ho_Chi_Minh');
    //     $startMonth = $date->startOfMonth()->format('Y-m-d H:i:s');
    //     $endMonth = $date->endOfMonth()->format('Y-m-d H:i:s'); 
    //     $service = Service::with('history');
    //     // $service = 
    // }
    // public function statisticService()
    // {
    //     $date = Carbon::now('Asia/Ho_Chi_Minh');
    //     $startMonth = $date->startOfMonth()->format('Y-m-d H:i:s');
    //     $endMonth = $date->endOfMonth()->format('Y-m-d H:i:s');

    //     $services = History_detail::with('service','history')

    //     $statistics = $services->map(function($service) {
    //         $totalQuantity = $service->histories->sum('quantity');
    //         $totalPrice = $service->histories->sum('price');
    //             return [
    //             'name' => $service->name,
    //             'quantity' => $totalQuantity,
    //             'price' => $totalPrice
    //         ];
    //     });

    //     return $statistics;
    // }
        public function statisticService(Request $request)
        {   
                $dateParam = $request->query('date');
                if(empty($dateParam)){ 
                    $date= Carbon::now('Asia/Ho_Chi_Minh');
                }else{
                    $date=Carbon::createFromFormat('Y-m-d', $dateParam, 'Asia/Ho_Chi_Minh');
                }
                $startMonth = $date->startOfMonth()->format('Y-m-d H:i:s');
                $endMonth = $date->endOfMonth()->format('Y-m-d H:i:s');
                $service = History_detail::with(['service','history' => function ($query)use($startMonth,$endMonth) {
                        $query->whereBetween('created_at',[$startMonth, $endMonth])
                        ->where('status','1');
                }])
                ->get()
                ->groupBy('service_id')
                ->map(function ($details, $service) {
                    $service = $details->first()->service;
                    $service = new ServiceResource($service);
                return [
                    'service' => $service,
                    'quantity' => $details->sum('quantity'),
                    'price' => $details->sum(function ($detail) {
                        return $detail->quantity * $detail->price;
                    })
                ];
            })
            ->values();
            return  $service;
         
        }


}
    

