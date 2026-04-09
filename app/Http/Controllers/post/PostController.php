<?php

namespace App\Http\Controllers\post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Notification;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
            ->withCount('likes', 'comments')
            ->latest()
            ->get();
        
        // Tambahkan is_liked_by_user untuk setiap post
        $userId = Auth::id();
        foreach ($posts as $post) {
            $post->is_liked_by_user = $post->likes->contains('user_id', $userId);
            // Atau bisa juga dengan query terpisah untuk performa lebih baik:
            // $post->is_liked_by_user = Like::where('post_id', $post->id)
            //     ->where('user_id', $userId)
            //     ->exists();
        }

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

        $post = Post::create([
            'user_id' => Auth::id(),
            'title'   => $request->title,
            'content' => $request->content
        ]);

        // ==================== CREATE NOTIFICATION FOR CONNECTIONS ====================
        // Kirim notifikasi ke semua connections (user yang terhubung)
        $connections = $this->getConnectedUsers();
        
        foreach ($connections as $connection) {
            Notification::create([
                'user_id' => $connection->id,
                'sender_id' => Auth::id(),
                'type' => 'new_post',
                'notifiable_type' => Post::class,
                'notifiable_id' => $post->id,
                'message' => Auth::user()->name . ' membuat postingan baru: "' . Str::limit($request->title, 50) . '"',
                'action_url' => route('posts.index') . '#post-card-' . $post->id,
                'is_read' => false,
            ]);
        }

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
    // HELPER: Get Connected Users
    // ===============================
    private function getConnectedUsers()
    {
        $userId = Auth::id();
        
        // Ambil semua user yang terhubung (status accepted)
        $connectionIds = \App\Models\Connection::where(function($query) use ($userId) {
                $query->where('requester_id', $userId)
                      ->where('status', 'accepted');
            })
            ->orWhere(function($query) use ($userId) {
                $query->where('receiver_id', $userId)
                      ->where('status', 'accepted');
            })
            ->get()
            ->map(function($connection) use ($userId) {
                return $connection->requester_id == $userId 
                    ? $connection->receiver_id 
                    : $connection->requester_id;
            });
        
        return \App\Models\User::whereIn('id', $connectionIds)->get();
    }
}