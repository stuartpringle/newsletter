@extends('statamic::layout')
@section('title', 'Manage Newsletter Users')

@section('content')
<div class="card p-4 mb-6">
    <form method="POST" action="{{ cp_route('newsletter.users.update', $tenant) }}" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1" for="name">Name</label>
            <input type="text" id="name" name="name" class="input w-full" value="{{ $tenant->name }}" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="slug">Slug</label>
            <input type="text" id="slug" name="slug" class="input w-full" value="{{ $tenant->slug }}">
        </div>

        <button class="btn">Save Tenant</button>
    </form>
</div>

<div class="card p-4">
    <h2 class="text-lg font-bold mb-4">Members</h2>

    <form method="POST" action="{{ cp_route('newsletter.users.members.store', $tenant) }}" class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-6">
        @csrf
        <select name="user_id" class="input">
            @foreach ($users as $user)
                <option value="{{ $user->id() }}">{{ $user->email() }}</option>
            @endforeach
        </select>
        <select name="role" class="input">
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
        <button class="btn">Add Member</button>
    </form>

    <table class="table-auto w-full">
        <thead>
            <tr>
                <th>User</th>
                <th>Role</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($members as $member)
            @php($user = $users->firstWhere(fn($u) => (string) $u->id() === (string) $member->user_id))
            <tr>
                <td>{{ $user?->email() ?? $member->user_id }}</td>
                <td>
                    <form method="POST" action="{{ cp_route('newsletter.users.members.update', [$tenant, $member]) }}">
                        @csrf @method('PUT')
                        <select name="role" onchange="this.form.submit()" class="input">
                            <option value="admin" @selected($member->role === 'admin')>Admin</option>
                            <option value="user" @selected($member->role === 'user')>User</option>
                        </select>
                    </form>
                </td>
                <td>
                    <form method="POST" action="{{ cp_route('newsletter.users.members.destroy', [$tenant, $member]) }}">
                        @csrf @method('DELETE')
                        <button class="btn text-xs text-red-600">Remove</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
