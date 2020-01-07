@extends('dashboard.auth.layout')

@section('content')
    <div class="card auth">
        <h1 class="title">
            Login
        </h1>

        <div class="body">
            <v-form action="{{ route('dashboard.auth.login.post') }}"
                method="POST"
                :fields="[
                    { type: 'email', name: 'email', label: 'E-mail' },
                    { type: 'password', name: 'password', label: 'Password' },
                    { type: 'checkbox', name: 'remember', label: 'Remember Me' },
                ]"
                :button="{
                    text: 'Log in'
                }"
                success="{{ route('dashboard.index') }}"></v-form>
        </div>
    </div>
@endsection