<?php
require_once 'init.php';
// Skrypt kontrolera głównego jako jedyny "punkt wejścia" inicjuje aplikację.

// Inicjacja ładuje konfigurację, definiuje funkcje getConf(), getMessages() oraz getSmarty(),
// pozwalające odwołać się z każdego miejsca w systemie do obiektów konfiguracji, messages i smarty.

// Ponadto ładuje skrypt funkcji pomocniczych (functions.php) oraz wczytuje parametr 'action' do zmiennej $action.
// Wystarczy już tylko podjąć decyzję co zrobić na podstawie $action.

// Nowością jest ClassLoader oraz automatyczne ładowanie klas na podstawie przestrzeni nazw.
// Przestrzenie nazw odpowiadają strukturze folderów (np. app\controllers\ -> app/controllers/)
// Nie trzeba już ręcznie używać include_once - autoloader to zrobi automatycznie.

switch ($action) {
	default : // 'calcView' - wyświetlenie formularza
		// autoloader sam załaduje plik na podstawie przestrzeni nazw
		$ctrl = new app\controllers\CalcCtrl();
		$ctrl->generateView ();
	break;
	case 'calcCompute' : // obliczenia
		// autoloader sam załaduje plik na podstawie przestrzeni nazw
		$ctrl = new app\controllers\CalcCtrl();
		$ctrl->process ();
	break;
}
