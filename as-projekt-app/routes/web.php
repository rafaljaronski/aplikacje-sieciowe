<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Http\Controllers\ArticleController;

// strona glowna - lista artykulow
Route::get('/', [ArticleController::class, 'index'])->name('home');

// zarzadzanie artykulami (autor/moderator)
Route::get('/articles/manage', [ArticleController::class, 'manage'])->name('articles.manage');

// rejestracja
Route::get('/register', function () {
    if (session('user_id')) {
        return redirect()->route('home')->with('info', 'Jesteś już zalogowany');
    }
    return view('register');
})->name('register');

Route::post('/register', function (Request $request) {
    if (session('user_id')) {
        return redirect()->route('home')->with('info', 'Jesteś już zalogowany');
    }
    
    $validated = $request->validate([
        'email' => 'required|unique:user,email',
        'password' => 'required|min:6',
        'first_name' => 'required',
        'last_name' => 'required'
    ], [
        'email.required' => 'Email jest wymagany',
        'email.unique' => 'Email zajęty',
        'password.required' => 'Hasło jest wymagane',
        'password.min' => 'Hasło musi mieć minimum 6 znaków',
        'first_name.required' => 'Imię jest wymagane',
        'last_name.required' => 'Nazwisko jest wymagane'
    ]);
    
    // utworz uzytkownika
    $user = User::create([
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'first_name' => $validated['first_name'],
        'last_name' => $validated['last_name']
    ]);
    
    // przypisz role czytelnik
    $readerRole = Role::where('name', 'Czytelnik')->first();
    if ($readerRole) {
        $user->roles()->attach($readerRole->id);
    }
    
    // automatyczne logowanie
    session([
        'user_id' => $user->id,
        'user_email' => $user->email,
        'user_roles' => ['Czytelnik']
    ]);
    
    return redirect()->route('home')->with('success', 'Rejestracja zakończona pomyślnie. Witaj!');
})->name('register.store');

// logowanie
Route::post('/login', function (Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');
    
    // pobierz uzytkownika z rolami
    $user = User::where('email', $email)->with('roles')->first();
    
    // walidacja
    if ($user && Hash::check($password, $user->password)) {
        // wyciagniecie nazw rol
        $roles = $user->roles->pluck('name')->toArray();
        
        if (!empty($roles)) {
            session([
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_roles' => $roles
            ]);
            return redirect()->route('home');
        } else {
            return back()->withInput()->with('error', 'Użytkownik nie ma przypisanej roli');
        }
    } else {
        return back()->withInput()->with('error', 'Nieprawidłowy email lub hasło');
    }
})->name('login');

// logout
Route::get('/logout', function () {
    session()->flush();
    return redirect()->route('home')->with('info', 'Wylogowano pomyślnie');
})->name('logout');

// artykuly
// resource automatycznie mapuje metody:
// index, create, store, show, edit, update, destroy
Route::resource('articles', ArticleController::class);

// dodatkowe akcje dla artykulow
Route::post('/articles/{article}/submit', [ArticleController::class, 'submitForReview'])->name('articles.submit');
Route::post('/articles/{article}/approve', [ArticleController::class, 'approve'])->name('articles.approve');
Route::post('/articles/{article}/reject', [ArticleController::class, 'reject'])->name('articles.reject');
