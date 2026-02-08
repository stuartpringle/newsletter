@extends('statamic::layout')
@section('title', 'Edit List')

@section('content')
<div class="card p-4">
    <form method="POST" action="{{ cp_route('newsletter.lists.update', $list) }}" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1" for="name">Name</label>
            <input type="text" id="name" name="name" class="input w-full" value="{{ $list->name }}" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="slug">Slug</label>
            <input type="text" id="slug" name="slug" class="input w-full" value="{{ $list->slug }}">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="description">Description</label>
            <textarea id="description" name="description" class="input w-full" rows="3">{{ $list->description }}</textarea>
        </div>

        <button class="btn">Save Changes</button>
    </form>
</div>
@endsection
