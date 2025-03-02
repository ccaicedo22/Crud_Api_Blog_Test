<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AuthorController extends Controller {
    
    public function index():JsonResponse  {
        try {
            $authors = Author::all();

            return response()->json([
                'status' => 'success',
                'data' => $authors
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error en el método index del controlador AuthorController',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function store(Request $request):JsonResponse   {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $author = Author::create($validator->validated());

            return response()->json([
                'status' => 'success',
                'data' => $author
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error en el método store del controlador AuthorController',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function show($id):JsonResponse  {
        try {
            $author = Author::findOrFail($id);

            return response()->json([
                'status' => 'success', 
                'data' => $author
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Autor no encontrado',
                'error' => $th->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id):JsonResponse  {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $author = Author::findOrFail($id);
            $author->update($validator->validated());

            return response()->json([
                'status' => 'success', 
                'data' => $author
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error en el método update del controlador AuthorController',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy($id):JsonResponse  {
        try {
            $author = Author::findOrFail($id);
            $author->delete();

            return response()->json([
                'status' => 'success', 
                'message' => 'Autor eliminado'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Autor no encontrado',
                'error' => $th->getMessage()
            ], 404);
        }
    }
}
