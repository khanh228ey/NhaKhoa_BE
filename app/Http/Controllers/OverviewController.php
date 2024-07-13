<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Models\Appointment;
use App\Repositories\OverviewRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OverviewController extends Controller
{
    //
    protected $overviewRepository;
    public function __construct( OverviewRepository $historyRepository)
    {
        $this->overviewRepository = $historyRepository; 
      
    }

    
    public function totalOverView(){
        
    }
}
