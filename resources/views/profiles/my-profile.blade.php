<x-layaouts.app>
    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <div class="flex flex-col sm:flex-row items-center mb-4">
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="User Avatar"
                        class="w-20 h-20 rounded-full mr-4 mb-4 sm:mb-0">
                    <div class="text-center sm:text-left">
                        <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                        <p class="text-gray-600">{{ $user->username }}</p>
                    </div>

                    @if (auth()->check() && auth()->id() === $user->id)
                        <div class="mt-4 sm:mt-0 sm:ml-auto">
                            <a href="{{ route('profile.edit', $user->username) }}"
                                class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
                                Edit Profile
                            </a>
                        </div>
                    @endif
                </div>

                <div class="flex flex-wrap justify-center sm:justify-start space-x-4">
                    <span class="font-semibold">{{ $user->followers_count ?? 0 }} Followers</span>
                    <span class="font-semibold">{{ $user->following_count ?? 0 }} Following</span>
                    <span class="font-semibold">{{ $user->posts_count ?? 0 }} Posts</span>                    
                </div>
            </div>

            <h2 class="text-2xl font-bold mb-4">My Posts</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($user->posts as $post)
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <img src="{{ asset('storage/'.$post->image) }}" alt="Post Image" class="w-full h-48 object-cover rounded-lg mb-4">                                                  
                        <h3 class="text-xl font-bold mb-2">{{ $post->title }}</h3>
                        <p class="text-gray-700 mb-4">{{ Str::limit($post->body, 100) }}</p>
                        <div class="flex space-x-2">
                            <a href="{{ route('posts.show', $post->id) }}" class="text-indigo-600 hover:text-indigo-800">Read More</a>
                            @if (auth()->id() === $user->id)
                                <a href="{{ route('posts.edit', $post->id) }}" class="text-green-600 hover:text-green-800">Edit</a>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
</x-layaouts.app>
