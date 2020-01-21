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
                <v-form action="{{ route('dashboard.manageuserroles.update', $item) }}"
                    method="POST"
                    type="table"
                    :fields="{{ json_encode($form) }}"
                    success="{{ route('dashboard.manageuserroles.index') }}"
                    :values="{{ $item }}"></v-form>
            </div>
        </div>
    </div>
@endsection
