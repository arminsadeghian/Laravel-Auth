<x-mail::message>
    # Login With Magic Link

    <x-mail::button :url="$link">
        Login
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
