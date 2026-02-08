@extends('statamic::layout')
@section('title', 'Analytics')

@section('content')
<div class="card p-4">
    <h2 class="text-lg font-bold mb-4">Campaign Analytics</h2>

    <table class="table-auto w-full">
        <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Sent</th>
                <th>Opens</th>
                <th>Clicks</th>
                <th>Bounces</th>
                <th>Spam</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($campaigns as $c)
            <tr>
                <td>{{ $c['name'] }}</td>
                <td>{{ ucfirst($c['status']) }}</td>
                <td>{{ $c['sent'] }}</td>
                <td>{{ $c['opens'] }}</td>
                <td>{{ $c['clicks'] }}</td>
                <td>{{ $c['bounces'] }}</td>
                <td>{{ $c['spams'] }}</td>
                <td>{{ $c['created_at']?->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
