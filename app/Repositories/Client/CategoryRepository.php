<?php
namespace App\Repositories\Client;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryRepository{

    public function getCategory(){
        $query = Category::where('status',1);
        return $query;
    }

    public function findById($id){
        try{
            $category = Category::where('status',1)->findOrFail($id);
            return $category;
        }catch(ModelNotFoundException $e){
            return false;
        }
    }


}