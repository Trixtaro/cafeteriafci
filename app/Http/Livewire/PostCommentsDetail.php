<?php

namespace App\Http\Livewire;

use App\Models\Post;
use App\Models\User;
use Livewire\Component;
use App\Models\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostCommentsDetail extends Component
{
    use AuthorizesRequests;

    public Post $post;
    public Comment $comment;
    public $postUsers = [];

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Comment';

    protected $rules = [
        'comment.content' => ['required', 'max:255', 'string'],
        'comment.author_id' => ['required', 'exists:users,id'],
        'comment.score' => ['required', 'numeric'],
    ];

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->postUsers = User::pluck('name', 'id');
        $this->resetCommentData();
    }

    public function resetCommentData()
    {
        $this->comment = new Comment();

        $this->dispatchBrowserEvent('refresh');
    }

    public function newComment()
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.post_comments.new_title');
        $this->resetCommentData();

        $this->showModal();
    }

    public function editComment(Comment $comment)
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.post_comments.edit_title');
        $this->comment = $comment;

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

        if (!$this->comment->post_id) {
            $this->authorize('create', Comment::class);

            $this->comment->post_id = $this->post->id;
        } else {
            $this->authorize('update', $this->comment);
        }

        $this->comment->save();

        $this->hideModal();
    }

    public function destroySelected()
    {
        $this->authorize('delete-any', Comment::class);

        Comment::whereIn('id', $this->selected)->delete();

        $this->selected = [];
        $this->allSelected = false;

        $this->resetCommentData();
    }

    public function toggleFullSelection()
    {
        if (!$this->allSelected) {
            $this->selected = [];
            return;
        }

        foreach ($this->post->comments as $comment) {
            array_push($this->selected, $comment->id);
        }
    }

    public function render()
    {
        return view('livewire.post-comments-detail', [
            'comments' => $this->post->comments()->paginate(20),
        ]);
    }
}
