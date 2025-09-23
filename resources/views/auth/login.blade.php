@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <h4 class="text-2xl font-semibold mb-4">Login</h4>

        @if(session('error'))
            <div class="mb-4 text-sm text-red-600">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" class="w-full border rounded px-3 py-2" />
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" />
            </div>
            <div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Log In</button>
            </div>
        </form>

        <div class="mt-4 text-center">
            <a href="#" class="text-sm text-blue-600">Forgot password?</a>
        </div>
    </div>
@endsection
