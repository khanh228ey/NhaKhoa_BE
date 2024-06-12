<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Models\Category;
use App\Models\Service;
use App\Models\Sevice;
use App\Repositories\CategoryRepository;
use App\RequestValidations\CategoryValidation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    protected $categoryRepository;
    protected $categoryValidation;
    public function __construct(CategoryValidation $categoryValidation, CategoryRepository $categoryRepository)
    {
        $this->categoryValidation = $categoryValidation;
        $this->categoryRepository = $categoryRepository; 
      
    }
    public function getCategories(Request $request)
    {
        $perPage = $request->get('limit', 10);
        $page = $request->get('page'); 
        $name = $request->get('name');
        $query = Category::query();
        if ($name) {
            $query->where('name', 'LIKE', "%{$name}%");
        }
        if (!is_null($page)) {
            $data = $query->paginate($perPage, ['*'], 'page', $page);
            $category = $data->items();
        } else {
            $category = $query->get();
        }
    
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $category, 200);
    }
    Public function createCategory(Request $request){
        $validator = $this->categoryValidation->categoryValidate();
        if ($validator->fails()) {
            return JsonResponse::error(400,$validator->messages(),400);
        }
        $category = $this->categoryRepository->addCategory($request->all());
        if ($category == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        return JsonResponse::handle(201, ConstantsMessage::SUCCESS, $category, 201);
    }

    
    Public function findById($id){
        try {
            $category = Category::findOrFail($id); 
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $category, 200);
        } catch (ModelNotFoundException $e) {
            return JsonResponse::handle(404, ConstantsMessage::Not_Found, null, 404);
        }
    }

    Public function updateCategory(Request $request ){
        $validator = $this->categoryValidation->categoryValidate();
        if ($validator->fails()) {
            return JsonResponse::error(400,$validator->messages(),400);
        }
        $category = $this->categoryRepository->updateCategory($request->all());
        if ($category == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        return JsonResponse::handle(201, ConstantsMessage::SUCCESS, $category, 201);
    }

    Public function deleteCategory($id){
        try {
            $category = Category::findOrFail($id); 
            $check = Service::where('category_id',$id)->first();
            if($check){
                return JsonResponse::error(409, 'ràng buộc khóa ngoại', 409);
            }
            $category->delete();
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $category, 200);
        } catch (ModelNotFoundException $e) {
            return JsonResponse::handle(404, ConstantsMessage::Not_Found, null, 404);
        } catch (\Exception $e) {
        return JsonResponse::error(500, ConstantsMessage::ERROR, 500);
    }
    }
}
