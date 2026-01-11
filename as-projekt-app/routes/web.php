<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\ArticleController;

// strona glowna - lista artykulow
Route::get('/', [ArticleController::class, 'index'])->name('home');

// zarzadzanie artykulami (autor/moderator)
Route::get('/articles/manage', [ArticleController::class, 'manage'])->name('articles.manage');

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
