<?php

namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    //

    public function getRoles(){
        $roles = Role::all();
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $roles, 200);
    }

    Public function findByID($id){
        $role = Role::with('permissions')->find($id);
        $result = new RoleResource($role);
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
    }

    Public function updatePermissions(Request $request, $id){
        
        $role = Role::findById($id);
        if($role->name == 'ADMIN'){
            return JsonResponse::error(403, "Không thể phân quyền cho role Admin", 403);
        }
        if (!$role) {
            return response()->json([
                'status' => 404,
                'message' => 'Role not found.',
            ], 404);
        }
        $permissions = $request->input('permissions');
        $role->syncPermissions($permissions);
        return JsonResponse::handle(200, ConstantsMessage::Update, null, 200);
    }
}
