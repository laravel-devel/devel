@extends('develdashboard::layout')

@section('content')
    <header class="section-header">
        <div class="title">
            {{ config('site.display_name') }} Settings
        </div>
    </header>

    <div class="section-body">
        <div class="card">
            <div class="body">
                <v-form action="{{ route('dashboard.site.settings.update') }}"
                    method="POST"
                    type="table"
                    :fields="{{ json_encode($form) }}"
                    :values="{{ json_encode($values) }}"
                ></v-form>
            </div>
        </div>
    </div>
@endsection
