<?php
namespace App\Repositories;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryRepository{

    public function addCategory(Request $request){
        $data = $request->all();
        $category = new Category();
        $category->name = $data['name'];
        $category->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $category->description = $data['description'];
        $category->status = $data['status'];
        $category->image = $data['image'];
        if ($category->save()) {
            return $category;
        }

        return false;
    }

    public function updateCategory(Request $request,$category){
        $data = $request->only(['name', 'status', 'image']);
        if (isset($request->description) && $request->description !== '') {
            $data['description'] = $request->description;
        }    
        $data['updated_at'] = Carbon::now('Asia/Ho_Chi_Minh');
        $category->fill($data);
        if ($category->save()) {
            return $category;
        }
        return false;
    }
}