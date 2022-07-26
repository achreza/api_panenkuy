<?php

namespace App\Http\Controllers;

use App\Http\Resources\Comment\CommentCollection;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index']);
    }

    public function index(Post $post)
    {

        $this->authorize('show all comment');

        try {
            $comments = new CommentCollection(Comment::with('user','post')->where('post_id',$post->id)->get());

            return $this->successResponse($comments,'Comments shown successfully');

        } catch (QueryException $e) {

            return $this->errorResponse($e, 'Cannot get comment list');

        }        
                


    }    

    public function store(Post $post,  Request $request)
    {
        $this->authorize('create comment');

        try {
            $comment = new Comment();

            $comment->content = $request->content;
            $comment->post_id = $post->id;
            $comment->created_by = auth()->id();

            $comment->save();

            return $this->successResponse($comment,'Comments has been created successfully');
        } catch (QueryException $e) {
            return $this->errorResponse($e, 'Cannot save comment data');
        }
    }
    
    public function update(Post $post, Comment $comment, Request $request)
    {
        $this->authorize('update comment');

        $comment = Comment::findOrFail($comment->id);        
        try {         
            $comment->content = $request->content;        

            $comment->save();

            return $this->successResponse($comment,'Comments has been updated successfully');
        } catch (QueryException $e) {
            return $this->errorResponse($e, 'Cannot update comment data');
        }
    }
    
    public function destroy(Post $post, Comment $comment)
    {
        $this->authorize('delete comment');

        $comment = Comment::findOrFail($comment->id);

        try {
            
            $temp_comment = $comment;

            $comment->delete();

            return $this->successResponse($temp_comment,'Comments has been deleted successfully');
        } catch (\Throwable $e) {
            return $this->errorResponse($e, 'Cannot delete comment data');
        }
    }
}
