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
      
    }

    Public function getServiceStatistics(Request $request){
        $service = $this->statisticsRepository->statisticService($request);
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$service,200);
    }
}
