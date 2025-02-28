<x-layaouts.app>
    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold mb-6">Edit Post</h1>
            <a href="{{ route('posts.show', $post->id) }}" class="text-indigo-600 hover:text-indigo-800 underline mb-4">Back</a>

            <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
            
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" id="title" name="title" required value="{{ old('title', $post->title) }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            
                <div class="mb-4">
                    <label for="content" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="content" name="description" rows="4" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 resize-none">{{ old('content', $post->content) }}</textarea>
                </div>
            
                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                    <input type="file" id="image" name="image" accept="image/*"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    
                    @if ($post->image)
                        <p class="mt-2 text-sm text-gray-500">Current image:</p>
                        <img src="{{ asset('storage/'.$post->image) }}" alt="Current Image" class="w-full h-48 object-cover rounded-lg">
                    @endif
                </div>
            
                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Post
                </button>
            </form>
            
            
        </div>
    </main>
</x-layaouts.app>
