<?php

namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Repositories\Manager\OverviewRepository;


class OverviewController extends Controller
{
    //
    protected $overviewRepository;
    public function __construct( OverviewRepository $historyRepository)
    {

        $this->overviewRepository = $historyRepository; 
        // $this->middleware('check.role:3');
    }

    public function totalOverView(){
        $invoice = $this->overviewRepository->totalTurnover();
        $appointment = $this->overviewRepository->totalAppointment();
        $history = $this->overviewRepository->totalHistory();
        $customer = $this->overviewRepository->totalCustomer();
        $data = [
            $invoice,
            $appointment,
            $history,
            $customer
        ];
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$data,200);
    }

    Public function monthlyStatistics(){
        $statistics = $this->overviewRepository->monthlyStatistics();
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$statistics,200);
    }

    Public function appointmentStatistics(){
        $statistics = $this->overviewRepository->appointmentStatistics();
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$statistics,200);
    }
}
