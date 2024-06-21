<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
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
        $result = $customer->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'birthday' => $item->birthday,
                'email' => $item->email,
                'phone' => $item->phone_number,
                
            ];
        });
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS,  $result, 200);
    }



    Public function createCustomer(Request $request){
        $validator = $this->customerValidation->customerValidation();
        if ($validator->fails()) {
            return JsonResponse::error(400,$validator->messages(),400);
        }
        $customer = $this->customerRepository->AddCustomer($request->all());
        if ($customer == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        return JsonResponse::handle(201, ConstantsMessage::SUCCESS, $customer, 201);
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
            return JsonResponse::error(400,$validator->messages(),400);
        }
        $category = $this->customerRepository->Update($request->all(),$id);
        if ($category == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        return JsonResponse::handle(201, ConstantsMessage::SUCCESS, $category, 201);
    }
}
