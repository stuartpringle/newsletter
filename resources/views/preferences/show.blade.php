@extends('statamic::layout')
@section('title', 'Email Preferences')

@section('content')
<div class="card p-4">
    <h2 class="text-lg font-bold mb-2">Email Preferences</h2>
    <p class="mb-4">Update preferences for <strong>{{ $signup->email }}</strong>.</p>

    <form method="POST" action="{{ route('newsletter.preferences.update', $signup->verification_token) }}" class="space-y-4">
        @csrf
        <div>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="unsubscribe_all" value="1" class="input">
                <span>Unsubscribe from all emails</span>
            </label>
        </div>

        <div>
            <h3 class="font-semibold mb-2">Lists</h3>
            <div class="space-y-2">
                @foreach ($signups as $s)
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="lists[]" value="{{ $s->list_id }}" class="input" @checked($s->status === 'subscribed')>
                        <span>{{ $s->list?->name ?? 'List' }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <button class="btn">Save Preferences</button>
    </form>
</div>
@endsection
