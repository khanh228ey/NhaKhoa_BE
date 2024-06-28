<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Http\Resources\HistoryResource;
use App\Models\History;
use App\Repositories\HistoryRepository;
use App\RequestValidations\HistoryValidation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        return JsonResponse::handle(201, ConstantsMessage::Add, $history, 201);
    }

    public function listMeeting(Request $request){
        $perPage = $request->get('limit', 10);
        $page = $request->get('page'); 
        $query = History::with(['Customer', 'Doctor'])->whereNull('date')->whereNull('noted');
        if (!is_null($page)) {
            $data = $query->paginate($perPage, ['*'], 'page', $page);
            $meeting = $data->items();
        } else {
            $meeting = $query->get();
        }
        $result =HistoryResource::collection($meeting);
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
    }





    Public function createHistory(Request $request){
        $validator = $this->historyValidation->history();
        if ($validator->fails()) {
              return JsonResponse::handle(400,ConstantsMessage::Bad_Request,$validator->messages(),400);
        }
        $history = $this->historyRepository->addhistory($request->all());
        if ($history == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        return JsonResponse::handle(201, ConstantsMessage::Add, $history, 201);
    }

    public function getHistory(Request $request)
    {
        $perPage = $request->get('limit', 10);
        $page = $request->get('page'); 
        $customer_id = $request->get('customer_id');
        $doctor_id = $request->get('doctor_id');
        $query = History::with(['Customer', 'Doctor', 'services' => function ($query) {
            $query->select('services.id', 'services.name')
                  ->withPivot('quantity', 'price'); 
        }])->whereNotNull('date')->whereNotNull('noted');
        if ($customer_id) {
            $query->where('customer_id', $customer_id);
        }
        if ($doctor_id) {
            $query->where('doctor_id', $doctor_id);
        }
        if (!is_null($page)) {
            $data = $query->paginate($perPage, ['*'], 'page', $page);
            $history = collect($data->items());
        } else {
            $history = $query->get();
        }
        
        $result = $history->map(function ($item) {
            return [
                'id' => $item->id,
                'customer_name' => $item->customer->name,
                'doctor_name' => $item->doctor->name,
                'date' => $item->date,
                'time' => $item->time,
            ];
        });
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
    }


    public function findById($id){
        try {
        $history = History::with(['Customer', 'Doctor', 'services'])->whereNotNull('date')->whereNotNull('noted')->findOrFail($id);

        $result = new HistoryResource($history);

        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
    } catch (ModelNotFoundException $e) {
        return JsonResponse::handle(404, ConstantsMessage::Not_Found, null, 404);
    }
}
    
    Public function updateHistory(Request $request,$id){
        $validator = $this->historyValidation->history();
        if ($validator->fails()) {
              return JsonResponse::handle(400,ConstantsMessage::Bad_Request,$validator->messages(),400);
        }
        $history = $this->historyRepository->updateHistory($request->all(),$id);
        if ($history == false) {
                return JsonResponse::error(500,ConstantsMessage::ERROR,500);
        }
        return JsonResponse::handle(201, ConstantsMessage::Update, $history, 201);
    }

}
