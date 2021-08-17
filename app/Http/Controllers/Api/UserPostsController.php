<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;

class UserPostsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $posts = $user
            ->posts()
            ->search($search)
            ->latest()
            ->paginate();

        return new PostCollection($posts);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $this->authorize('create', Post::class);

        $validated = $request->validate([
            'title' => ['required', 'max:255', 'string'],
            'content' => ['required', 'max:255', 'string'],
            'image' => ['nullable', 'image', 'max:1024'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $post = $user->posts()->create($validated);

        return new PostResource($post);
    }
}
