@extends('layouts.app')

@section('header_title', 'Nowy artykuł')

@section('content')
<div class="container">

    <form method="POST" action="{{ route('articles.store') }}" class="card">
        @csrf
        
        <div class="form-group">
            <label for="title">Tytuł artykułu:</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" 
                   class="form-control" maxlength="255" required>
        </div>

        <div class="form-group">
            <label for="content">Treść artykułu:</label>
            <textarea id="content" name="content" rows="15" 
                      class="form-control" required>{{ old('content') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" name="action" value="draft" class="btn btn-secondary">Zapisz jako wersję roboczą</button>
            <button type="submit" name="action" value="pending" class="btn btn-primary">Wyślij do moderacji</button>
            <a href="{{ route('articles.index') }}" class="btn btn-secondary">Anuluj</a>
        </div>
    </form>
</div>
@endsection
