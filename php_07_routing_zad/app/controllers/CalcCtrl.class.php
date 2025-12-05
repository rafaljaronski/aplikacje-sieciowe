<?php

namespace app\controllers;

use app\forms\CalcForm;
use app\transfer\CalcResult;

/** Kontroler kalkulatora kredytowego
 * W strukturze z namespaces nie trzeba dołączać plików - autoloader to zrobi
 */
class CalcCtrl {

	private $form;   //dane formularza (do obliczeń i dla widoku)
	private $result; //dane wyniku dla widoku
	private $hide_intro; //zmienna informująca o tym czy schować intro

	/** 
	 * Konstruktor - inicjalizacja właściwości
	 */
	public function __construct(){
		//stworzenie potrzebnych obiektów
		$this->form = new CalcForm();
		$this->result = new CalcResult();
		$this->hide_intro = false;
	}
	
	/** 
	 * Pobranie parametrów
	 */
	public function getParams(){
		$this->form->loan = getFromRequest('loan');
		$this->form->term = getFromRequest('term');
		$this->form->rate = getFromRequest('rate');
	}
	
	/** 
	 * Walidacja parametrów
	 * @return true jeśli brak błedów, false w przeciwnym wypadku 
	 */
	public function validate() {
		// sprawdzenie, czy parametry zostały przekazane
		if (! (isset ( $this->form->loan ) && isset ( $this->form->term ) && isset ( $this->form->rate ))) {
			// sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
			return false; //zakończ walidację z błędem
		} else { 
			$this->hide_intro = true; //przyszły pola formularza, więc - schowaj wstęp
		}
		
		getMessages()->addInfo('Przekazano parametry.');
		
		// sprawdzenie, czy potrzebne wartości zostały przekazane
		if ($this->form->loan == "") {
			getMessages()->addError('Nie podano kwoty kredytu');
		}
		if ($this->form->term == "") {
			getMessages()->addError('Nie podano okresu kredytowania');
		}
		if ($this->form->rate == "") {
			getMessages()->addError('Nie podano oprocentowania');
		}
		
		// nie ma sensu walidować dalej gdy brak parametrów
		if (! getMessages()->isError()) {
			
			// sprawdzenie, czy parametry są liczbami
			if (! is_numeric ( $this->form->loan )) {
				getMessages()->addError('Kwota kredytu nie jest liczbą');
			}
			
			if (! is_numeric ( $this->form->term )) {
				getMessages()->addError('Okres kredytowania nie jest liczbą');
			}
			
			if (! is_numeric ( $this->form->rate )) {
				getMessages()->addError('Oprocentowanie nie jest liczbą');
			}
		}
		
		// sprawdzenie poprawności wartości
		if (! getMessages()->isError()) {
			if ($this->form->loan <= 0) {
				getMessages()->addError('Kwota kredytu musi być większa od zera');
			}
			if ($this->form->term <= 0) {
				getMessages()->addError('Okres kredytowania musi być większy od zera');
			}
			if ($this->form->rate < 0) {
				getMessages()->addError('Oprocentowanie nie może być ujemne');
			}
		}
		
		return ! getMessages()->isError();
	}
	
	/** 
	 * Wykonanie obliczeń i wyświetlenie widoku
	 */
	public function process(){
		$this->getparams();
		
		if ($this->validate()) {
			//konwersja parametrów na liczby
			$this->form->loan = floatval($this->form->loan);
			$this->form->term = intval($this->form->term);
			$this->form->rate = floatval($this->form->rate);
			
			// Sprawdzenie ograniczeń role-based za pomocą funkcji inRole()
			// Wysoka kwota kredytu (> 500000) wymaga roli admin
			if ($this->form->loan > 500000 && !inRole('admin')) {
				getMessages()->addError('Tylko administrator może obliczyć ratę dla kredytu powyżej 500 000 zł');
			} 
			// Niskie oprocentowanie (< 5%) wymaga roli admin
			else if ($this->form->rate < 5 && !inRole('admin')) {
				getMessages()->addError('Tylko administrator może obliczyć ratę dla oprocentowania poniżej 5%');
			}
			else {
				getMessages()->addInfo('Parametry poprawne. Wykonuję obliczenia.');
					
				//wykonanie obliczeń kredytu
				// Obliczenie całkowitej kwoty do spłaty
				$this->result->total_amount = $this->form->loan + $this->form->loan * ($this->form->term * ($this->form->rate/100));
				
				// Obliczenie miesięcznej raty
				$this->result->monthly_payment = round($this->result->total_amount / (12 * $this->form->term), 2);
			}
		}
		
		$this->generateView();
	}
	
	/**
	 * Wygenerowanie widoku (bez obliczeń)
	 */
	public function generateView(){
		//pobierz Smarty z gettera
		$smarty = getSmarty();
		
		// Pobierz użytkownika z sesji dla widoku
		$user = isset($_SESSION['user']) ? unserialize($_SESSION['user']) : null;
		
		$smarty->assign('page_title','Kalkulator kredytowy');
		$smarty->assign('page_description','Szablonowanie oparte na bibliotece Smarty');
		$smarty->assign('page_header','Kalkulator kredytowy - Smarty');
		
		//używamy już przypisanej przez init.php konfiguracji i messages		
		$smarty->assign('user',$user);
		$smarty->assign('form',$this->form);
		$smarty->assign('res',$this->result);
		$smarty->assign('hide_intro',$this->hide_intro);
		
		$smarty->display('CalcView.tpl');
	}
}
