@extends('statamic::layout')
@section('title', 'Newsletter Users')

@section('content')
<div class="card p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold">Newsletter Users</h2>
        <a href="{{ cp_route('newsletter.users.create') }}" class="btn">New Tenant</a>
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
            @foreach ($tenants as $tenant)
            <tr>
                <td>{{ $tenant->name }}</td>
                <td>{{ $tenant->slug }}</td>
                <td class="flex gap-2">
                    <a class="btn text-xs" href="{{ cp_route('newsletter.users.edit', $tenant) }}">Manage</a>
                    <form method="POST" action="{{ cp_route('newsletter.users.destroy', $tenant) }}">
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
