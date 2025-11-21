<!doctype html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="{$page_description|default:'Profesjonalny kalkulator kredytowy'}">
	<title>{$page_title|default:"Kalkulator Kredytowy"}</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/purecss@3.0.0/build/pure-min.css" integrity="sha384-X38yfunGUhNzHpBaEBsWLO+A0HDYOQi8ufWDkZ0k9e0eXz/tH3II7uKZ9msv++Ls" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link rel="stylesheet" href="{$conf->app_url}/css/style.css">	
</head>
<body>

<!-- Header -->
<div class="header">
	<h1><i class="fas fa-calculator"></i> {$page_title|default:"Kalkulator Kredytowy"}</h1>
	<h2>{$page_header|default:"Kalkulator kredytowy"}</h2>
	<p>{$page_description|default:"Oblicz swoją miesięczną ratę kredytu"}</p>
</div>

<!-- Main Content -->
<main class="main-content">
	<div class="container">
		{block name=content} 
		<div class="default-content">
			<h2>Witaj w kalkulatorze kredytowym</h2>
			<p>Użyj formularza aby obliczyć miesięczną ratę kredytu.</p>
		</div>
		{/block}
	</div>
</main>

<!-- Footer -->
<footer class="footer">
	<div class="footer-content">
		{block name=footer}
		<p>Widok oparty na szablonach <a href="http://purecss.io/" target="_blank">Pure CSS</a> i <a href="https://www.smarty.net/" target="_blank">Smarty</a></p>
		{/block}
	</div>
</footer>

</body>
</html>
