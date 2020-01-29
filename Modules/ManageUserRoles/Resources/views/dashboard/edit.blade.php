@extends('develdashboard::layout')

@section('content')
    <header class="section-header">
        <div class="title">
            {{ config('manageuserroles.display_name') }} - Edit
        </div>
    </header>

    <div class="section-body">
        <div class="card">
            <div class="body">
                @include('manageuserroles::dashboard._form')
            </div>
        </div>
    </div>
@endsection
