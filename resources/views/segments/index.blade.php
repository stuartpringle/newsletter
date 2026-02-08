@extends('statamic::layout')
@section('title', 'Segments')

@section('content')
<div class="card p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold">Segments</h2>
        <a href="{{ cp_route('newsletter.segments.create') }}" class="btn">New Segment</a>
    </div>

    <table class="table-auto w-full">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($segments as $segment)
            <tr>
                <td>{{ $segment->name }}</td>
                <td>{{ $segment->description }}</td>
                <td class="flex gap-2">
                    <a class="btn text-xs" href="{{ cp_route('newsletter.segments.edit', $segment) }}">Edit</a>
                    <form method="POST" action="{{ cp_route('newsletter.segments.destroy', $segment) }}">
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
