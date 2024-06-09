<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use App\RequestValidations\CustomerValidation;
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
        $name = $request->get('name');
        $phone = $request->get('phone');
        $query = Customer::query();
        if ($name) {
            $query->where('name', 'LIKE', "%{$name}%");
        }
        if($phone){
            $query->where('phone_number', 'LIKE', "%{$phone}%");
        }
        if (!is_null($page)) {
            $data = $query->paginate($perPage, ['*'], 'page', $page);
            $customer = $data->items();
        } else {
            $customer = $query->get();
        }
    
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $customer, 200);
    }



    Public function createCustomer(Request $request){
        $validator = $this->customerValidation->create();
        if ($validator->fails()) {
            return JsonResponse::error(400,$validator->messages(),400);
        }
        $customer = $this->customerRepository->AddCustomer($request->all());
        if ($customer == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        return JsonResponse::handle(201, ConstantsMessage::SUCCESS, $customer, 201);
    }
}
