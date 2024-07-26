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
        $service = $this->statisticsRepository->statisticService($request);
        $turnover = $this->statisticsRepository->statisticInvoice($request);
        $invoice = $this->statisticsRepository->getInvoice($request);
        $data = [
            'turnover' => $turnover,
            'invoice' => $invoice,
            'services' => $service,
        ];
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$data,200);
    }
    
}
