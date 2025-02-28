<x-layaouts.app>

    @section('content')
    <div class="max-w-3xl mx-auto bg-white shadow-md p-6 rounded-lg">
        <h2 class="text-xl font-bold mb-4">Your Notifications</h2>
    
        @forelse ($notifications as $notification)
            <div class="p-4 border-b">
                <a href="{{ route('notifications.read', $notification->id) }}" class="{{ $notification->is_read ? 'text-gray-500' : 'text-blue-600 font-bold' }}">
                    {{ $notification->message }}
                </a>
            </div>
        @empty
            <p class="text-gray-500">No notifications available.</p>
        @endforelse
    </div>
    @endsection
    

</x-layaouts.app>