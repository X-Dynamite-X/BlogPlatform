<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return response()->json(['roles'=>$roles]);
    }
    public function store(Request $request)
    {
        $role = Role::create($request->all());
        return response()->json(['message'=>'Role created successfully', 'role'=>$role]);

    }
    public function update(Request $request, Role $role)
    {
        $role->update($request->all());
        return response()->json(['message'=>'Role updated successfully', 'role'=>$role]);
    }
    public function destroy(Role $role)
    {
        if (!auth()->user()->hasRole('admin')  || $role->name === 'admin'  ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }
}
