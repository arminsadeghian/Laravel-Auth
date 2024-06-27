@if(session('mustVerifyEmail'))
    <div class="alert alert-danger">{{ session('mustVerifyEmail') }}</div>
@endif
