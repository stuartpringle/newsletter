@extends('statamic::layout')
@section('title', 'Edit Segment')

@section('content')
<div class="card p-4">
    <form method="POST" action="{{ cp_route('newsletter.segments.update', $segment) }}" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1" for="name">Name</label>
            <input type="text" id="name" name="name" class="input w-full" value="{{ $segment->name }}" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="description">Description</label>
            <textarea id="description" name="description" class="input w-full" rows="3">{{ $segment->description }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Rules</label>
            @php($rules = $segment->rules ?? collect())
            @if ($rules->isEmpty())
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                    <input type="text" name="rules[0][field]" class="input" placeholder="field (e.g. status)">
                    <input type="text" name="rules[0][operator]" class="input" placeholder="operator (e.g. =)">
                    <input type="text" name="rules[0][value]" class="input" placeholder="value">
                </div>
            @else
                @foreach ($rules as $i => $rule)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-2">
                        <input type="text" name="rules[{{ $i }}][field]" class="input" value="{{ $rule->field }}">
                        <input type="text" name="rules[{{ $i }}][operator]" class="input" value="{{ $rule->operator }}">
                        <input type="text" name="rules[{{ $i }}][value]" class="input" value="{{ $rule->value }}">
                    </div>
                @endforeach
            @endif
        </div>

        <button class="btn">Save Changes</button>
    </form>
</div>
@endsection
