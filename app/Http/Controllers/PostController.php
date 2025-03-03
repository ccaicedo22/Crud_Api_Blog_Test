<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller {

    public function index(): JsonResponse {
        try {
            
            $posts = Post::with('author')->get();

            return response()->json([
                'status' => 'success',
                'data' => $posts
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error en el método index del controlador PostController',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'file' => 'nullable|file|mimes:jpg,jpeg,png,svg,webp|max:5120', 
                'author_id' => 'required|exists:authors,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $imageUrl = null;
            
            if ($request->hasFile('file')) {
                $fileName = time() . '_' . $request->file('file')->getClientOriginalName();

                $request->file('file')->storeAs('public/posts', $fileName);

                $imageUrl = asset('storage/posts/' . $fileName);
            }

            $post = Post::create([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $imageUrl,  
                'author_id' => $request->author_id
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $post
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error en el método store del controlador PostController',
                'error' => $th->getMessage()
            ], 500);
        }
    }


    public function show(int $id): JsonResponse {
        try {
            $post = Post::with('author')->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $post
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Post no encontrado',
                'error' => $th->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'author_id' => 'required|exists:authors,id',
                'file' => 'nullable|file|mimes:jpg,jpeg,png,svg,webp|max:5120', 
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $post = Post::findOrFail($id);
            $imageUrl = $post->image; 

            if ($request->hasFile('file')) {

                $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
                $request->file('file')->storeAs('public/posts', $fileName);

                $imageUrl = asset('storage/posts/' . $fileName);
            }

            $post->update([
                'title' => $request->title,
                'content' => $request->content,
                'author_id' => $request->author_id,
                'image' => $imageUrl, 
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $post
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error en el método update del controlador PostController',
                'error' => $th->getMessage()
            ], 500);
        }
    }


    public function destroy(int $id): JsonResponse {
        try {
            $post = Post::findOrFail($id);
            $post->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Post eliminado'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Post no encontrado',
                'error' => $th->getMessage()
            ], 404);
        }
    }
}

