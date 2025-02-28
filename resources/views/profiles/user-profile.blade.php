<x-layaouts.app>
    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <div class="flex flex-col sm:flex-row items-center mb-4">
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="User Avatar"
                        class="w-20 h-20 rounded-full mr-4 mb-4 sm:mb-0">
                    <div class="text-center sm:text-left">
                        <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                        <p class="text-gray-600">{{ $user->username }}</p>
                    </div>

                    <!-- Agar bu o'z profilingiz bo'lsa, Edit Profile chiqadi -->
                    @if (Auth::check() && Auth::id() === $user->id)
                        <div class="mt-4 sm:mt-0 sm:ml-auto">
                            <a href="{{ route('profile.edit', $user->username) }}"
                                class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                                Edit Profile
                            </a>
                        </div>
                    @else
                        <!-- Agar boshqa odam profiliga kirsangiz, Follow / Unfollow chiqadi -->
                        <div class="mt-4 sm:mt-0 sm:ml-auto">
                            @if (Auth::check() && Auth::user()->isFollowing($user))
                                <form action="{{ route('unfollow', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700">
                                        Unfollow
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('follow', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
                                        Follow
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="flex flex-wrap justify-center sm:justify-start space-x-4">
                    <span class="font-semibold">{{ $user->followers()->count() }} Followers</span>
                    <span class="font-semibold">{{ $user->following()->count() }} Following</span>
                    <span class="font-semibold">{{ $user->posts()->count() }} Posts</span>
                </div>
            </div>

            <!-- User's Posts -->
            <h2 class="text-2xl font-bold mb-4">{{ $user->name }}'s Posts</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($user->posts as $post)
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <img src="{{ asset('storage/'.$post->image) }}" alt="Post Image"
                            class="w-full h-48 object-cover rounded-lg mb-4">
                        <h3 class="text-xl font-bold mb-2">{{ $post->title }}</h3>
                        <p class="text-gray-700 mb-4">{{ Str::limit($post->content, 100) }}</p>
                        <a href="{{ route('posts.show', $post->id) }}" class="text-indigo-600 hover:text-indigo-800">Read More</a>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
</x-layaouts.app>
