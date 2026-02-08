<!-- resources/views/widgets/mailing_list_signups.blade.php -->

<div class="card p-4">
    <h2 class="text-lg font-bold mb-2">Recent Newsletter Signups</h2>
    <table class="table-auto w-full">
        <thead>
            <tr>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Signed Up</th>
                <th class="px-4 py-2">Confirmed</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($signups as $signup)
                <tr>
                    <td class="border px-4 py-2">{{ $signup->email }}</td>
                    <td class="border px-4 py-2">{{ ucfirst($signup->status) }}</td>
                    <td class="border px-4 py-2">{{ $signup->created_at->format('Y-m-d') }}</td>
                    <td class="border px-4 py-2">{{ $signup->confirmed_at ? $signup->confirmed_at->format('Y-m-d') : '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br />
    <div>
        <a href="/cp/newsletter">View all</a>
    </div>
</div>
