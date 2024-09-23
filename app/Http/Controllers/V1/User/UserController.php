<?php

namespace App\Http\Controllers\v1\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // dd(auth()->user());
        $user = auth();
        // dd($user);
        return response()->json(["usre"=>$user,]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
        $profile= $user->load('roles');
        // $profile="test";
        return response()->json(["profile"=>$profile]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            // Add other fields that need validation
        ]);

        // Update the user with validated data
        $user->update($validatedData);

        // Return a success response with the updated user data
        return response()->json([
            "message" => "Profile updated successfully",
            "user" => $user,
        ]);
    }
    public function updateAuth(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $user = auth()->user();
        $user->update($validatedData);
        // Return a success response with the updated user data
        return response()->json([
            "message" => "Profile updated successfully",
            "user" => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
