@extends('layouts.app')

@section('content')
<div class="default-content">
	@if(!session('user_role'))
		<h2 class="page-title">Strona główna</h2>
	@else
		<div class="article-card">
			<p><strong>Login:</strong> {{ session('user_email') }}</p>
			<p><strong>Rola:</strong> {{ session('user_role') }}</p>
		</div>
	@endif
</div>
@endsection
