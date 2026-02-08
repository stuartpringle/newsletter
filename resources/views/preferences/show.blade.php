@extends('statamic::layout')
@section('title', 'Email Preferences')

@section('content')
<div class="card p-4">
    <h2 class="text-lg font-bold mb-2">Email Preferences</h2>
    <p class="mb-4">Update preferences for <strong>{{ $signup->email }}</strong>.</p>

    <form method="POST" action="{{ route('newsletter.preferences.update', $signup->verification_token) }}" class="space-y-4">
        @csrf
        <label class="flex items-center gap-2">
            <input type="checkbox" name="unsubscribe" value="1" class="input">
            <span>Unsubscribe from all emails</span>
        </label>

        <button class="btn">Save Preferences</button>
    </form>
</div>
@endsection
