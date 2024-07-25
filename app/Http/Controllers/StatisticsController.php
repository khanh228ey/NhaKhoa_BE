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

    Public function getServiceStatistics(Request $request){
        $service = $this->statisticsRepository->statisticService($request);
        $turnover = $this->statisticsRepository->statisticInvoice($request);
        $data = [
            'turnover' => $turnover,
            'services' => $service,
        ];
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$data,200);
    }
}
