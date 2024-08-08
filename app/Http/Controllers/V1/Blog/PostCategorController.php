<?php

namespace App\Http\Controllers\V1\Blog;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostCategorController extends Controller
{
    //
    public function getAllPostInCategors(Category $category){
        // dd($tag);
        $posts = $category->posts()->get()->load("user");;
        return response()->json(["message"=>"Success response","posts"=>$posts]);
    }
    public function getAllCategorInPost(Post $post){

        $categors = $post->category()->get();
        return response()->json(["message"=>"Success response","categors"=>$categors]);
    }
}
