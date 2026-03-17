@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto">

<div class="bg-white shadow rounded-lg p-6">

<div class="text-center">

<img 
src="https://ui-avatars.com/api/?name={{ $user->name }}&size=120"
class="w-28 h-28 rounded-full mx-auto mb-4"
>

<h2 class="text-2xl font-bold">
{{ $user->name }}
</h2>

<p class="text-gray-500">
{{ $user->email }}
</p>

</div>


<div class="mt-6 border-t pt-4 space-y-2">

<div class="flex justify-between">
<span class="text-gray-600">User ID</span>
<span class="font-semibold">{{ $user->id }}</span>
</div>

<div class="flex justify-between">
<span class="text-gray-600">Joined</span>
<span class="font-semibold">{{ $user->created_at->format('d M Y') }}</span>
</div>

</div>


<div class="mt-6 text-center">

<button
class="bg-blue-500 text-white px-5 py-2 rounded-lg
hover:bg-blue-600
cursor-pointer
transition"
>
Edit Profile
</button>

</div>

</div>

</div>

@endsection