@extends('statamic::layout')
@section('title', 'Create Segment')

@section('content')
<div class="card p-4">
    <form method="POST" action="{{ cp_route('newsletter.segments.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1" for="name">Name</label>
            <input type="text" id="name" name="name" class="input w-full" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="description">Description</label>
            <textarea id="description" name="description" class="input w-full" rows="3"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Rules</label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                <input type="text" name="rules[0][field]" class="input" placeholder="field (e.g. status)">
                <input type="text" name="rules[0][operator]" class="input" placeholder="operator (e.g. =)">
                <input type="text" name="rules[0][value]" class="input" placeholder="value">
            </div>
        </div>

        <button class="btn">Create Segment</button>
    </form>
</div>
@endsection
