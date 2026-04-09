<?php

namespace App\Http\Controllers\post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // <-- Tambahkan import Str

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        // Pastikan request AJAX
        if (!$request->ajax()) {
            return redirect()->back();
        }
        
        try {
            $request->validate([
                'content' => 'required|string|max:500'
            ]);
            
            $comment = Comment::create([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'content' => $request->content
            ]);
            
            // ==================== CREATE NOTIFICATION ====================
            // Buat notifikasi untuk pemilik post (jika bukan komentar sendiri)
            if ($post->user_id !== Auth::id()) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'sender_id' => Auth::id(),
                    'type' => 'comment',
                    'notifiable_type' => Comment::class,
                    'notifiable_id' => $comment->id,
                    'message' => Auth::user()->name . ' mengomentari postinganmu: "' . Str::limit($request->content, 50) . '"',
                    'action_url' => route('posts.index') . '#post-card-' . $post->id,
                    'is_read' => false,
                ]);
            }
            
            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'user_name' => Auth::user()->name,
                    'user_avatar' => strtoupper(substr(Auth::user()->name, 0, 2)),
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->diffForHumans()
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update comment
    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $request->validate(['content' => 'required|string|max:500']);
        
        $comment->update(['content' => $request->content]);
        
        return response()->json(['success' => true, 'message' => 'Komentar berhasil diperbarui']);
    }

    // Delete comment
    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $comment->delete();
        
        return response()->json(['success' => true, 'message' => 'Komentar berhasil dihapus']);
    }
}