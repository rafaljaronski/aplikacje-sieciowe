<?php require_once dirname(__FILE__) .'/../config.php';?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
<meta charset="utf-8" />
<title>Kalkulator kredytowy</title>
<style>
/* https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/input/range */
datalist {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  writing-mode: vertical-lr;
  width: 200px;
}

option {
  padding: 0;
}

input[type="range"] {
  width: 200px;
  margin: 0;
}
</style>
</head>
<body>

<form action="<?php print(_APP_URL);?>/app/calc.php" method="post">
	<label for="id_loan">Kwota kredytu: </label><br />
	<input id="id_loan" type="text" name="loan" value="<?php if (isset($loan)) print($loan); ?>" /><br />
	<label for="id_term">Okres kredytowania: </label><br />
	<select name="term">
		<option value="10">10 lat</option>
		<option value="20">20 lat</option>
		<option value="25">25 lat</option>
		<option value="30">30 lat</option>
	</select><br />
	<label for="id_rate">Oprocentowanie: </label><br />
	<input id="id_rate" type="range" name="rate" min="2" max="20" list="values" value="<?php if (isset($rate)) print($rate); ?>" />
	<datalist id="values">
  		<?php
		for ($i = 2; $i <= 20; $i += 2) {
    	echo "<option value=\"$i\" label=\"$i\"></option>";
		}
		?>
	</datalist>
	<br /><input type="submit" value="Oblicz" />
</form>	

<?php
//wyświeltenie listy błędów, jeśli istnieją
if (isset($messages)) {
	if (count ( $messages ) > 0) {
		echo '<ol style="margin: 20px; padding: 10px 10px 10px 30px; border-radius: 5px; background-color: #f88; width:300px;">';
		foreach ( $messages as $key => $msg ) {
			echo '<li>'.$msg.'</li>';
		}
		echo '</ol>';
	}
}
?>

<?php if (isset($result)){ ?>
<div style="margin: 20px; padding: 10px; border-radius: 5px; background-color: #ff0; width:300px;">
<?php echo 'Wynik: '.$result; ?>
</div>
<?php } ?>

</body>
</html>