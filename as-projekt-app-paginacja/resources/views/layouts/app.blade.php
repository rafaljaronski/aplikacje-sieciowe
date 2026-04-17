<!doctype html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>@yield('title', 'Artykuły')</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/purecss@3.0.0/build/pure-min.css" integrity="sha384-X38yfunGUhNzHpBaEBsWLO+A0HDYOQi8ufWDkZ0k9e0eXz/tH3II7uKZ9msv++Ls" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">	
</head>
<body>

<div class="header">
	<div class="header-content">
		<h1><i class="fas fa-newspaper"></i>@yield('header_title', 'Artykuły')</h1>
		<nav class="nav-menu">
			@if(session('user_roles'))
				<!-- zalogowany -->
				 <p>{{ session('user_email') }}</p>
                <a href="{{ route('home') }}"><i class="fas fa-home"></i> Strona główna</a>
				@if(in_array('Autor', session('user_roles', [])) || in_array('Moderator', session('user_roles', [])))
					<a href="{{ route('articles.manage') }}"><i class="fas fa-tasks"></i> Zarządzaj</a>
				@endif
				@if(in_array('Autor', session('user_roles', [])))
					<a href="{{ route('articles.create') }}"><i class="fas fa-plus"></i> Nowy artykuł</a>
				@endif
				@if(in_array('Administrator', session('user_roles', [])))
					<a href="{{ route('users.index') }}"><i class="fas fa-users"></i> Użytkownicy</a>
				@endif
				<a href="{{ url('/logout') }}" class="logout-btn"><i class="fas fa-right-from-bracket"></i> Wyloguj</a>
			@else
				<!-- gosc -->
				<form method="POST" action="{{ url('/login') }}" class="login-form">
					@csrf
					<input 
						type="email" 
						name="email" 
						placeholder="Email" 
						required 
						value="{{ old('email') }}">
					<input 
						type="password" 
						name="password" 
						placeholder="Hasło" 
						required>
					<button type="submit"><i class="fas fa-right-to-bracket"></i> Zaloguj</button>
				</form>
				<a href="{{ route('register') }}" class="logout-btn"><i class="fas fa-user-plus"></i> Rejestracja</a>
			@endif
		</nav>
	</div>
</div>

<main class="main-content">
	<div class="container">
		<!-- messages -->
		@if(session('error'))
			<div class="messages">
				<div class="err">{{ session('error') }}</div>
			</div>
		@endif
		
		@if(session('success'))
			<div class="messages">
				<div class="inf">{{ session('success') }}</div>
			</div>
		@endif
		
		@if(session('info'))
			<div class="messages">
				<div class="res">{{ session('info') }}</div>
			</div>
		@endif
		
		<!-- bledy walidacji -->
		@if ($errors->any())
			<div class="messages">
				@foreach ($errors->all() as $error)
					<div class="err">{{ $error }}</div>
				@endforeach
			</div>
		@endif
		
		<!-- content -->
		@yield('content')
	</div>
</main>

<!-- stopka -->
<footer class="footer">
	<div class="footer-content">
		<p>Projekt Aplikacje Sieciowe</p>
	</div>
</footer>

</body>
</html>
