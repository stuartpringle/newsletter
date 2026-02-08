@extends('statamic::layout')
@section('title', 'Create Newsletter Tenant')

@section('content')
<div class="card p-4">
    <form method="POST" action="{{ cp_route('newsletter.users.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1" for="name">Name</label>
            <input type="text" id="name" name="name" class="input w-full" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="slug">Slug</label>
            <input type="text" id="slug" name="slug" class="input w-full" placeholder="optional">
        </div>

        <button class="btn">Create Tenant</button>
    </form>
</div>
@endsection
