<?php

namespace App\Http\Controllers;

use App\Http\Resources\blogResource;
use App\Models\Blog;
use App\Models\TagPivot;
use Illuminate\Http\Request;

class blogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchTerm = $request->query('term');

        $query = Blog::with('category', 'tags'); // Eager load relasi

        if ($searchTerm) {
            $query->where('title', 'like', '%' . $searchTerm . '%');
        }

        $posts = $query->get();

        return blogResource::collection($posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

            $request->validate([
        'title' => 'required',
        'content' => 'required',
        'category_id'=>'required',
        'tag'=>'required'
    ]);

        try {
        $blog = Blog::create([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
        ]);

        $tags = [];
        if ($request->filled('tags')) {
            $tags = is_array($request->tags) ? $request->tags : array_map('trim', explode(',', $request->tags));
        } elseif ($request->filled('tag')) {
            $tags = is_array($request->tag) ? $request->tag : array_map('trim', explode(',', $request->tag));
        }

        if (!empty($tags)) {
            $blog->tags()->sync($tags);
        }

        return new blogResource($blog->load('tags', 'category'));
        } catch (\Throwable $err) {
           return response()->json([
        'message' => 'Terjadi kesalahan server saat menyimpan data.',
        'error_detail' => $err->getMessage() // Sertakan pesan error untuk debugging
    ], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Blog::FindOrFail($id);

        return new blogResource($post->load('tags', 'category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,string $id)
    {
        $request->validate([
        'title' => 'required',
        'content' => 'required',
        'category_id'=>'required',
        'tag'=>'required'
    ]);

    try {
        $blog = Blog::FindOrFail($id);

        $blog->update([
            'title'=>$request->title,
            'content'=>$request->content,
            'category_id'=>$request->category_id,
        ]);

        $tags=[];

        if($request->filled('tags')){
            $tags = is_array($request->tags) ? $request->tags : array_map('trim',explode(',',$request->tags));
        } elseif($request->filled('tag')){
            $tag = is_array($request->tag) ? $request->tag : array_map('trim',explode(',',$request->tag));
        }

        if(!empty($tags)){
            $blog->tags()->sync($tags);
        }

        return new blogResource($blog->load('tags','category'));


    } catch (\Throwable $th) {
        return response()->json([
            'message'=>'Server mengalami ganguan',
            'error'=>$th->getMessage()
        ]);
    }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();

        return response()->json([
            'message' => "deleted"
        ], 204);
    }
}
