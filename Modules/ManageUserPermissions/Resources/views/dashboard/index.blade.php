@extends('develdashboard::layout')

@section('content')
    <header class="section-header">
        <div class="title">
            {{ config('manageuserpermissions.display_name') }}
        </div>
    </header>

    <div class="section-body">
        <v-datatable base-url="{{ route('dashboard.manageuserpermissions.get') }}"
            :fields="{{ json_encode($fields) }}"></v-datatable>
    </div>
@endsection
