<?php
namespace App\Repositories;

use App\Models\Category;
use Carbon\Carbon;

class CategoryRepository{

    public function addCategory($data){
        $data = $_REQUEST;
        $category = new Category();
        $category->name = $data['name'];
        $category->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $category->description = $data['description'];
        if($category->save()){
            return $category;
        }
        return false;
    }
}