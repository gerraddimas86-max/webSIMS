<?php

namespace App\Http\Controllers\post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ===============================
    // INDEX (Tampilkan Semua Post)
    // ===============================
    public function index()
    {
        $posts = Post::with(['user', 'comments.user', 'likes'])
            ->latest()
            ->get();

        return view('posts.index', compact('posts'));
    }

    // ===============================
    // STORE (Simpan Post Baru)
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        Post::create([
            'user_id' => Auth::id(),
            'title'   => $request->title,
            'content' => $request->content
        ]);

        return redirect()->back()->with('success', 'Post created successfully!');
    }

    // ===============================
    // SHOW (Detail Post)
    // ===============================
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    // ===============================
    // EDIT
    // ===============================
    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        return view('posts.edit', compact('post'));
    }

    // ===============================
    // UPDATE
    // ===============================
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $post->update([
            'title'   => $request->title,
            'content' => $request->content
        ]);

        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully!');
    }

    // ===============================
    // DELETE
    // ===============================
    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $post->delete();

        return redirect()->back()
            ->with('success', 'Post deleted successfully!');
    }

    // ===============================
    // LIKE POST
    // ===============================
    public function like(Post $post)
    {
        $post->likes()->firstOrCreate([
            'user_id' => Auth::id()
        ]);

        return redirect()->back();
    }
}