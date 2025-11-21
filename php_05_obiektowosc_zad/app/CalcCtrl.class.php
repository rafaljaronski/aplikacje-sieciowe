<?php
// W skrypcie definicji kontrolera nie trzeba dołączać problematycznego skryptu config.php,
// ponieważ będzie on użyty w miejscach, gdzie config.php zostanie już wywołany.

require_once $conf->root_path.'/lib/smarty/Smarty.class.php';
require_once $conf->root_path.'/lib/Messages.class.php';
require_once $conf->root_path.'/app/CalcForm.class.php';
require_once $conf->root_path.'/app/CalcResult.class.php';

/** Kontroler kalkulatora
 * @author Przemysław Kudłacik
 *
 */
class CalcCtrl {

	private $msgs;   //wiadomości dla widoku
	private $form;   //dane formularza (do obliczeń i dla widoku)
	private $result; //inne dane dla widoku
	private $hide_intro; //zmienna informująca o tym czy schować intro

	/** 
	 * Konstruktor - inicjalizacja właściwości
	 */
	public function __construct(){
		//stworzenie potrzebnych obiektów
		$this->msgs = new Messages();
		$this->form = new CalcForm();
		$this->result = new CalcResult();
		$this->hide_intro = false;
	}
	
	/** 
	 * Pobranie parametrów
	 */
	public function getParams(){
		$this->form->loan = isset($_REQUEST ['loan']) ? $_REQUEST ['loan'] : null;
		$this->form->term = isset($_REQUEST ['term']) ? $_REQUEST ['term'] : null;
		$this->form->rate = isset($_REQUEST ['rate']) ? $_REQUEST ['rate'] : null;
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
		
		$this->msgs->addInfo('Przekazano parametry.');
		
		// sprawdzenie, czy potrzebne wartości zostały przekazane
		if ($this->form->loan == "") {
			$this->msgs->addError('Nie podano kwoty kredytu');
		}
		if ($this->form->term == "") {
			$this->msgs->addError('Nie podano okresu kredytowania');
		}
		if ($this->form->rate == "") {
			$this->msgs->addError('Nie podano oprocentowania');
		}
		
		// nie ma sensu walidować dalej gdy brak parametrów
		if (! $this->msgs->isError()) {
			
			// sprawdzenie, czy parametry są liczbami
			if (! is_numeric ( $this->form->loan )) {
				$this->msgs->addError('Kwota kredytu nie jest liczbą');
			}
			
			if (! is_numeric ( $this->form->term )) {
				$this->msgs->addError('Okres kredytowania nie jest liczbą');
			}
			
			if (! is_numeric ( $this->form->rate )) {
				$this->msgs->addError('Oprocentowanie nie jest liczbą');
			}
		}
		
		// sprawdzenie poprawności wartości
		if (! $this->msgs->isError()) {
			if ($this->form->loan <= 0) {
				$this->msgs->addError('Kwota kredytu musi być większa od zera');
			}
			if ($this->form->term <= 0) {
				$this->msgs->addError('Okres kredytowania musi być większy od zera');
			}
			if ($this->form->rate < 0) {
				$this->msgs->addError('Oprocentowanie nie może być ujemne');
			}
		}
		
		return ! $this->msgs->isError();
	}
	
	/** 
	 * Pobranie wartości, walidacja, obliczenie i wyświetlenie
	 */
	public function process(){

		$this->getparams();
		
		if ($this->validate()) {
				
			//konwersja parametrów na liczby
			$this->form->loan = floatval($this->form->loan);
			$this->form->term = intval($this->form->term);
			$this->form->rate = floatval($this->form->rate);
			$this->msgs->addInfo('Parametry poprawne. Wykonuję obliczenia.');
				
			//wykonanie obliczeń kredytu
			// Obliczenie całkowitej kwoty do spłaty
			$this->result->total_amount = $this->form->loan + $this->form->loan * ($this->form->term * ($this->form->rate/100));
			
			// Obliczenie miesięcznej raty
			$this->result->monthly_payment = round($this->result->total_amount / (12 * $this->form->term), 2);
		}
		
		$this->generateView();
	}
	
	
	/**
	 * Wygenerowanie widoku
	 */
	public function generateView(){
		global $conf;
		
		$smarty = new Smarty();
		$smarty->assign('conf',$conf);
		
		$smarty->assign('page_title','Kalkulator kredytowy');
		$smarty->assign('page_description','Szablonowanie oparte na bibliotece Smarty');
		$smarty->assign('page_header','Kalkulator kredytowy - Smarty');
				
		$smarty->assign('hide_intro',$this->hide_intro);
		
		$smarty->assign('msgs',$this->msgs);
		$smarty->assign('form',$this->form);
		$smarty->assign('res',$this->result);
		
		$smarty->display($conf->root_path.'/app/CalcView.tpl');
	}
}
