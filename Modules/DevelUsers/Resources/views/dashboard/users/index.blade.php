@extends('develdashboard::layout')

@section('content')
    <header class="section-header">
        <div class="title">
            {{ config('develusers.display_name') }}
        </div>
    </header>

    <div class="section-body">
        <v-datatable base-url="{{ route('dashboard.develusers.users.get') }}"
            :fields="{{ json_encode($fields) }}"
            :actions="{{ json_encode($actions) }}"
            :permissions="{{ json_encode($permissions ?? []) }}"></v-datatable>
    </div>
@endsection
