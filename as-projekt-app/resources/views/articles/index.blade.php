@extends('layouts.app')

@section('header_title', 'Artykuły')

@section('content')
<div class="container">

    @if($articles->isEmpty())
        <p class="no-content">Brak artykułów do wyświetlenia.</p>
    @else
        @foreach($articles as $article)
            <div class="card">
                <div class="article-header">
                    <h2><a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a></h2>
                    <div class="article-meta">
                        <span>{{ $article->author->first_name }} {{ $article->author->last_name }}</span>
                        <span>{{ $article->created_at->format('d.m.Y') }}</span>
                        <span>{{ $article->status->display_status }}</span>
                    </div>
                </div>
                <p class="article-content">{{ Str::limit($article->content, 200) }}</p>
            </div>
        @endforeach
    @endif
</div>
@endsection
