<?php

namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;
use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DeleteResource;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Service;
use App\Repositories\Manager\CategoryRepository;
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
        // $this->middleware('check.role:3')->except('getCategories');
    }

    public function getCategories(Request $request)
    {
        $perPage = $request->get('limit', 10);
        $page = $request->get('page'); 
        $status = $request->get('status');
        $query = Category::query();
        if($status){
            $query->where('status',  $status);
        }
        if (!is_null($page)) {
            $data = $query->paginate($perPage, ['*'], 'page', $page);
            $category = collect($data->items());
        } else {
            $category = $query->get();
        }
        $result = CategoryResource::collection($category);
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
    }
    
    Public function createCategory(Request $request){
        $validator = $this->categoryValidation->categoryValidate();
        if ($validator->fails()) {
            $firstError = $validator->messages()->first();
            return JsonResponse::handle(400, $firstError,$validator->messages(),400);
        }
        $category = $this->categoryRepository->addCategory($request);
        if ($category == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        $category = new CategoryResource($category);
        return JsonResponse::handle(200, ConstantsMessage::Add, $category, 200);
    }
    
    
    Public function findById($id){
        try {
            $category = Category::with('Services')->findOrFail($id); 
            $result = new CategoryResource($category);
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS,  $result, 200);
        } catch (ModelNotFoundException $e) {
            return JsonResponse::handle(404, 'Danh mục không tồn tại', null, 404);
        }
    }

    Public function updateCategory(Request $request,$id ){
        try{
                $category = Category::findOrFail($id);
                $data = $request->all();
                if(count($data)  >1){
                    $validator = $this->categoryValidation->categoryValidate();
                    if ($validator->fails()) {
                        $firstError = $validator->messages()->first();
                        return JsonResponse::handle(400,$firstError,$validator->messages(),400);
                    }
                }  
                $category = $this->categoryRepository->updateCategory($request,$category);
                $category = new CategoryResource($category);
                return JsonResponse::handle(200, ConstantsMessage::Update, $category, 200);
            }catch (ModelNotFoundException $e){
                return JsonResponse::handle(404, "Danh mục không tồn tại", null, 404);
            } catch (\Exception $e) {
                return JsonResponse::error(500, ConstantsMessage::ERROR, 500);
            }
    }

    Public function deleteCategory($id){
        try {
            $category = Category::findOrFail($id); 
            $check = Service::where('category_id',$id)->first();
            if($check){
                return JsonResponse::error(409, 'Ràng buộc dữ liệu', 409);
            }
            $category->delete();
            $category = new DeleteResource($category);
            return JsonResponse::handle(200, ConstantsMessage::Delete,$category, 200);
        } catch (ModelNotFoundException $e) {
            return JsonResponse::handle(404, "Danh mục không tồn tại", null, 404);
        } catch (\Exception $e) {
        return JsonResponse::error(500, ConstantsMessage::ERROR, 500);
    }
    }

    public function getCateTranslate($id){
        try {
            $cateTranslate = CategoryTranslation::where('category_id', $id)->firstOrFail();
            return JsonResponse::handle(404,ConstantsMessage::SUCCESS,$cateTranslate,404);
        } catch (ModelNotFoundException $e) {
            return JsonResponse::handle(404,ConstantsMessage::Not_Found,null,404);
        }
    }
    public function createCateTranslate(){
        
    }
}
