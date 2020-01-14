@extends('develdashboard::layout')

@section('content')
    <header class="section-header">
        <div class="title">
            {{ config('manageuserroles.display_name') }}
        </div>
    </header>

    <div class="section-body">
        <v-datatable base-url="{{ route('dashboard.manageuserroles.get') }}"
            :fields="{{ json_encode($fields) }}"
            :actions="{{ json_encode($actions) }}"></v-datatable>
    </div>
@endsection
