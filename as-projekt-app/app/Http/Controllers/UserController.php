<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // lista uzytkownikow
    public function index() {
        if (!in_array('Administrator', session('user_roles', []))) {
            return redirect()->route('home')->with('error', 'Nie masz uprawnień');
        }
        
        // pobierz urzytkownikow bez administratorow
        $adminRoleId = Role::where('name', 'Administrator')->value('id');
        $users = User::with('roles')
            ->whereDoesntHave('roles', function($query) use ($adminRoleId) {
                $query->where('role_id', $adminRoleId);
            })
            ->get();
        
        // pobierz role
        $availableRoles = Role::where('name', '!=', 'Administrator')->get();
        
        return view('users.index', compact('users', 'availableRoles'));
    }
    
    // aktualizacja rol uzytkownika
    public function updateRoles(Request $request, User $user) {
        if (!in_array('Administrator', session('user_roles', []))) {
            return redirect()->route('home')->with('error', 'Nie masz uprawnień');
        }
        
        // sprawdz czy uzytkownik nie jest administratorem
        $userRoles = $user->roles->pluck('name')->toArray();
        if (in_array('Administrator', $userRoles)) {
            return back()->with('error', 'Nie można zmienić ról administratora');
        }
        
        $validated = $request->validate([
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:role,id'
        ], [
            'roles.required' => 'Musisz wybrać przynajmniej jedną rolę',
            'roles.min' => 'Musisz wybrać przynajmniej jedną rolę'
        ]);
        
        $selectedRoles = Role::whereIn('id', $validated['roles'])->get();
        if ($selectedRoles->contains('name', 'Administrator')) {
            return back()->with('error', 'Nie można nadać roli Administrator');
        }
        
        $user->roles()->sync($validated['roles']);
        
        return back()->with('success', 'Role zostały zaktualizowane');
    }
}
