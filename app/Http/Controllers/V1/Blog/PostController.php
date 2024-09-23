<?php
namespace App\Http\Controllers\V1\Blog;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(["user","category","tags",'user.roles'])->get();
        return response()->json([
            'posts' => $posts,
        ]);
    }

    public function getPostInUser(User $user)
    {
        $allPostInUser =  $user->posts()->with(["category","tags","user.roles"])->get();
        return response()->json([
            'allPostInUser' => $allPostInUser,
        ]);
    }

    public function store(StorePostRequest $request)
    {
        $data = $request->safe()->merge(['user_id' => auth()->id()]);
        $post = Post::create($data->all());
        $post->tags()->sync($request->tags);
        return response()->json([
            'post' => $post,
        ]);
    }

    public function show(Post $post)
    {
        $post->load(["category","user","tags",'user.roles',"comments.user"]);
        return response()->json([
            'post' => $post,
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->validated());
        $post->tags()->sync($request->tags);
        return response()->json([
            'post' => $post,
        ]);
    }

    public function destroy(Post $post)
    {
        if (auth()->id() !== $post->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }
}
