@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                @include('messages.errors')
                @include('messages.alerts')

                <div class="card">
                    <div class="card-header">Enter Code</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('auth.login.code') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="code"
                                       class="col-md-4 col-form-label text-md-end">Code</label>

                                <div class="col-md-6">
                                    <input id="code" type="number"
                                           class="form-control" name="code"
                                           value="{{ old('code') }}">
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button class="btn btn-primary" type="submit">Login</button>
                                    <a href="{{ route('auth.two.factor.resend') }}">Resend Code</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
