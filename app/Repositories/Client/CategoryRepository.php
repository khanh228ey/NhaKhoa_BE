<?php
namespace App\Repositories\Client;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\Translate\CategoryResource as TranslateCategoryResource;
use App\Models\Category;
use App\Models\CategoryTranslation;

class CategoryRepository{

    public function getCategory(){
        $query = Category::where('status',1);
        return $query;
    }

    Public function getCategoryTranlastions(){
        $query = Category::with('translation')->where('status',1);
        return $query;
    }
}