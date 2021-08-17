<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Post;
use Livewire\Component;
use App\Models\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserCommentsDetail extends Component
{
    use AuthorizesRequests;

    public User $user;
    public Comment $comment;
    public $userPosts = [];

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Comment';

    protected $rules = [
        'comment.content' => ['required', 'max:255', 'string'],
        'comment.post_id' => ['required', 'exists:posts,id'],
        'comment.score' => ['required', 'numeric'],
    ];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->userPosts = Post::pluck('title', 'id');
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
        $this->modalTitle = trans('crud.user_comments.new_title');
        $this->resetCommentData();

        $this->showModal();
    }

    public function editComment(Comment $comment)
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.user_comments.edit_title');
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

        if (!$this->comment->author_id) {
            $this->authorize('create', Comment::class);

            $this->comment->author_id = $this->user->id;
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

        foreach ($this->user->comments as $comment) {
            array_push($this->selected, $comment->id);
        }
    }

    public function render()
    {
        return view('livewire.user-comments-detail', [
            'comments' => $this->user->comments()->paginate(20),
        ]);
    }
}
