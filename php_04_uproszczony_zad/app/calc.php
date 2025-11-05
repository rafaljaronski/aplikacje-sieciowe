<?php
// KONTROLER strony kalkulatora kredytowego
require_once dirname(__FILE__).'/../config.php';
//załaduj Smarty
require_once _ROOT_PATH.'/lib/smarty/libs/Smarty.class.php';

use Smarty\Smarty;

//pobranie parametrów
function getParams(&$form){
	$form['loan'] = isset($_REQUEST['loan']) ? $_REQUEST['loan'] : null;
	$form['term'] = isset($_REQUEST['term']) ? $_REQUEST['term'] : null;
	$form['rate'] = isset($_REQUEST['rate']) ? $_REQUEST['rate'] : null;	
}

//walidacja parametrów z przygotowaniem zmiennych dla widoku
function validate(&$form,&$infos,&$msgs,&$hide_intro){

	//sprawdzenie, czy parametry zostały przekazane - jeśli nie to zakończ walidację
	if ( ! (isset($form['loan']) && isset($form['term']) && isset($form['rate']) ))	return false;	
	
	//parametry przekazane zatem
	//nie pokazuj wstępu strony gdy tryb obliczeń (aby nie trzeba było przesuwać)
	// - ta zmienna zostanie użyta w widoku aby nie wyświetlać całego bloku intro z tłem 
	$hide_intro = true;

	$infos [] = 'Przekazano parametry.';

	// sprawdzenie, czy potrzebne wartości zostały przekazane
	if ( $form['loan'] == "") $msgs [] = 'Nie podano kwoty kredytu';
	
	//nie ma sensu walidować dalej gdy brak parametrów
	if ( count($msgs)==0 ) {
		// sprawdzenie, czy kwota jest liczbą
		if (! is_numeric( $form['loan'] )) $msgs [] = 'Kwota nie jest liczbą';
	}
	
	//konwersja parametrów na liczby
	if ( count($msgs)==0 ) {
		$form['loan'] = intval($form['loan']);
		$form['term'] = intval($form['term']);
		$form['rate'] = intval($form['rate']);
		
		// sprawdzenie, czy kwota jest dodatnia
		if ($form['loan'] <= 0) $msgs [] = 'Kwota nie może być mniejsza lub równa 0';
	}
	
	if (count($msgs)>0) return false;
	else return true;
}
	
// wykonaj obliczenia
function process(&$form,&$infos,&$msgs,&$result){
	$infos [] = 'Parametry poprawne. Wykonuję obliczenia.';
	
	//wykonanie obliczeń - formuła kalkulatora kredytowego
	$result = $form['loan'] + $form['loan'] * ($form['term'] * ($form['rate']/100));
	$result = $result / (12 * $form['term']); // Miesięczna rata
	$result = round($result, 2); // Zaokrąglenie do 2 miejsc po przecinku
}

//inicjacja zmiennych
$form = null;
$infos = array();
$messages = array();
$result = null;
$hide_intro = false;
	
getParams($form);
if ( validate($form,$infos,$messages,$hide_intro) ){
	process($form,$infos,$messages,$result);
}

// 4. Przygotowanie danych dla szablonu

$smarty = new Smarty();

$smarty->assign('app_url',_APP_URL);
$smarty->assign('root_path',_ROOT_PATH);
$smarty->assign('page_title','Kalkulator kredytowy');
$smarty->assign('page_description','Szablonowanie oparte na bibliotece Smarty');
$smarty->assign('page_header','Kalkulator kredytowy - Smarty');
$smarty->assign('hide_intro',$hide_intro);

//pozostałe zmienne niekoniecznie muszą istnieć, dlatego sprawdzamy aby nie otrzymać ostrzeżenia
$smarty->assign('form',$form);
$smarty->assign('result',$result);
$smarty->assign('messages',$messages);
$smarty->assign('infos',$infos);

// 5. Wywołanie szablonu
$smarty->display(_ROOT_PATH.'/app/calc.tpl');