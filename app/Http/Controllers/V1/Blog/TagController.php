<?php

namespace App\Http\Controllers\V1\Blog;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\StoreTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return response()->json(["message"=>"success response", "tags"=>$tags]);;
    }
    public function store(StoreTagRequest $request)
    {
        //
        $tag = Tag::create($request->validated());
        return response()->json(["message"=>"success response","tag"=>$tag]);
    }
    public function show(Tag $tag)
    {
        //
        return response()->json(["message"=>"success response", "tags"=>$tag]);;

    }
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        //
        $tag->update($request->validated());
        return response()->json(["message"=>"success response","tag"=>$tag]);
    }
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return response()->json(["message"=>"success delete"]);
    }
}
