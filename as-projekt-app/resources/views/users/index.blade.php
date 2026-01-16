@extends('layouts.app')

@section('header_title', 'Zarządzanie użytkownikami')

@section('content')
<div class="container">
    <h2>Użytkownicy systemu</h2>
    
    @if($users->isEmpty())
        <p class="no-content">Brak użytkowników w systemie.</p>
    @else
        <div class="users-list">
            @foreach($users as $user)
                <div class="card user-card">
                    <div class="user-info">
                        <h3>{{ $user->first_name }} {{ $user->last_name }}</h3>
                        <p class="user-email">{{ $user->email }}</p>
                        <p><strong>Aktualne role:</strong> {{ $user->roles->pluck('name')->join(', ') }}</p>
                    </div>
                    
                    <form method="POST" action="{{ route('users.updateRoles', $user) }}" class="roles-form">
                        @csrf
                        <div class="roles-checkboxes">
                            @foreach($availableRoles as $role)
                                <label class="role-checkbox">
                                    <input 
                                        type="checkbox" 
                                        name="roles[]" 
                                        value="{{ $role->id }}"
                                        {{ $user->roles->contains('id', $role->id) ? 'checked' : '' }}>
                                    {{ $role->name }}
                                </label>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
