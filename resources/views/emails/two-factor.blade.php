<x-mail::message>
    # Two Factor

    Your two-factor code is: {{ $code }}

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
