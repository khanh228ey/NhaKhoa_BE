<?php

namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;
use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Repositories\Manager\StatisticsRepository;
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
        $turnover = $this->statisticsRepository->statisticInvoice($request);
        $invoice = $this->statisticsRepository->getInvoice($request);
        $data = [
            'turnover' => $turnover,
            'invoice' => $invoice,
        ];
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$data,200);
    }
    Public function getService(Request $request){
        $service = $this->statisticsRepository->getService($request);
        $statisticsService = $this->statisticsRepository->statisticService($request);
        $data = [
            'turnover' => $statisticsService,
            'service' => $service,
        ];
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$data,200);
    }
    Public function getHistories(Request $request){
        $statisticsHistory = $this->statisticsRepository->statisticsHistory($request);
        $history = $this->statisticsRepository->getHistory($request);
        $data = [
            'turnover' => $statisticsHistory,
            'history' => $history,
        ];
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$data,200);
    }
    Public function getAppointment(Request $request){
        $appointment = $this->statisticsRepository->getAppointment($request);
        $statisticsAppointment = $this->statisticsRepository->statisticsAppointment($request);
        $data = [
            'turnover' => $statisticsAppointment,
            'appointment' => $appointment,
        ];
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$data,200);
    }
}
