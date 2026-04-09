<?php

namespace App\Http\Controllers\post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Like;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        // Pastikan request AJAX
        if (!request()->ajax()) {
            return redirect()->back();
        }
        
        try {
            $userId = Auth::id();
            
            $existingLike = Like::where('post_id', $post->id)
                ->where('user_id', $userId)
                ->first();
            
            if ($existingLike) {
                $existingLike->delete();
                $liked = false;
            } else {
                Like::create([
                    'post_id' => $post->id,
                    'user_id' => $userId
                ]);
                $liked = true;
                
                // ==================== CREATE NOTIFICATION ====================
                // Buat notifikasi untuk pemilik post (jika bukan like sendiri)
                if ($post->user_id !== $userId) {
                    Notification::create([
                        'user_id' => $post->user_id,
                        'sender_id' => Auth::id(),
                        'type' => 'like',
                        'notifiable_type' => Post::class,
                        'notifiable_id' => $post->id,
                        'message' => Auth::user()->name . ' menyukai postinganmu',
                        'action_url' => route('posts.index') . '#post-card-' . $post->id,
                        'is_read' => false,
                    ]);
                }
            }
            
            // Ambil updated like count
            $likesCount = $post->likes()->count();
            
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $likesCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}