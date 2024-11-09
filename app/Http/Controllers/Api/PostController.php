<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // Display a listing of all posts
    public function index()
    {
        $posts = Post::with('user', 'comments')->get();

        $formattedPosts = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'content' => $post->content,
                'created_at' => $post->created_at,
                'author_id' => $post->user->id,
                'author_name' => $post->user->name,
                'author_avatar' => $post->user->avatar ?? 'default-avatar.png',
                'comments' => $post->comments,
            ];
        });

        return response()->json($formattedPosts);
    }

    // Store a newly created post
    public function store(Request $request)
    {
        $request->validate(['content' => 'required|string']);

        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

        return response()->json(['message' => 'Post created successfully', 'data' => $post], 201);
    }

    // Display a single post with its details
    public function show($id)
    {
        $post = Post::with('user', 'comments')->find($id);

        if ($post) {
            $formattedPost = [
                'id' => $post->id,
                'content' => $post->content,
                'created_at' => $post->created_at,
                'author_name' => $post->user->name,
                'author_avatar' => $post->user->avatar ?? 'default-avatar.png',
                'comments' => $post->comments,
            ];

            return response()->json($formattedPost);
        }

        return response()->json(['message' => 'Post not found'], 404);
    }

    // Update a post if owned by the authenticated user
    public function update(Request $request, $id)
    {
        $request->validate(['content' => 'required|string']);

        $post = Post::find($id);
        if ($post && $post->user_id === Auth::id()) {
            $post->update(['content' => $request->input('content')]);
            return response()->json(['message' => 'Post updated successfully', 'data' => $post]);
        }

        return response()->json(['message' => 'Post not found or unauthorized'], 404);
    }

    // Delete a post if owned by the authenticated user
    public function destroy($id)
    {
        $post = Post::find($id);
        if ($post && $post->user_id === Auth::id()) {
            $post->delete();
            return response()->json(['message' => 'Post deleted successfully']);
        }

        return response()->json(['message' => 'Post not found or unauthorized'], 404);
    }
}
