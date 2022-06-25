<?php

namespace App\Http\Controllers\PASSPORT;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function createBlog(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'likes' => 'required'
        ]);

        $blog = new Blog();

        $blog->author_id = auth()->user()->id;
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->likes = $request->likes;

        $blog->save();

        return response()->json([
            "status" => 1,
            "message" => "Blog created successfully",
            "blog" => $blog
        ]);
    }

    public function listBlogs()
    {
        $blogs = Blog::all();
        return response()->json([
            "status" => 1,
            "message" => "Blogs",
            "blog" => $blogs
        ]);
    }

    public function getBlog($id)
    {
        $author_id = auth()->user()->id;
        if(Blog::where(['author_id'=> $author_id, 'id' => $id])->exists()){
            return response()->json([
                "status" => 1,
                "blog" => Blog::find($id),
            ]);
        }else{
            return response()->json([
                "status" => 0,
                "message" => "not found",
            ]);
        }
    }
}
