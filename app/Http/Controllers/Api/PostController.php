<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use App\Http\Requests\PostStoreRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PostUpdateRequest;

class PostController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Post::class);

        $search = $request->get('search', '');

        $posts = Post::search($search)
            ->latest()
            ->paginate();

        return new PostCollection($posts);
    }

    /**
     * @param \App\Http\Requests\PostStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostStoreRequest $request)
    {
        $this->authorize('create', Post::class);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $post = Post::create($validated);

        return new PostResource($post);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Post $post)
    {
        $this->authorize('view', $post);

        return new PostResource($post);
    }

    /**
     * @param \App\Http\Requests\PostUpdateRequest $request
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::delete($post->image);
            }

            $validated['image'] = $request->file('image')->store('public');
        }

        $post->update($validated);

        return new PostResource($post);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Post $post)
    {
        $this->authorize('delete', $post);

        if ($post->image) {
            Storage::delete($post->image);
        }

        $post->delete();

        return response()->noContent();
    }
}
