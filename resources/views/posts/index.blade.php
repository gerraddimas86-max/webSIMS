@extends('layouts.app')

@section('content')

<!-- PROFILE CARD (BACKGROUND IMAGE VERSION) -->
<div class="relative rounded-2xl overflow-hidden shadow mb-6">

    <!-- Background Image -->
    <img src="{{ asset('images/unsri_bg.jpg') }}"
         class="absolute inset-0 w-full h-full object-cover">

    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/50"></div>

    <!-- Content -->
    <div class="relative z-10 p-6 flex justify-between items-center text-white">

        <!-- LEFT SIDE (User Info) -->
        <div>
            <h3 class="text-lg font-bold">
                {{ auth()->user()->name }}
            </h3>

            <p class="text-sm opacity-90">
                {{ auth()->user()->email }}
            </p>

            <p class="text-sm mt-2">
                Connections:
                <span class="font-semibold">
                    {{ auth()->user()->connections()->count() }}
                </span>
            </p>
        </div>

        <!-- RIGHT SIDE (Logo tetap kanan walaupun mobile) -->
        <div class="shrink-0">
            <img src="{{ asset('images/Logo_unsri.jpg') }}"
                 class="w-16 h-16 object-contain">
        </div>

    </div>

</div>


<!-- CREATE POST -->
<div class="bg-white p-6 rounded-2xl shadow mb-8">

    <form action="{{ route('posts.store') }}" method="POST">
        @csrf

        <!-- TITLE -->
        <input type="text"
            name="title"
            placeholder="Post title..."
            class="w-full border rounded-xl p-3 mb-3 focus:ring-2 focus:ring-blue-500"
            required>

        <!-- CONTENT -->
        <textarea name="content"
            class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-500"
            placeholder="Share something with the community..."
            required></textarea>

        <button
            class="mt-3 bg-blue-600 text-white px-6 py-2 rounded-xl hover:bg-blue-700 transition w-full sm:w-auto">
            Post
        </button>

    </form>

</div>


<!-- POSTS -->
@forelse($posts as $post)

<div class="bg-white p-6 rounded-2xl shadow mb-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-3">

        <div>
            <h4 class="font-semibold">
                {{ $post->user->name }}
            </h4>

            <p class="text-xs text-gray-400">
                {{ $post->created_at->diffForHumans() }}
            </p>
        </div>

    </div>

    <!-- TITLE -->
    <h3 class="font-bold text-lg mb-2">
        {{ $post->title }}
    </h3>

    <!-- CONTENT -->
    <p class="mb-4 text-gray-700">
        {{ $post->content }}
    </p>

    <!-- LIKE BUTTON -->
    <form action="{{ route('posts.like', $post->id) }}" method="POST">
        @csrf
        <button class="text-blue-600 text-sm hover:underline">
            👍 Like ({{ $post->likes->count() }})
        </button>
    </form>

    <!-- COMMENTS -->
    <div class="mt-4 space-y-1">
        @foreach($post->comments as $comment)
            <p class="text-sm text-gray-600">
                <strong>{{ $comment->user->name }}</strong>:
                {{ $comment->content }}
            </p>
        @endforeach
    </div>

    <!-- ADD COMMENT -->
    <form action="{{ route('comments.store', $post->id) }}"
          method="POST"
          class="mt-3">
        @csrf

        <input type="text"
            name="content"
            placeholder="Write a comment..."
            class="w-full border rounded-xl p-2 text-sm"
            required>
    </form>

</div>

@empty

<div class="text-center text-gray-500">
    No posts yet.
</div>

@endforelse


@endsection