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
        $category->status= $data['status'];
        if($category->save()){
            return $category;
        }
        return false;
    }

    Public function updateCategory($data){
        $category = Category::find($data['id']);
        $category->name = $data['name'];
        $category->status = $data['status'];
        $category->description =$data['description'];
        $category->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        if($category->save()){
            return $category;
        }
        return false;
    }
}