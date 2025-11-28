<?php
// Konfiguracja dla php_06_nowastruktura_zad
// Obiekt $conf jest już utworzony w init.php

$conf->root_path = dirname(__FILE__);
$conf->server_name = 'localhost:80';
$conf->server_url = 'http://'.$conf->server_name;
$conf->app_root = '/php_06_nowastruktura_zad';
$conf->app_url = $conf->server_url.$conf->app_root;