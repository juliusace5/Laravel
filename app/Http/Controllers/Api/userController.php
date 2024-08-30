<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class userController extends Controller
{
   public function profile(Request $request)
    {
        if ($request->user()) {
            return response()->json([
                'message' => 'User profile successful',
                'data' => $request->user()
            ], 200);
        } else {
             return response()->json([
                'message' => 'User not authenticated',
            ], 401);
        }

    }
}
