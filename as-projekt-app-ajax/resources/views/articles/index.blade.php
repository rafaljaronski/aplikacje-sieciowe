@extends('layouts.app')

@section('header_title', 'Artykuły')

@push('scripts')
<script src="{{ asset('js/functions.js') }}"></script>
@endpush

@section('content')
<div class="container">
    <form id="search-form" method="GET" class="search-form"
          onsubmit="ajaxSearchForm('search-form', '{{ url()->current() }}', 'articles-list'); return false;">
        <input type="text" name="search" placeholder="Szukaj po tytule" value="{{ request('search') }}" class="search-input">

        @if(Route::currentRouteName() === 'articles.manage')
            <select name="status" class="form-control status-select">
                <option value="">Wszystkie statusy</option>
                @foreach($statuses ?? [] as $status)
                    <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>
                        {{ $status->display_status }}
                    </option>
                @endforeach
            </select>
        @endif

        <button type="submit" class="btn btn-primary">Szukaj</button>
        @if(request('search') || request('status'))
            <a href="{{ url()->current() }}" class="btn btn-secondary">Wyczyść</a>
        @endif
    </form>

    <div id="articles-list">
        @include('articles.list')
    </div>
</div>
@endsection
