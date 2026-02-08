
<div class="max-w-md mx-auto text-center py-16 px-4">
    <h1 class="text-2xl font-bold mb-4 text-white">Confirm Unsubscribe</h1>
    <p class="text-lg text-gray-300 mb-6">
        Are you sure you want to unsubscribe <strong>{{ $signup->email }}</strong> from the newsletter?
    </p>
    <form method="POST" action="{{ route('newsletter.unsubscribe', $signup->verification_token) }}">
        @csrf
        <button 
            type="submit" 
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-500 transition"
        >
            Yes, unsubscribe me
        </button>
    </form>
</div>
