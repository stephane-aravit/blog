<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche la liste des catégories.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Category::class);

        // Requête de base
        $query = Category::with('posts');

        // Ajout filtre par mot-clés si présent
        if ($request->filled('search')) {
            $query
                ->where('name', 'like', "%{$request->search}%")
                ->orWhere('description', 'like', "%{$request->search}%");
        }

        // Ajout ordre de tri
        $sortField = 'name';
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortField, $sortOrder);

        // Ajout pagination
        $categories = $query->paginate(10)->withQueryString();

        return Inertia::render('categories/Index', [
            'categories' => $categories,
            'filters' => $request->only(['search', 'sort_order']),
        ]);
    }

    /**
     * Affiche le formulaire de création d'une catégorie.
     */
    public function create()
    {
        $this->authorize('create', Category::class);

        return Inertia::render('categories/Create');
    }

    /**
     * Sauvegarde une nouvelle catégorie.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Category::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {

            $category = Category::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
            ]);

            Log::info('Création catégorie #' . $category->id);

            return redirect()->route('categories.index')->with('flash', [
                'success' => 'Catégorie créée avec succès.',
            ]);

        } catch (\Exception $e) {

            Log::error('Erreur création catégorie', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('flash', [
                'error' => 'Erreur lors de la création de la catégorie.',
            ]);
        }
    }

    /**
     * Affiche le détail d'une catégorie.
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);

        return Inertia::render('categories/Show', [
            'category' => $category,
        ]);
    }

    /**
     * Affiche le formulaire d'édition d'une catégorie.
     */
    public function edit(Category $category)
    {
        $this->authorize('update', $category);

        return Inertia::render('categories/Edit', [
            'category' => $category,
        ]);
    }

    /**
     * Met à jour une catégorie existante.
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('create', $category);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {

            $category->update($validated);

            Log::info('Mise à jour catégorie #' . $category->id);

            return redirect()->route('categories.index')->with('flash', [
                'success' => 'Catégorie mise à jour avec succès.',
            ]);

        } catch (\Exception $e) {

            Log::error('Erreur mise à jour catégorie', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('flash', [
                'error' => 'Erreur lors de la mise à jour de la catégorie.',
            ]);
        }
    }

    /**
     * Supprime une catégorie.
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        try {

            $category->posts()->detach();

            $categoryId = $category->id;
            $category->delete();

            Log::info('Suppression catégorie #' . $categoryId);

            return redirect()->route('categories.index')->with('flash', [
                'success' => 'Catégorie supprimée avec succès.',
            ]);

        } catch (\Exception $e) {

            Log::error('Erreur suppression catégorie #' . $category->id, [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->route('categories.index')->with('flash', [
                'error' => 'Erreur lors de la suppression de la catégorie.',
            ]);
        }
    }
}
