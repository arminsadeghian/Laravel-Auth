@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            @include('messages.errors')
            @include('messages.alerts')

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Two factor authentication</div>
                    <div class="card-body">

                        @if(auth()->user()->has_two_factor == 1)
                            <a href="{{ route('auth.two.factor.deactivate') }}" class="btn btn-primary">
                                Deactivate
                            </a>
                        @endif

                        @if(auth()->user()->has_two_factor == 0)
                            <a href="{{ route('auth.two.factor.activate') }}" class="btn btn-primary">
                                Activate
                            </a>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
