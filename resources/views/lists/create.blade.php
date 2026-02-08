@extends('statamic::layout')
@section('title', 'Create List')

@section('content')
<div class="card p-4">
    <form method="POST" action="{{ cp_route('newsletter.lists.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1" for="name">Name</label>
            <input type="text" id="name" name="name" class="input w-full" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="slug">Slug</label>
            <input type="text" id="slug" name="slug" class="input w-full" placeholder="optional">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="description">Description</label>
            <textarea id="description" name="description" class="input w-full" rows="3"></textarea>
        </div>

        <button class="btn">Create List</button>
    </form>
</div>
@endsection
