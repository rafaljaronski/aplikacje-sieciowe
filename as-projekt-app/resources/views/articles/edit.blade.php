@extends('layouts.app')

@section('header_title', 'Edycja artykułu')

@section('content')
<div class="container">

    <form method="POST" action="{{ route('articles.update', $article) }}" class="card">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Tytuł artykułu:</label>
            <input type="text" id="title" name="title" value="{{ old('title', $article->title) }}" class="form-control" maxlength="255" required>
        </div>

        <div class="form-group">
            <label for="content">Treść artykułu:</label>
            <textarea id="content" name="content" rows="15" class="form-control" required>{{ old('content', $article->content) }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
            <a href="{{ route('articles.show', $article) }}" class="btn btn-secondary">Anuluj</a>
        </div>
    </form>
</div>
@endsection
