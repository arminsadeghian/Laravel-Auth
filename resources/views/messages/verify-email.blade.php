@if(session('mustVerifyEmail'))
    <div class="alert alert-danger">{{ session('mustVerifyEmail') }}
        <a style="text-decoration: none;" href="{{ route('auth.email.send.verification') }}">
            Send Email
        </a>
    </div>
@endif
