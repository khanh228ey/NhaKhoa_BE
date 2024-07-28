<?php
namespace App\Repositories;

use App\Http\Resources\AppointmentResource;
use App\Http\Resources\HistoryResource;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\ServiceResource;
use App\Models\Appointment;
use App\Models\History;
use App\Models\History_detail;
use App\Models\Invoices;
use App\Models\Service;
use Carbon\Carbon;
use Database\Seeders\InvoiceSeeder;
use Illuminate\Http\Request;

class StatisticsRepository{
    
        private function getRequestDate($startDate ,$endDate){
            if (empty($startDate) || empty($endDate)) {
                $date = Carbon::now('Asia/Ho_Chi_Minh');
                $startDate = $date->startOfMonth()->format('Y-m-d H:i:s');
                $endDate = $date->endOfMonth()->format('Y-m-d H:i:s');
            } else {
                $startDate = Carbon::createFromFormat('Y-m-d', $startDate, 'Asia/Ho_Chi_Minh')->startOfDay()->format('Y-m-d H:i:s');
                $endDate = Carbon::createFromFormat('Y-m-d', $endDate, 'Asia/Ho_Chi_Minh')->endOfDay()->format('Y-m-d H:i:s');
            }
            return  [$startDate,$endDate];
        }

        //Thống kê dịch vụ
        public function statisticService(Request $request){
            $startDate = $request->query('begin-date');
            $endDate= $request->query('end-date');
            [$startDate,$endDate] = $this->getRequestDate($startDate,$endDate);
            $turnover = Invoices::where('status',1)->whereBetween('created_at', [$startDate, $endDate])->sum('total_price');
            $quantityService = History_detail::with(['history'=> function($query) use($startDate,$endDate){
                    $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 1); 
            }])->sum('quantity');
            $service =  Service::with(['histories' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 1); 
            }])->first();
            $data = [
                ['title' => 'Tổng doanh thu:','content' => number_format($turnover)],
                ['title' => 'Tổng số bán ra:','content' => $quantityService],
                ['title'=> 'Dịch vụ nổi bật:','content' => $service->name],
            ];
            return $data;
        }


    public function getService(Request $request)
        {
            $startDate = $request->query('begin-date');
            $endDate= $request->query('end-date');
            [$startDate,$endDate] = $this->getRequestDate($startDate,$endDate);
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
                    'id' => $service->id,
                    'name' => $service->name,
                    'quantity_sold' => $totalQuantity,
                    'total_price' => $totalPrice,
                ];
            });

            $sortedResult = $result->sortByDesc('total_price')->values();
            return $sortedResult;
        }
    //Thống kê hóa đơn
    Public function statisticInvoice(Request $request){
        $startDate = $request->query('begin-date');
        $endDate= $request->query('end-date');
        [$startDate,$endDate] = $this->getRequestDate($startDate,$endDate);
        $turnover = Invoices::where('status',1)->whereBetween('created_at', [$startDate, $endDate])->sum('total_price');
        $turnoverMethod_0 =  Invoices::where('status',1)->where('method_payment',0)->whereBetween('created_at', [$startDate, $endDate])->sum('total_price');
        $turnoverMethod_1 =  Invoices::where('status',1)->where('method_payment',1)->whereBetween('created_at', [$startDate, $endDate])->sum('total_price');

        $data = [
            ['title' => 'Tổng doanh thu:','content' => $turnover,],
            ['title' => 'Thanh toán tiền mặt:','content' => $turnoverMethod_0,],
            ['title' => 'Thanh toán chuuyển khoản:','content' => $turnoverMethod_1,]
        ];
        return $data;
    }

    Public function getInvoice(Request $request){
        $startDate = $request->query('begin-date');
        $endDate= $request->query('end-date');
        [$startDate,$endDate] = $this->getRequestDate($startDate,$endDate);
        $invoice = Invoices::where('status',1)->whereBetween('created_at', [$startDate, $endDate])->orderBy('status','DESC')->orderBy('method_payment','ASC')->get();
        $invoice = InvoiceResource::collection($invoice);
        return $invoice;
    }


    //Thong ke lịch khám
    Public function getHistory(Request $request){
        $startDate = $request->query('begin-date');
        $endDate= $request->query('end-date');
        [$startDate,$endDate] = $this->getRequestDate($startDate,$endDate);
        $histories = History::where('status',1)->whereBetween('created_at', [$startDate, $endDate])
        ->with(['invoice' => function($query){
            $query->orderBy('total_price','DESC');
        }])->get();
            $result = HistoryResource::collection($histories);
            return $result;
    }

    // Public function statisticsHistory(Request $request){
    //     $startDate = $request->query('begin-date');
    //     $endDate= $request->query('end-date');
    //     [$startDate,$endDate] = $this->getRequestDate($startDate,$endDate);
    //     $history = History::whereBetween('created_at', [$startDate, $endDate]);
    //     $sumhistory = $history->count();
    //     $sumhistory = 
    // }


    //thong ke lich hen cua khach hang
    Public function statisticsAppointment(Request $request){
        $startDate = $request->query('begin-date');
        $endDate= $request->query('end-date');
        [$startDate,$endDate] = $this->getRequestDate($startDate,$endDate);
        $appointment = Appointment::whereBetween('created_at', [$startDate, $endDate]);
        $sumApoiment = $appointment->count();
        $sumApoimentDone = $appointment->where('status',1)->count();
        $sumApoimentCancel = $appointment->where('status',2)->count();
        $data = [
            ['title' => 'Tổng số lịch hẹn:','content' => $sumApoiment,],
            ['title' => 'Số lịch hẹn hoàn thành:','content' => $sumApoimentDone,],
            ['title' => 'Số lịch hẹn bị hủy:','content' => $sumApoimentCancel,]
        ];
        return $data;
    }

    Public function getAppointment(Request $request){
        $startDate = $request->query('begin-date');
        $endDate= $request->query('end-date');
        [$startDate,$endDate] = $this->getRequestDate($startDate,$endDate);
        $appointment = Appointment::whereBetween('created_at', [$startDate, $endDate])->orderBy('status','DESC')
        ->get();
            $result = AppointmentResource::collection($appointment);
            return $result;
    }
}

    



    

