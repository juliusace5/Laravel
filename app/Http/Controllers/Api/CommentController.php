<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class CommentController extends Controller
{
    // Display a listing of comments for a specific post
    public function index(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:aceposts,id',
        ]);

        $comments = Comment::with('user')
            ->where('post_id', $request->post_id)
            ->get();

        return response()->json($comments);
    }

    // Store a newly created comment and notify post owner
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'post_id' => 'required|exists:aceposts,id',
            'content' => 'required|string',
        ]);

        $validatedData['user_id'] = Auth::id();
        $comment = Comment::create($validatedData);

        $post = Post::find($validatedData['post_id']);
        if ($post && $post->user_id !== Auth::id()) {
            Notification::create([
                'user_id' => $post->user_id,
                'actor_id' => Auth::id(),
                'post_id' => $post->id,
                'type' => 'comment',
                'message' => 'User ' . Auth::user()->name . ' commented on your post.',
            ]);

            // Log notification creation for debugging
            \Log::info('Notification created for post owner', ['post_id' => $post->id, 'commenter_id' => Auth::id()]);
        }

        return response()->json([
            'message' => 'Comment created successfully',
            'data' => $comment
        ], 201);
    }

    // Display a single comment
    public function show($id)
    {
        $comment = Comment::with('user', 'post')->find($id);

        if ($comment) {
            return response()->json($comment);
        }
        return response()->json(['message' => 'Comment not found'], 404);
    }

    // Update a comment if owned by the authenticated user
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = Comment::find($id);
        if ($comment && $comment->user_id === Auth::id()) {
            $comment->update(['content' => $request->input('content')]);
            return response()->json(['message' => 'Comment updated successfully', 'data' => $comment]);
        }

        return response()->json(['message' => 'Comment not found or unauthorized'], 404);
    }

    // Delete a comment if owned by the authenticated user
    public function destroy($id)
    {
        $comment = Comment::find($id);
        if ($comment && $comment->user_id === Auth::id()) {
            $comment->delete();
            return response()->json(['message' => 'Comment deleted successfully']);
        }

        return response()->json(['message' => 'Comment not found or unauthorized'], 404);
    }
}
