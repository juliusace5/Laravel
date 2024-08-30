<?php

namespace App\Http\Controllers\Api;

use App\Models\BlogPosts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $blogs = BlogPosts::all();
            return response()->json([
                'message' => 'Successfully retrieved all blog posts.',
                'data' => $blogs
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve blog posts.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author' => 'required|string|max:255',
        ]);

        try {
            $blog = BlogPosts::create($validatedData);
            return response()->json([
                'message' => 'Blog post created successfully.',
                'data' => $blog
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create blog post.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $blog = BlogPosts::findOrFail($id);
            return response()->json([
                'message' => 'Blog post retrieved successfully.',
                'data' => $blog
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Blog post not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve blog post.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $blog = BlogPosts::findOrFail($id);

            $validatedData = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'content' => 'sometimes|required|string',
                'author' => 'sometimes|required|string|max:255',
            ]);

            $blog->update($validatedData);

            return response()->json([
                'message' => 'Blog post updated successfully.',
                'data' => $blog
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Blog post not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update blog post.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $blog = BlogPosts::findOrFail($id);
            $blog->delete();

            return response()->json([
                'message' => 'Blog post deleted successfully.'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Blog post not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete blog post.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
