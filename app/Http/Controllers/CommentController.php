<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content
        ]);

        return redirect()->back();
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id != Auth::id()) {
            abort(403);
        }

        $comment->delete();

        return redirect()->back();
    }
}