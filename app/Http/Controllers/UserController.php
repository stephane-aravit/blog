<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use App\Enums\Role;


class UserController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Affiche la liste des utilisateurs.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        return Inertia::render('users/Index', [
            'users' => User::all(),
        ]);
    }

    /**
     * Affiche le formulaire de création d'un utilisateur.
     */
    public function create()
    {
        $this->authorize('create', User::class);

        return Inertia::render('users/Create', [
            'roles' => array_column(Role::cases(), 'value'),
        ]);
    }

    /**
     * Sauvegarde un nouvel utilisateur.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|string|max:255',
        ]);

        try {
            
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'email_verified_at' => now(),
            ]);


            Log::info('Création utilisateur #' . $user->id);

            return redirect()->route('users.index')->with('flash', [
                'success' => 'Utilisateur créé avec succès.',
            ]);
        
        } catch (\Exception $e) {

            Log::error('Erreur création utilisateur', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('flash', [
                'error' => 'Erreur lors de la création de l\'utilisateur.',
            ]);
        }
    }

    /**
     * Affiche le détail d'un utilisateur.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return Inertia::render('users/Show', [
            'user' => $user,
        ]);
    }

    /**
     * Affiche le formulaire d'édition d'un utilisateur.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return Inertia::render('users/Edit', [
            'user' => $user,
            'roles' => array_column(Role::cases(), 'value'),
        ]);
    }

    /**
     * Met à jour un utilisateur.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class.',email,'.$user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|string|max:255',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        try {

            $user->update($validated);

            Log::info('Mise à jour utilisateur #' . $user->id);

            return redirect()->route('users.index')->with('flash', [
                'success' => 'Utilisateur mis à jour avec succès.',
            ]);

        } catch (\Exception $e) {

            Log::error('Erreur mise à jour utilisateur', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('flash', [
                'error' => 'Erreur lors de la mise à jour de l\'utilisateur.',
            ]);
        }
    }

    /**
     * Supprime un utilisateur.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        try {

            $user->delete();

            Log::info('Suppression utilisateur #' . $user->id);

            return redirect()->route('users.index')->with('flash', [
                'success' => 'Utilisateur supprimé avec succès.',
            ]);

        } catch (\Exception $e) {

            Log::error('Erreur suppression utilisateur #' . $user->id, [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('flash', [
                'error' => 'Erreur lors de la supression de l\'utilisateur.',
            ]);
        }
    }
}
