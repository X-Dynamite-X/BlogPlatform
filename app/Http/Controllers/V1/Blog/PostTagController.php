<?php

namespace App\Http\Controllers\V1\blog;

use App\Models\Tag;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function getAllPostInTags(Tag $tag){
        // dd($tag);
        $posts = $tag->posts()->get()->load("user");
        return response()->json(["message"=>"Success response","posts"=>$posts]);
    }
    public function getAllTagInPost(Post $post){

        $tags = $post->tags()->get();
        return response()->json(["message"=>"Success response","tags"=>$tags]);
    }
}
