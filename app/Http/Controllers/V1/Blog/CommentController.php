<?php

namespace App\Http\Controllers\V1\Blog;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexForPost(Post $post)
    {
        $comments = $post->comments()->get();
        return response()->json([
            'message' => 'Comments fetched successfully',
            'comments' => $comments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeForPost(Post $post,StoreCommentRequest $request)
    {
        $data =  $request->safe()->merge([
            'user_id' => auth()->id(),
            "post_id"=>$post->id]);
        $comment = Comment::create($data->all());

        return response()->json([
            'message' => 'Comment created successfully',
            'comment' => $comment,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function showForPost(Post $post,Comment $comment)
    {
        $comment = $post->comments()->get()->find($comment->id);
        return response()->json([
            'message' => 'Comment fetched successfully',
            'comment' => $comment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateForPost(Post $post,UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->safe()->all());
        return response()->json([
            'message' => 'Comment updated successfully',
            'comment' => $comment,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyForPost(Post $post,Comment $comment)
    {
        if (auth()->id() !== $comment->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully',"comments"=>$post->comments()->get()]);
    }
}
