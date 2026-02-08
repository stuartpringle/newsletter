@extends('statamic::layout')
@section('title', 'Campaigns')

@section('content')
<div class="card p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold">Campaigns</h2>
        <a href="{{ cp_route('newsletter.campaigns.create') }}" class="btn">New Campaign</a>
    </div>

    <table class="table-auto w-full">
        <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Created</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($campaigns as $campaign)
            <tr>
                <td>{{ $campaign->name }}</td>
                <td>{{ ucfirst($campaign->status) }}</td>
                <td>{{ $campaign->created_at->format('Y-m-d') }}</td>
                <td class="flex gap-2">
                    <a class="btn text-xs" href="{{ cp_route('newsletter.campaigns.edit', $campaign) }}">Edit</a>
                    <form method="POST" action="{{ cp_route('newsletter.campaigns.destroy', $campaign) }}">
                        @csrf @method('DELETE')
                        <button class="btn text-xs text-red-600">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
