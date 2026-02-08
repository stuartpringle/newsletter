@extends('statamic::layout')
@section('title', 'Create Template')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/trix@2.1.8/dist/trix.css">
<script src="https://unpkg.com/trix@2.1.8/dist/trix.umd.min.js"></script>

<div class="card p-4">
    <form method="POST" action="{{ cp_route('newsletter.templates.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1" for="name">Name</label>
            <input type="text" id="name" name="name" class="input w-full" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1" for="subject">Default Subject</label>
                <input type="text" id="subject" name="subject" class="input w-full">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="from_name">From Name</label>
                <input type="text" id="from_name" name="from_name" class="input w-full">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="from_email">From Email</label>
                <input type="email" id="from_email" name="from_email" class="input w-full">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2" for="html">Content</label>
            <p class="text-sm text-gray-500 mb-2">Upload images in the Newsletter asset container, then paste their URL.</p>
            <input id="html" type="hidden" name="html" value="{{ old('html') }}">
            <trix-editor input="html"></trix-editor>
        </div>

        <button class="btn">Create Template</button>
    </form>
</div>
@endsection
