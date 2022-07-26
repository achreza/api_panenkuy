<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\PostStoreRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    public function index()
    {
        $this->authorize('show all post');

        $postsCache = Cache::get('posts');;
        
        if (isset($postsCache)) {
            $posts = $postsCache;            
            return $this->successResponse($posts, 'List Post has been shown successfully from cache');
        }else {
            try {                
                $posts = new PostCollection(Post::with('user','comments')->paginate(15));
                Cache::put('posts', $posts, 100);
            return $this->successResponse($posts, 'List Post has been shown successfully');
            } catch (Exception $e) {
                return $this->errorResponse($e, 'posts list cant be shown');
            }    
        }            
    }

    public function store(PostStoreRequest $request)
    {
        $this->authorize('create post');

        $response['picture'] = "";
        $response['post'] = "";        
                
        if ($request->picture != null) {
            $response['picture'] = $this->savePicture($request->file('picture'));
        }

        try {
            $post = new Post();

            $post->fill($request->all());

            $post->created_by = auth()->id();
            $post->contact_person = json_encode($request->contact_person);

            if ($request->picture != null) {
                $post->picture = $response['picture']['picture_name'];
            }            

            $post->save();
            $response['post'] = $post;

            Artisan::call('cache:clear');
            return $this->successResponse($response, "Upload data post success");

        } catch (\Throwable $th) {
            return $this->errorResponse($th, 'Error storing post data');
        }
    }

    public function show(Post $post)
    {
        $this->authorize('show post');

        $post = new PostResource(Post::with('user')->findOrFail($post->id));

        return $this->successResponse($post, 'Post has been shown successfully');
    }

    public function update(PostUpdateRequest $request, Post $post)
    {
        $this->authorize('update post');

        $response['post'] = '';
        $response['picture'] = '';

        if ($request->picture != null) {
            $response['picture'] = $this->savePicture($request->file('picture'));
        }

        try {
            $post->fill($request->all());

            if ($request->filled('picture')) {
                $post->picture = $response['picture']['picture_name'];
            }

            $post->contact_person = json_encode($request->contact_person);

            $post->save();

            $response['post'] = $post;

            return $this->successResponse($response, "Update data post success");
        } catch (\Throwable $th) {
            return $this->errorResponse($th, 'Error storing post data');
        }
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete post');

        $post = Post::findOrFail($post->id);
        $temp_post = $post;
        try {
            $post->delete();

            return $this->successResponse($temp_post,'Post successfulyy deleted');

        } catch (QueryException $e) {
            return $this->errorResponse($e,"Error desroyin data post");
        }
    }

    public function savePicture($image)
    {
        try {
            $uploadFolder = 'posts';
            $image_uploaded_path = $image->store($uploadFolder, 'public');

            return
            [
                "picture_name" => basename($image_uploaded_path),
                "picture_url" => Storage::disk('public')->url($image_uploaded_path),
                "mime" => $image->getClientMimeType()
            ];            
        } catch (\Throwable $th) {
            return $this->errorResponse($th, " Error uploading Image");
        }
    }
}
