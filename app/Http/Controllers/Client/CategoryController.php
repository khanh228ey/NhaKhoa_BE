<?php

namespace App\Http\Controllers\Client;

use App\Commons\Cache\HandleCache;
use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\Translate\CategoryResource as TranslateCategoryResource;
use App\Repositories\Client\CategoryRepository;
use Exception;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    use HandleCache;
    protected $categoryRepo;
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepo = $categoryRepository; 
    }

    public function getCategories(Request $request, $lang)
    {
        $perPage = $request->get('limit', 10);
        $page = $request->get('page');
        $cacheKey = "categories:all:lang_{$lang}";
        $categories = HandleCache::rememberCache( $cacheKey, function () {
            return $this->categoryRepo->getCategory()->get();
        });
        $paginated = collect($categories)->forPage($page, $perPage)->values();
        $result = CategoryResource::collection($paginated);
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
    }


    Public function categoryfindById($lang,$id){
        try {
            $category = $this->categoryRepo->findById($id);
            if($category == false){
                return JsonResponse::handle(404, ConstantsMessage::Not_Found, null, 404);
            }
            $result = ($lang == 'vi') ? new CategoryResource($category)
            :new TranslateCategoryResource($category);
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS,  $result, 200);
            } catch (Exception $e) {
                return JsonResponse::handle(404, ConstantsMessage::ERROR, null, 404);
            }
        }
}
