<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserPostsDetail extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;

    public User $user;
    public Post $post;
    public $postImage;
    public $uploadIteration = 0;

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Post';

    protected $rules = [
        'post.title' => ['required', 'max:255', 'string'],
        'post.content' => ['required', 'max:255', 'string'],
        'postImage' => ['nullable', 'image', 'max:1024'],
    ];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->resetPostData();
    }

    public function resetPostData()
    {
        $this->post = new Post();

        $this->postImage = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newPost()
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.user_posts.new_title');
        $this->resetPostData();

        $this->showModal();
    }

    public function editPost(Post $post)
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.user_posts.edit_title');
        $this->post = $post;

        $this->dispatchBrowserEvent('refresh');

        $this->showModal();
    }

    public function showModal()
    {
        $this->resetErrorBag();
        $this->showingModal = true;
    }

    public function hideModal()
    {
        $this->showingModal = false;
    }

    public function save()
    {
        $this->validate();

        if (!$this->post->author_id) {
            $this->authorize('create', Post::class);

            $this->post->author_id = $this->user->id;
        } else {
            $this->authorize('update', $this->post);
        }

        if ($this->postImage) {
            $this->post->image = $this->postImage->store('public');
        }

        $this->post->save();

        $this->uploadIteration++;

        $this->hideModal();
    }

    public function destroySelected()
    {
        $this->authorize('delete-any', Post::class);

        collect($this->selected)->each(function (string $id) {
            $post = Post::findOrFail($id);

            if ($post->image) {
                Storage::delete($post->image);
            }

            $post->delete();
        });

        $this->selected = [];
        $this->allSelected = false;

        $this->resetPostData();
    }

    public function toggleFullSelection()
    {
        if (!$this->allSelected) {
            $this->selected = [];
            return;
        }

        foreach ($this->user->posts as $post) {
            array_push($this->selected, $post->id);
        }
    }

    public function render()
    {
        return view('livewire.user-posts-detail', [
            'posts' => $this->user->posts()->paginate(20),
        ]);
    }
}
