<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:10',
            'author' => 'required|min:3'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'false',
                'message' => 'fix the errors.',
                'errors' => $validator->errors()
            ]);
        }

        $blog = new Blog;

        $blog->title = $request->title;
        $blog->author = $request->author;
        $blog->shortDesc = $request->shortDesc;
        $blog->description = $request->description;
        $blog->save();

        return response()->json([
            'status' => 'true',
            'message' => 'Blog created successfully.',
            'data' => $blog
        ]);
    }
}
