@extends('develdashboard::auth.layout')

@section('content')
    <div class="card auth">
        <h1 class="title">
            Forgot Password
        </h1>

        <div class="body">
            <v-form action="{{ route('dashboard.auth.forgot-password.post') }}"
                method="POST"
                :fields="{ 'Main': [
                    { type: 'email', name: 'email', label: 'E-mail' },
                ]}"
                :button="{
                    text: 'Send Reset Link'
                }"></v-form>
        </div>
    </div>
@endsection