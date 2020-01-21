@extends('develdashboard::layout')

@section('content')
    <header class="section-header">
        <div class="title">
            {{ config('manageuserroles.display_name') }} - Add
        </div>
    </header>

    <div class="section-body">
        <div class="card">
            <div class="body">
                <v-form action="{{ route('dashboard.manageuserroles.store') }}"
                    method="POST"
                    type="table"
                    :fields="{{ json_encode($form) }}"
                    success="{{ route('dashboard.manageuserroles.index') }}"></v-form>
            </div>
        </div>
    </div>
@endsection
