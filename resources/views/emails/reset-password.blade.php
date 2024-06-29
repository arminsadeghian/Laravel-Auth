<x-mail::message>
    # Reset password

    Reset your password

    <x-mail::button :url="$link">
        Reset your password
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
