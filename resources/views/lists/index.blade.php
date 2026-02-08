@extends('statamic::layout')
@section('title', 'Mailing Lists')

@section('content')
<div class="card p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold">Lists</h2>
        <a href="{{ cp_route('newsletter.lists.create') }}" class="btn">New List</a>
    </div>

    <table class="table-auto w-full">
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Description</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lists as $list)
            <tr>
                <td>{{ $list->name }}</td>
                <td>{{ $list->slug }}</td>
                <td>{{ $list->description }}</td>
                <td class="flex gap-2">
                    <a class="btn text-xs" href="{{ cp_route('newsletter.lists.edit', $list) }}">Edit</a>
                    <form method="POST" action="{{ cp_route('newsletter.lists.destroy', $list) }}">
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
