<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
class Posts extends Component
{
    use WithPagination;

    public  $title, $body, $post_id;
    public $isOpen = 0;
    public function render()
    {
        //$posts = Post::paginate(5);
        //dd($posts);
        return view('livewire.posts', ['posts' => Post::paginate(5),]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal(): void
    {
        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
    }

    public function resetInputFields(): void
    {
        $this->title = '';
        $this->body = '';
        $this->post_id = '';
    }

    public function store(): void
    {
        $this->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        Post::updateOrCreate(['id' => $this->post_id], [
            'title' => $this->title,
            'body' => $this->body
        ]);

        session()->flash('message',
            $this->post_id ? 'Post Updated Successfully.' : 'Post Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id): void
    {
        $post = Post::findOrFail($id);
        $this->post_id = $id;
        $this->title = $post->title;
        $this->body = $post->body;

        $this->openModal();
    }

    public function delete($id): void
    {
        Post::find($id)->delete();
        session()->flash('message', 'Post deleted successfully.');
    }
}
