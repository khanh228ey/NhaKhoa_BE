<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Models\History;
use App\Repositories\HistoryRepository;
use App\RequestValidations\HistoryValidation;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    //
    protected $historyRepository;
    protected $historyValidation;
    public function __construct(HistoryValidation $historyValidation, HistoryRepository $historyRepository)
    {
        $this->historyValidation = $historyValidation;
        $this->historyRepository = $historyRepository; 
      
    }
    public function transferInformation(Request $request){
        $history = $this->historyRepository->addHistory($request->all());
        if ($history == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        return JsonResponse::handle(201, ConstantsMessage::SUCCESS, $history, 201);
    }

    Public function createHistory(Request $request){
        $validator = $this->historyValidation->history();
        if ($validator->fails()) {
            return JsonResponse::error(400,$validator->messages(),400);
        }
        $history = $this->historyRepository->addhistory($request->all());
        if ($history == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        return JsonResponse::handle(201, ConstantsMessage::SUCCESS, $history, 201);
    }

    public function getHistory(Request $request)
    {
        $perPage = $request->get('limit', 10);
        $page = $request->get('page'); 
        $customer_id = $request->get('customer_id');
        $doctor_id = $request->get('doctor_id');
        $query = History::with('Customer','Doctor');
        if ($customer_id) {
            $query->where('customer_id', $customer_id);
        }
        if ($doctor_id) {
            $query->where('doctor_id', $doctor_id);
        }
        if (!is_null($page)) {
            $data = $query->paginate($perPage, ['*'], 'page', $page);
            $history = $data->items();
        } else {
            $history = $query->get();
        }
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $history, 200);
    }
}
