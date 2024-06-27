<x-mail::message>
    # Verify your email

    Dear {{ $name }}

    <x-mail::button :url="$url">
        Verify
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
