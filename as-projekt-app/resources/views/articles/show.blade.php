@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <h1 class="article-title">{{ $article->title }}</h1>

        <div class="article-meta">
            <div class="meta-item">
                <strong>Status:</strong> {{ $article->status->display_status }}
            </div>
            <div class="meta-item">
                <strong>Autor:</strong> {{ $article->author->first_name }} {{ $article->author->last_name }}
            </div>
            <div class="meta-item">
                <strong>Data utworzenia:</strong> {{ $article->created_at->format('d.m.Y H:i') }}
            </div>
            @if($article->updated_at != $article->created_at)
                <div class="meta-item">
                    <strong>Ostatnia edycja:</strong> {{ $article->updated_at->format('d.m.Y H:i') }}
                </div>
            @endif
        </div>

        <div class="article-content">
            {!! $article->content !!}
        </div>

        @if($article->status->name === 'rejected')
            <div class="review-info">
                <h3>Informacje o odrzuceniu</h3>
                <div class="meta-item">
                    <strong>Moderator:</strong> {{ $article->reviewer->first_name }} {{ $article->reviewer->last_name }}
                </div>
                <div class="meta-item">
                    <strong>Data odrzucenia:</strong> {{ $article->reviewed_at->format('d.m.Y H:i') }}
                </div>
                <div class="meta-item">
                    <strong>Powód odrzucenia:</strong> {{ $article->rejection_reason }}
                </div>
            </div>
        @endif

        <div class="article-actions">
            <a href="{{ route('articles.index') }}" class="btn btn-secondary">Powrót do listy</a>

            @if(session('user_id'))
                <!-- akcje autor -->
                @if($article->author_id === session('user_id'))
                    @if(in_array($article->status->name, ['draft', 'rejected']))
                        <a href="{{ route('articles.edit', $article) }}" class="btn btn-primary">Edytuj</a>
                    @endif
                    
                    @if($article->status->name === 'draft')
                        <form method="POST" action="{{ route('articles.submit', $article) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary">Wyślij do moderacji</button>
                        </form>
                    @endif
                @endif

                <!-- akcje moderator -->
                @if(in_array('Moderator', session('user_roles', [])) && $article->status->name === 'pending')
                    <form method="POST" action="{{ route('articles.approve', $article) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary">Zatwierdź</button>
                    </form>
                    
                    <button type="button" class="btn btn-danger" onclick="showRejectForm()">Odrzuć</button>
                    
                    <div id="rejectForm" style="display: none; margin-top: 20px;">
                        <form method="POST" action="{{ route('articles.reject', $article) }}">
                            @csrf
                            <div class="form-group">
                                <label for="rejection_reason">Powód odrzucenia:</label>
                                <textarea id="rejection_reason" name="rejection_reason" 
                                          rows="4" class="form-control" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger">Potwierdź odrzucenie</button>
                            <button type="button" class="btn btn-secondary" onclick="hideRejectForm()">Anuluj</button>
                        </form>
                    </div>
                @endif

                <!-- usuwanie dla autora i moderatora -->
                @if(
                    ($article->author_id === session('user_id') && in_array($article->status->name, ['draft', 'rejected']))
                    || in_array('Moderator', session('user_roles', []))
                )
                    <form method="POST" action="{{ route('articles.destroy', $article) }}" 
                          style="display: inline;"
                          onsubmit="return confirm('Czy na pewno chcesz usunąć ten artykuł?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Usuń</button>
                    </form>
                @endif
            @endif
        </div>
    </div>
</div>

<script>
function showRejectForm() {
    document.getElementById('rejectForm').style.display = 'block';
}

function hideRejectForm() {
    document.getElementById('rejectForm').style.display = 'none';
}
</script>
@endsection
