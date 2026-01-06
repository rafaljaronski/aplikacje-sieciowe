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
		<h1>
			<i class="fas fa-newspaper"></i> 
		@yield('header_title', 'Artykuły')
        </h1>
		<nav class="nav-menu">
			@if(session('user_role'))
				<!-- zalogowany -->	
                <a href="{{ url('/') }}">
					<i class="fas fa-newspaper"></i> Test button
				</a>	
				<a href="{{ url('/logout') }}" class="logout-btn">
					<i class="fas fa-right-from-bracket"></i> Wyloguj
				</a>
			@else
				<!-- gosc -->
				<form method="POST" action="{{ url('/login') }}" class="login-form">
					@csrf
					<input 
						type="email" 
						name="email" 
						placeholder="Email" 
						required 
						value="{{ old('email') }}"
					>
					<input 
						type="password" 
						name="password" 
						placeholder="Hasło" 
						required
					>
					<button type="submit">
						<i class="fas fa-right-to-bracket"></i> Zaloguj
					</button>
				</form>
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
		
		<!-- content -->
		@yield('content')
	</div>
</main>

<!-- stopka -->
<footer class="footer">
	<div class="footer-content">
		@section('footer')
		<p>Projekt Aplikacje Sieciowe</p>
		@show
	</div>
</footer>

</body>
</html>
