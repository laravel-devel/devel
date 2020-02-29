@extends('develdashboard::layout')

@section('content')
    <header class="section-header">
        <div class="title">
            Manage Modules
        </div>
    </header>

    <div class="section-body">
        <v-pages-modules :modules="{{ json_encode($modules) }}"
            base-url="{{ route('dashboard.modules.toggle-enabled', '') }}"></v-pages-modules>
    </div>
@endsection