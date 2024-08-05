<?php

namespace App\Http\Controllers\Client;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\Translate\CategoryResource as TranslateCategoryResource;
use App\Models\Category;
use App\Repositories\Client\CategoryRepository;
use Exception;

class CategoryController extends Controller
{
    
    protected $categoryRepo;
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepo = $categoryRepository; 
    }

    

    public function getCategories(Request $request,$lang)
        {
            $perPage = $request->get('limit', 10);
            $page = $request->get('page'); 
            if($lang == 'vi'){
                $query = $this->categoryRepo->getCategory();
            }else{
                $query = $this->categoryRepo->getCategoryTranlastions();
            }
            if (!is_null($page)) {
                $data = $query->paginate($perPage, ['*'], 'page', $page);
                $category = $data->items();
            } else {
                $category = $query->get();
            }
            if($lang == 'vi'){
                $result = CategoryResource::collection($category);
            }else{
                $result = TranslateCategoryResource::collection($category);
            }
            
            // $result = CategoryResource::collection($category);
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
        }



        Public function categoryfindById($id){
            try {
                $category = Category::where('status',1)->findOrFail($id); 
                $result = new CategoryResource($category);
                return JsonResponse::handle(200, ConstantsMessage::SUCCESS,  $result, 200);
            } catch (Exception $e) {
                return JsonResponse::handle(404, ConstantsMessage::Not_Found, null, 404);
            }
        }
}
