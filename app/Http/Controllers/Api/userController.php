<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class userController extends Controller
{
    // Method to get the profile of the authenticated user
    public function profile(Request $request)
    {
        if ($request->user()) {
            return response()->json([
                'message' => 'User profile retrieved successfully',
                'data' => $request->user()
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not authenticated',
            ], 401);
        }
    }

    // Method to get the profile by user ID
    public function profileById($id)
    {
        $user = User::find($id); // Find user by ID

        if ($user) {
            return response()->json([
                'message' => 'User profile retrieved successfully',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
    }

    public function updateProfile(Request $request)
{
    $user = $request->user();

    if ($user) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
        ]);

        $user->update($validatedData);

        return response()->json([
            'message' => 'User profile updated successfully',
            'data' => $user
        ], 200);
    } else {
        return response()->json([
            'message' => 'User not authenticated',
        ], 401);
    }
}

}

