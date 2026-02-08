@extends('statamic::layout')
@section('title', 'Email Templates')

@section('content')
<div class="card p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold">Templates</h2>
        <a href="{{ cp_route('newsletter.templates.create') }}" class="btn">New Template</a>
    </div>

    <table class="table-auto w-full">
        <thead>
            <tr>
                <th>Name</th>
                <th>Subject</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($templates as $template)
            <tr>
                <td>{{ $template->name }}</td>
                <td>{{ $template->subject }}</td>
                <td class="flex gap-2">
                    <a class="btn text-xs" href="{{ cp_route('newsletter.templates.edit', $template) }}">Edit</a>
                    <form method="POST" action="{{ cp_route('newsletter.templates.destroy', $template) }}">
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
