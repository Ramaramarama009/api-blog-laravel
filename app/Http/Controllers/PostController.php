<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index ()
    {
        $perpage = request()->get('per_page') ?? 1;
        $page = request('page') ?? 1;

        return response()->json([
            'message' => 'success',
            'data' => Post::with(['tags', 'comments'])->paginate($perpage)
        ], 200);
    }
    
    public function store ()
    {

        $post = Post::create([
            'title' => request('title'),
            'body' => request('body'),
            'cover' => request('cover')->store('posts/uploads')
        ]);

        $post->tags()->attach(['1', '2']);

        return response()->json([
            'message' => 'success'
        ], 201);
      
    }

    public function show (Post $post)
    {
        return response()->json([
            'message' => 'success',
            'data' => $post->load('tags')
        ], 200);
    }

    public function update(Post $post)
    {
        if(request()->has('cover')){
            $cover =  request('cover')->store('posts/uploads');
            Storage::disk('public')->delete($post->cover);
        }else{
            $cover = request('oldCover');
        }

        $post->update([
            'title' => request('title'),
            'body' => request('body'),
            'cover' => $cover
        ]);

        return response()->json([
            'message' => 'success',
            'data' => $post
        ], 200);
    }

    public function destroy (Post $post)
    {
        
        Storage::disk('public')->delete($post->cover);
        $post->tags()->detach($post->id);
        $post->delete();

        return response()->json([
            'message' => 'success'
        ], 200);
    }
}
