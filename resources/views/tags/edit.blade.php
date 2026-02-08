@extends('statamic::layout')
@section('title', 'Edit Tag')

@section('content')
<div class="card p-4">
    <form method="POST" action="{{ cp_route('newsletter.tags.update', $tag) }}" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1" for="name">Name</label>
            <input type="text" id="name" name="name" class="input w-full" value="{{ $tag->name }}" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="slug">Slug</label>
            <input type="text" id="slug" name="slug" class="input w-full" value="{{ $tag->slug }}">
        </div>

        <button class="btn">Save Changes</button>
    </form>
</div>
@endsection
