<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// strona glowna
Route::get('/', function () {
    return view('home');
})->name('home');

// logowanie
Route::post('/login', function (Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');
    
    // mock, zastapic baza
    $users = [
        'admin@projekt.pl' => ['password' => 'admin', 'role' => 'Administrator'],
        'autor@projekt.pl' => ['password' => 'autor', 'role' => 'Autor'],
        'moderator@projekt.pl' => ['password' => 'moderator', 'role' => 'Moderator'],
        'czytelnik@projekt.pl' => ['password' => 'czytelnik', 'role' => 'Czytelnik'],
    ];
    
    // walidacja
    if (isset($users[$email]) && $users[$email]['password'] === $password) {
        session([
            'user_email' => $email,
            'user_role' => $users[$email]['role']
        ]);
        return redirect('/');
    } else {
        return back()->withInput()->with('error', 'Nieprawidłowy email lub hasło');
    }
})->name('login');

// logout
Route::get('/logout', function () {
    session()->flush();
    return redirect('/')->with('info', 'Wylogowano pomyślnie');
})->name('logout');
