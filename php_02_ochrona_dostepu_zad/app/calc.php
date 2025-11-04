<?php
require_once dirname(__FILE__).'/../config.php';

// KONTROLER strony kalkulatora kredytowego

// W kontrolerze niczego nie wysyła się do klienta.
// Wysłaniem odpowiedzi zajmie się odpowiedni widok.
// Parametry do widoku przekazujemy przez zmienne.

//ochrona kontrolera - poniższy skrypt przerwie przetwarzanie w tym punkcie gdy użytkownik jest niezalogowany
include _ROOT_PATH.'/app/security/check.php';

//pobranie parametrów
function getParams(&$loan,&$term,&$rate){
	$loan = isset($_REQUEST['loan']) ? $_REQUEST['loan'] : null;
	$term = isset($_REQUEST['term']) ? $_REQUEST['term'] : null;
	$rate = isset($_REQUEST['rate']) ? $_REQUEST['rate'] : null;	
}

//konwersja parametrów na int
function convertParams(&$loan,&$term,&$rate){
	$loan = intval($loan);
	$term = intval($term);
	$rate = intval($rate);
}

//walidacja parametrów z przygotowaniem zmiennych dla widoku
function validate(&$loan,&$term,&$rate,&$messages){
	// sprawdzenie, czy parametry zostały przekazane
	if ( ! (isset($loan) && isset($term) && isset($rate))) {
		// sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
		// teraz zakładamy, ze nie jest to błąd. Po prostu nie wykonamy obliczeń
		return false;
	}

	// sprawdzenie, czy potrzebne wartości zostały przekazane
	if ( $loan == "") {
		$messages [] = 'Nie podano kwoty kredytu';
	}

	//nie ma sensu walidować dalej gdy brak parametrów
	if (count ( $messages ) != 0) return false;
	
	// sprawdzenie, czy kwota jest liczbą
	if (! is_numeric( $loan )) {
		$messages [] = 'Kwota nie jest liczbą';
	}	

	if (count ( $messages ) != 0) return false;
	
	// konwersja parametrów na int
	convertParams($loan, $term, $rate);
	
	// sprawdzenie, czy kwota jest dodatnia
	if ($loan <= 0) {
		$messages [] = 'Kwota nie może być mniejsza lub równa 0';
	}

	if (count ( $messages ) != 0) return false;
	else return true;
}

function process(&$loan,&$term,&$rate,&$messages,&$result){
	global $role;
	
	//sprawdzenie ograniczeń role-based
	// Wysoka kwota kredytu (> 500000) wymaga roli manager
	if ($loan > 500000 && $role != 'manager') {
		$messages [] = 'Tylko manager banku może obliczyć ratę dla kredytu powyżej 500 000 zł';
		return;
	}
	
	// Niskie oprocentowanie (< 5%) wymaga roli manager
	if ($rate < 5 && $role != 'manager') {
		$messages [] = 'Tylko manager banku może obliczyć ratę dla oprocentowania poniżej 5%';
		return;
	}
	
	//wykonanie obliczeń - formuła kalkulatora kredytowego
	$result = $loan + $loan * ($term * ($rate/100));
	$result = $result / (12 * $term); // Miesięczna rata
}

//definicja zmiennych kontrolera
$loan = null;
$term = null;
$rate = null;
$result = null;
$messages = array();

//pobierz parametry i wykonaj zadanie jeśli wszystko w porządku
getParams($loan,$term,$rate);
if ( validate($loan,$term,$rate,$messages) ) { // gdy brak błędów
	process($loan,$term,$rate,$messages,$result);
}

// Wywołanie widoku z przekazaniem zmiennych
// - zainicjowane zmienne ($messages,$loan,$term,$rate,$result)
//   będą dostępne w dołączonym skrypcie
include 'calc_view.php';