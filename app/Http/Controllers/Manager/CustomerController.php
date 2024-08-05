<?php

namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Repositories\Manager\CustomerRepository;
use App\RequestValidations\CustomerValidation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    protected $customerRepository;
    protected $customerValidation;
    public function __construct(CustomerValidation $customerValidation, CustomerRepository $customerRepository)
    {
        $this->customerValidation = $customerValidation;
        $this->customerRepository = $customerRepository; 
        
    }
    
    public function getCutomer(Request $request)
    {
        $perPage = $request->get('limit', 10);
        $page = $request->get('page'); 
        $query = Customer::with(['Histories' => function ($query) {
            $query->whereNotNull('date')
                  ->whereNotNull('time')
                  ->where('status', '!=', 0);
        }]);
        if (!is_null($page)) {
            $data = $query->paginate($perPage, ['*'], 'page', $page);
            $customer = collect($data->items());
        } else {
            $customer = $query->get();
        }
        $result = CustomerResource::collection($customer);
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS,  $result, 200);
    }



    Public function createCustomer(Request $request){
            $validator = $this->customerValidation->customerValidation();
            if ($validator->fails()) {
                $firstError = $validator->messages()->first();
                return JsonResponse::handle(400,$firstError,$validator->messages(),400);
            }
            $customer = $this->customerRepository->AddCustomer($request);
            $customer = new CustomerResource($customer);
            if ($customer == false) {
                    return JsonResponse::error(401,ConstantsMessage::ERROR,401);
            }
            return JsonResponse::handle(200, ConstantsMessage::Add, $customer, 200);
    }

    
    Public function findById($id){
        try {
            $customer = Customer::findOrFail($id); 
            $result = new CustomerResource($customer);
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS,  $result, 200);
        } catch (ModelNotFoundException $e) {
            return JsonResponse::handle(404, ConstantsMessage::Not_Found, null, 404);
        }
    }

    Public function updateCustomer(Request $request,$id ){
        $validator = $this->customerValidation->customerValidation();
        if ($validator->fails()) {
            $firstError = $validator->messages()->first();
              return JsonResponse::handle(400,$firstError,$validator->messages(),400);
        }
        $customer = $this->customerRepository->Update($request->all(),$id);
        if ($customer == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        $customer = new CustomerResource($customer);
        return JsonResponse::handle(200, ConstantsMessage::Update, $customer, 200);
    }
}
