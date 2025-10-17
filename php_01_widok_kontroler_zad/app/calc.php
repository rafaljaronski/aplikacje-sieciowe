<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';

// W kontrolerze niczego nie wysyła się do klienta.
// Wysłaniem odpowiedzi zajmie się odpowiedni widok.
// Parametry do widoku przekazujemy przez zmienne.

// 1. pobranie parametrów

$loan = $_REQUEST ['loan'];
$term = $_REQUEST ['term'];
$rate = $_REQUEST ['rate'];

// 2. walidacja parametrów z przygotowaniem zmiennych dla widoku

// sprawdzenie, czy parametry zostały przekazane
if ( ! (isset($loan) && isset($term) && isset($rate))) {
	//sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
	$messages [] = 'Błędne wywołanie aplikacji. Brak jednego z parametrów.';
}

// sprawdzenie, czy potrzebne wartości zostały przekazane
if ( $loan == "") {
	$messages [] = 'Nie podano kwoty kredytu';
}
//nie ma sensu walidować dalej gdy brak parametrów
if (empty( $messages )) {
	
	if (! is_numeric( $loan )) {
		$messages [] = 'Kwota nie jest liczbą';
	}
}

if (empty( $messages )) {
	$loan = intval($loan);
	if ($loan <= 0) {
		$messages [] = 'Kwota nie może być mniejsza od 0';
	}
}

// 3. wykonaj zadanie jeśli wszystko w porządku

if (empty ( $messages )) { // gdy brak błędów
	
	//konwersja parametrów na int
	$term = intval($term);
	$rate = intval($rate);
	
	//wykonanie operacji
	$result = $loan + $loan * ($term * ($rate/100));
	$result = $result / (12 * $term);
}

// 4. Wywołanie widoku z przekazaniem zmiennych
// - zainicjowane zmienne ($messages,$x,$y,$operation,$result)
//   będą dostępne w dołączonym skrypcie
include 'calc_view.php';