@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Dashboard</h3>
                    <p class="mb-1">Welcome, <strong>{{ session('username') }}</strong></p>
                    <p class="text-muted">Role: {{ session('peran') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
