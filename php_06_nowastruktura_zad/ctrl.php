<?php
require_once 'init.php';
// Skrypt kontrolera głównego jako jedyny "punkt wejścia" inicjuje aplikację.

// Inicjacja ładuje konfigurację, definiuje funkcje getConf(), getMessages() oraz getSmarty(),
// pozwalające odwołać się z każdego miejsca w systemie do obiektów konfiguracji, messages i smarty.

// Ponadto ładuje skrypt funkcji pomocniczych (functions.php) oraz wczytuje parametr 'action' do zmiennej $action.
// Wystarczy już tylko podjąć decyzję co zrobić na podstawie $action.

switch ($action) {
	default : // 'calcView' - wyświetlenie formularza
	    // załaduj definicję kontrolera
		include_once getConf()->root_path.'/app/controllers/CalcCtrl.class.php';
		// utwórz obiekt i użyj
		$ctrl = new CalcCtrl ();
		$ctrl->generateView ();
	break;
	case 'calcCompute' : // obliczenia
		// załaduj definicję kontrolera
		include_once getConf()->root_path.'/app/controllers/CalcCtrl.class.php';
		// utwórz obiekt i użyj
		$ctrl = new CalcCtrl ();
		$ctrl->process ();
	break;
}
