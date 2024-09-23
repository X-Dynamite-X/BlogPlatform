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
        $comments = $post->comments()->with('user')->get();
        return response()->json([
            'message' => 'Comments fetched successfully',
            'comments' => $comments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeForPost(Post $post, StoreCommentRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['post_id'] = $post->id;

        $comment = Comment::create($data);

        return response()->json([
            'message' => 'Comment created successfully',
            'comment' => $comment->load('user'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function showForPost(Post $post, Comment $comment)
    {
        $comment = $post->comments()->where('id', $comment->id)->with('user')->firstOrFail();

        return response()->json([
            'message' => 'Comment fetched successfully',
            'comment' => $comment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateForPost(Post $post, UpdateCommentRequest $request, Comment $comment)
    {
        if( auth()->user()->id === $comment->user_id){

        $comment->update($request->validated());

        return response()->json([
            'message' => 'Comment updated successfully',
            'comment' => $comment->load('user'),
        ]);}
        else{
            return response()->json([
                'message' => 'This action is unauthorized',
                ],403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyForPost(Post $post, Comment $comment)
    {
        if( auth()->user()->id === $comment->user_id){

            $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully',
        ]);}
        else{
            return response()->json([
                'message' => 'This action is unauthorized',
                ],403);
        }
    }
}
