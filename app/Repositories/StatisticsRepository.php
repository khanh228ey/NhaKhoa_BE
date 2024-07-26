<?php
namespace App\Repositories;

use App\Http\Resources\InvoiceResource;
use App\Http\Resources\ServiceResource;
use App\Models\History;
use App\Models\History_detail;
use App\Models\Invoices;
use App\Models\Service;
use Carbon\Carbon;
use Database\Seeders\InvoiceSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsRepository{

    
    public function statisticService(Request $request)
        {
            $startDate = $request->query('beginDate');
            $endDate= $request->query('endDate');
            if (empty($startDate) || empty($endDate)) {
                $date = Carbon::now('Asia/Ho_Chi_Minh');
                $startDate = $date->startOfMonth()->format('Y-m-d H:i:s');
                $endDate = $date->endOfMonth()->format('Y-m-d H:i:s');
            } else {
                $startDate = Carbon::createFromFormat('Y-m-d', $startDate, 'Asia/Ho_Chi_Minh')->startOfDay()->format('Y-m-d H:i:s');
                $endDate = Carbon::createFromFormat('Y-m-d', $endDate, 'Asia/Ho_Chi_Minh')->endDateOfDay()->format('Y-m-d H:i:s');
            }

            $services = Service::with(['histories' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 1); 
            }])->get();

            $result = $services->map(function ($service) {
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
                        'name' => $service->name,
                    ],
                    'quantity_sold' => $totalQuantity,
                    'total_price' => $totalPrice,
                ];
            });

            $sortedResult = $result->sortByDesc('total_price')->values();
            return $sortedResult;
        }

    Public function statisticInvoice(Request $request){
        $startDate = $request->query('beginDate');
        $endDate= $request->query('endDate');
        if (empty($startDate) || empty($endDate)) {
            $date = Carbon::now('Asia/Ho_Chi_Minh');
            $startDate = $date->startOfMonth()->format('Y-m-d H:i:s');
            $endDateDate = $date->endOfMonth()->format('Y-m-d H:i:s');
        } else {
            $startDate = Carbon::createFromFormat('Y-m-d', $startDate, 'Asia/Ho_Chi_Minh')->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromFormat('Y-m-d', $endDate, 'Asia/Ho_Chi_Minh')->endDateOfDay()->format('Y-m-d H:i:s');
        }
        $turnover = Invoices::where('status',1)->whereBetween('created_at', [$startDate, $endDate])->sum('total_price');
        $turnoverMethod_0 =  Invoices::where('status',1)->where('method_payment',0)->whereBetween('created_at', [$startDate, $endDate])->sum('total_price');
        $turnoverMethod_1 =  Invoices::where('status',1)->where('method_payment',1)->whereBetween('created_at', [$startDate, $endDate])->sum('total_price');

        $data = [
            [
                'title' => 'Tổng doanh thu',
                'total_price' => $turnover,
            ],
            [
                'title' => 'Tổng doanh thu thanh toán tiền mặt',
                'total_price' => $turnoverMethod_0,
            ],
            [
                'title' => 'Tổng doanh thu thanh toán chuuyển khoản',
                'total_price' => $turnoverMethod_1,
            ]
        ];
        return $data;
    }
            
    Public function getInvoice(Request $request){
        $startDate = $request->query('beginDate');
        $endDate= $request->query('endDate');
        if (empty($startDate) || empty($endDate)) {
            $date = Carbon::now('Asia/Ho_Chi_Minh');
            $startDate = $date->startOfMonth()->format('Y-m-d H:i:s');
            $endDateDate = $date->endOfMonth()->format('Y-m-d H:i:s');
        } else {
            $startDate = Carbon::createFromFormat('Y-m-d', $startDate, 'Asia/Ho_Chi_Minh')->startOfDay()->format('Y-m-d H:i:s');
            $endDateDate = Carbon::createFromFormat('Y-m-d', $endDate, 'Asia/Ho_Chi_Minh')->endDateOfDay()->format('Y-m-d H:i:s');
        }
        $invoice = Invoices::where('status',1)->whereBetween('created_at', [$startDate, $endDateDate])->orderBy('status','DESC')->orderBy('method_payment','ASC')->get();
        $invoice = InvoiceResource::collection($invoice);
        return $invoice;
    }
}

    



    

