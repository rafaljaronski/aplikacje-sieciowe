@extends('layouts.app')

@section('header_title', 'Rejestracja')

@section('content')
<div class="container">
    <div class="card">      
        <form method="POST" action="{{ route('register.store') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    value="{{ old('email') }}" 
                    required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password">Hasło</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    required>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="first_name">Imię</label>
                <input 
                    type="text" 
                    id="first_name" 
                    name="first_name" 
                    class="form-control @error('first_name') is-invalid @enderror" 
                    value="{{ old('first_name') }}" 
                    required>
                @error('first_name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="last_name">Nazwisko</label>
                <input 
                    type="text" 
                    id="last_name" 
                    name="last_name" 
                    class="form-control @error('last_name') is-invalid @enderror" 
                    value="{{ old('last_name') }}" 
                    required>
                @error('last_name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Zarejestruj się</button>
                <a href="{{ route('home') }}" class="btn btn-secondary">Anuluj</a>
            </div>
        </form>
    </div>
</div>
@endsection
