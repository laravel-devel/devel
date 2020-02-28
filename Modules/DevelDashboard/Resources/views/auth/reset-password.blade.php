@extends('develdashboard::auth.layout')

@section('content')
    <div class="card auth">
        <h1 class="title">
            Reset Your Password
        </h1>

        <div class="body">
            <v-form action="{{ route('dashboard.auth.reset-password.post') }}"
                method="POST"
                :fields="{ 'Main': [
                    { type: 'email', name: 'email', label: 'Your Email' },
                    { type: 'password', name: 'password', label: 'New Password' },
                    { type: 'password', name: 'password_confirmation', label: 'Confirm Password' },
                    { type: 'hidden', name: 'token' },
                ]}"
                :values="{
                    token: '{{ $token }}',
                }"
                success="{{ route('dashboard.index') }}"></v-form>
        </div>
    </div>
@endsection