@extends('develdashboard::layout')

@section('content')
    <header class="section-header">
        <div class="title">
            {{ setting('site-name') }} Settings
        </div>
    </header>

    <div class="section-body">
        <div class="card">
            <div class="body">
                <v-form action="{{ route('dashboard.settings.update') }}"
                    method="POST"
                    type="table"
                    :fields="{{ json_encode($form) }}"
                    :values="{{ json_encode($values) }}"
                ></v-form>
            </div>
        </div>
    </div>
@endsection
