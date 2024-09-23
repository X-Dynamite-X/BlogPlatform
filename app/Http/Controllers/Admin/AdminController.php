<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexAllUser()
    {
        $users = User::all()->load("roles");
        $roles = Role::all();

        return response()->json(["users" => $users, "roles" => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeUser(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function showUser(User $user)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function updateUser(Request $request, User $user)
    {
        // التحقق من صحة البيانات
        if(!auth()->user()->hasRole('admin'))
        {
            return response()->json(["message" => 'You do not have permission to edit users'], 403);

        }
        else{$validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string|exists:roles,name', // التحقق من صحة وجود الدور
        ]);

        // تحديث بيانات المستخدم
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        // تحديث الدور
        $user->syncRoles([$validatedData['role']]);

        // حفظ التعديلات
        $user->save();

        // إرجاع استجابة JSON
        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);}
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroyUser(User $user)
    {
        //
        $user->delete();
        return response()->json(["message" => "Delete User is Done ", "user" => $user]);
    }
    public function getAllPost()
    {
        $posts = Post::with(["user", "category", "tags", 'user.roles'])->get();
        return response()->json([
            'posts' => $posts,
        ]);
    }
    public function destroyPost(Post $post){
        $post->delete();
        return response()->json(["message" => "Delete Post is Done ", "post" => $post]);
    }

}
