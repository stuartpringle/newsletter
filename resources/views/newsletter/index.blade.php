@extends('statamic::layout')
@section('title', 'Newsletter Subscribers')

@section('content')
<div class="card p-4">
    <form method="GET" class="mb-4">
        <input name="search" value="{{ request('search') }}" placeholder="Search by email..." class="input w-full" />
    </form>

    <table class="table-auto w-full">
        <thead>
            <tr>
                <th>Email</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subscribers as $subscriber)
            <tr>
                <td>{{ $subscriber->email }}</td>
                <td>
                    <form method="POST" action="{{ cp_route('newsletter.status', $subscriber) }}">
                        @csrf
                        <select name="status" onchange="this.form.submit()" class="input">
                            @foreach (['unconfirmed', 'subscribed', 'unsubscribed'] as $status)
                                <option value="{{ $status }}" @selected($subscriber->status == $status)>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </td>
                <td>{{ $subscriber->created_at->format('Y-m-d') }}</td>
                <td class="flex gap-2">
                    <form method="POST" action="{{ cp_route('newsletter.resend', $subscriber) }}">
                        @csrf
                        <button class="btn text-xs">Resend</button>
                    </form>
                    <form method="POST" action="{{ cp_route('newsletter.destroy', $subscriber) }}">
                        @csrf @method('DELETE')
                        <button class="btn text-xs text-red-600">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $subscribers->links() }}
</div>

<div class="card p-4 mt-6">
    <h2>Add New Subscriber</h2>
    <form method="POST" action="{{ cp_route('newsletter.store') }}" class="mt-2 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1" for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="Email..." class="input w-full" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1" for="status">Status</label>
                <select name="status" id="status" class="input w-full">
                    @foreach (['unconfirmed', 'subscribed', 'unsubscribed'] as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2 mt-6 md:mt-0">
                <input type="checkbox" name="send_email" id="send_email" class="input">
                <label for="send_email" class="text-sm">Send Confirmation Email</label>
            </div>
        </div>

        <button class="btn mt-4">Add Subscriber</button>
    </form>

</div>
@endsection
