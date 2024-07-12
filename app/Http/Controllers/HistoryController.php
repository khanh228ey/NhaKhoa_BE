<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Http\Resources\HistoryResource;
use App\Models\History;
use App\Repositories\HistoryRepository;
use App\Repositories\InvoiceRepository;
use App\RequestValidations\HistoryValidation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    //
    protected $historyRepository;
    protected $historyValidation;
    public function __construct(HistoryValidation $historyValidation, HistoryRepository $historyRepository,InvoiceRepository $invoiceRepository)
    {
        $this->historyValidation = $historyValidation;
        $this->historyRepository = $historyRepository; 
      
    }
    // public function transferInformation(Request $request){
    //     $history = $this->historyRepository->addHistory($request->all());
    //     if ($history == false) {
    //             return JsonResponse::error(401,ConstantsMessage::ERROR,401);
    //     }
    //     return JsonResponse::handle(200, ConstantsMessage::Add, $history, 200);
    // }

    // public function listMeeting(Request $request){
    //     $perPage = $request->get('limit', 10);
    //     $page = $request->get('page'); 
    //     $query = History::with(['Customer', 'Doctor'])->whereNull('date')->whereNull('noted')->where('doctor_id',Auth::user()->id);
    //     if (!is_null($page)) {
    //         $data = $query->paginate($perPage, ['*'], 'page', $page);
    //         $meeting = $data->items();
    //     } else {
    //         $meeting = $query->get();
    //     }
    //     $result = $meeting->map(function ($item) {
    //         return [
    //             'customer'[
    //                 'id' => $item->customer_id,
    //                 'name' => $item->Customer->name,
    //             ],
            
    //     ];
    // });
    //     return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
    // }


    Public function createHistory(Request $request){
  
        $history = $this->historyRepository->addhistory($request->all());
        if ($history == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        return JsonResponse::handle(200, ConstantsMessage::Add, $history, 200);
    }

    public function getHistory(Request $request)
    {
        $perPage = $request->get('limit', 10);
        $page = $request->get('page'); 
        $query = History::with(['Customer', 'Doctor', 'services' => function ($query) {
            $query->select('services.id', 'services.name')
                  ->withPivot('quantity','price'); 
        }])->OrderBy('created_at','DESC');
        if (!is_null($page)) {
            $data = $query->paginate($perPage, ['*'], 'page', $page);
            $history = collect($data->items());
        } else {
            $history = $query->get();
        }
        
        $result = HistoryResource::collection($history);
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
    }

    public function findById($id){
        try {
            $history = History::with(['Customer','Customer.histories' => function($query){
                $query->where('status', '!=', 0);
            }, 
            'Doctor', 'services' ])->findOrFail($id);
            $result = new HistoryResource($history);
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
        } catch (ModelNotFoundException $e) {
            return JsonResponse::handle(404, ConstantsMessage::Not_Found, null, 404);
        }
    }
    
    Public function updateHistory(Request $request,$id){
        $history = History::findOrFail($id);
        try{
            $history = $this->historyRepository->updateHistory($request->all(),$history);
            if ($history == false) {
                    return JsonResponse::error(500,ConstantsMessage::ERROR,500);
            }
            return JsonResponse::handle(200, ConstantsMessage::Update, $history, 200);
        }catch(ModelNotFoundException $e){
            return JsonResponse::handle(404, "Không tìm thấy lịch sử khám", null, 404);
        }
    }   
}