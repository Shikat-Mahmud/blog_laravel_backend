<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index() {
        $blogs = Blog::orderBy('created_at', 'DESC')->get();

        return response()->json([
            'status' => true,
            'data' => $blogs
        ]);
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|min:10',
                'image' => 'nullable|image',
                'author' => 'required|min:3'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'false',
                    'message' => 'fix the errors.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $blog = new Blog;

            $blog->title = $request->title;
            $blog->author = $request->author;
            $blog->shortDesc = $request->shortDesc;
            $blog->description = $request->description;

            if ($request->hasFile('image')) {
                $image = $request->image;
                $extension = $image->getClientOriginalExtension();
                $imageName = 'blog' . time() . '.' . $extension;
                $blog->image = $imageName;
    
                // Save image
                $image->move(public_path('img'), $imageName);
            }

            $blog->save();

            return response()->json([
                'status' => 'true',
                'message' => 'Blog created successfully.',
                'data' => $blog
            ]);
        } catch (\Exception $e) {
            // Log the exception message
            \Log::error("Error in store method: " . $e->getMessage());

            // Return a JSON response with the error
            return response()->json([
                'status' => 'false',
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }

    public function show($id) {
        $blog = Blog::findOrFail($id);

        $blog['date'] = \Carbon\Carbon::parse($blog->created_at)->format('d M Y');

        if($blog){
            return response()->json([
                "status" => true,
                "data" => $blog
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "The blog is not found."
            ]);
        }

    }

    public function update($id, Request $request)
    {
        \Log::info('Update route accessed with ID:', ['id' => $id]);
        try {
            $blog = Blog::find($id);

            if ($blog == null) {
                return response()->json([
                    "status" => false,
                    "message" => "The blog is not found."
                ]);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|min:10',
                'image' => 'nullable|image',
                'author' => 'required|min:3'
            ]);

            if ($validator->fails()) {
                \Log::error('Validation errors:', $validator->errors()->toArray());
                return response()->json([
                    'status' => 'false',
                    'message' => 'fix the errors.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $blog->title = $request->title;
            $blog->author = $request->author;
            $blog->shortDesc = $request->shortDesc;
            $blog->description = $request->description;

            if ($request->hasFile('image')) {
                $image = $request->image;
                $extension = $image->getClientOriginalExtension();
                $imageName = 'blog' . time() . '.' . $extension;
                $blog->image = $imageName;
    
                // Save image
                $image->move(public_path('img'), $imageName);
            }

            $blog->save();

            return response()->json([
                'status' => 'true',
                'message' => 'Blog updated successfully.',
                'data' => $blog
            ]);
        } catch (\Exception $e) {
            // Log the exception message
            \Log::error("Error in store method: " . $e->getMessage());

            // Return a JSON response with the error
            return response()->json([
                'status' => 'false',
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }
}
