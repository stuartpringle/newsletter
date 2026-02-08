@extends('statamic::layout')
@section('title', 'Tags')

@section('content')
<div class="card p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold">Tags</h2>
        <a href="{{ cp_route('newsletter.tags.create') }}" class="btn">New Tag</a>
    </div>

    <table class="table-auto w-full">
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tags as $tag)
            <tr>
                <td>{{ $tag->name }}</td>
                <td>{{ $tag->slug }}</td>
                <td class="flex gap-2">
                    <a class="btn text-xs" href="{{ cp_route('newsletter.tags.edit', $tag) }}">Edit</a>
                    <form method="POST" action="{{ cp_route('newsletter.tags.destroy', $tag) }}">
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
