<?php

namespace App\Http\Controllers;
use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Repositories\StatisticsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    //
    protected $statisticsRepository;
    public function __construct( StatisticsRepository $statisticsRepository)
    {
        $this->statisticsRepository = $statisticsRepository; 
        // $this->middleware('check.role:3');
    }

    Public function getStatistics(Request $request){
        $startDate = $request->query('begin-date');
        $endDate = $request->query('end-date');
        if (empty($startDate) || empty($endDate)) {
            return JsonResponse::handle(400,"Chọn ngày bắt đầu và ngày kết thúc",null,400);
        }
        $turnover = $this->statisticsRepository->statisticInvoice($request);
        $invoice = $this->statisticsRepository->getInvoice($request);
        $data = [
            'turnover' => $turnover,
            'invoice' => $invoice,
        ];
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$data,200);
    }
    Public function getService(Request $request){
        $service = $this->statisticsRepository->statisticService($request);
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$service,200);
    }
    Public function getHistories(Request $request){
        $histories = $this->statisticsRepository->statisticsHistory($request);
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$histories,200);
    }
    Public function getAppointment(Request $request){
        $appointment = $this->statisticsRepository->statisticsAppointment($request);
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$appointment,200);
    }
}
