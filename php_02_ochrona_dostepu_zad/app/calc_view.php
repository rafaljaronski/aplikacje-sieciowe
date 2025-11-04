<?php
//Tu już nie ładujemy konfiguracji - sam widok nie będzie już punktem wejścia do aplikacji.
//Wszystkie żądania idą do kontrolera, a kontroler wywołuje skrypt widoku.
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Kalkulator kredytowy</title>
	<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
	<style>
	/* Styl dla datalist - wyświetlanie wartości pod suwakiem */
	datalist {
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		writing-mode: vertical-lr;
		width: 300px;
		font-size: 14px;
		font-weight: bold;
		margin-top: 5px;
	}
	
	option {
		padding: 0;
	}
	
	input[type="range"] {
		width: 300px;
		margin: 0;
	}
	</style>
</head>
<body>

<div style="width:90%; margin: 2em auto;">
	<a href="<?php print(_APP_ROOT); ?>/app/inna_chroniona.php" class="pure-button">kolejna chroniona strona</a>
	<a href="<?php print(_APP_ROOT); ?>/app/security/logout.php" class="pure-button pure-button-active">Wyloguj</a>
</div>

<div style="width:90%; margin: 2em auto;">

<form action="<?php print(_APP_ROOT); ?>/app/calc.php" method="post" class="pure-form pure-form-stacked">
	<legend>Kalkulator kredytowy</legend>
	<fieldset>
		<label for="id_loan">Kwota kredytu: </label>
		<input id="id_loan" type="text" name="loan" value="<?php if(isset($loan)) echo $loan; ?>" />
		<label for="id_term">Okres kredytowania (lata): </label>
		<select id="id_term" name="term">
			<option value="10" <?php if(isset($term) && $term == 10) echo 'selected'; ?>>10 lat</option>
			<option value="20" <?php if(isset($term) && $term == 20) echo 'selected'; ?>>20 lat</option>
			<option value="25" <?php if(isset($term) && $term == 25) echo 'selected'; ?>>25 lat</option>
			<option value="30" <?php if(isset($term) && $term == 30) echo 'selected'; ?>>30 lat</option>
		</select>
		<label for="id_rate">Oprocentowanie (%): </label>
		<input id="id_rate" type="range" name="rate" min="2" max="20" list="values" value="<?php if(isset($rate)) echo $rate; else echo '11'; ?>" />
		<datalist id="values">
			<?php
			for ($i = 2; $i <= 20; $i += 2) {
				echo "<option value=\"$i\" label=\"$i\"></option>";
			}
			?>
		</datalist>
	</fieldset>	
	<input type="submit" value="Oblicz ratę" class="pure-button pure-button-primary" />
</form>	

<?php
//wyświeltenie listy błędów, jeśli istnieją
if (isset($messages)) {
	if (count ( $messages ) > 0) {
		echo '<ol style="margin-top: 1em; padding: 1em 1em 1em 2em; border-radius: 0.5em; background-color: #f88; width:25em;">';
		foreach ( $messages as $key => $msg ) {
			echo '<li>'.$msg.'</li>';
		}
		echo '</ol>';
	}
}
?>

<?php if (isset($result)){ ?>
<div style="margin-top: 1em; padding: 1em; border-radius: 0.5em; background-color: #ff0; width:25em;">
<?php echo 'Miesięczna rata: '.round($result, 2).' zł'; ?>
</div>
<?php } ?>

</div>

</body>
</html>