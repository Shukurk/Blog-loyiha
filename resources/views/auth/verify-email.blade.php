<x-layaouts.app>
    @if (Auth::user() && !Auth::user()->hasVerifiedEmail())
    <div class="bg-yellow-500 text-white p-4 rounded-md mb-4">
        Please verify your email address. A verification link has been sent to your email.
    </div>
@endif


</x-layaouts.app>