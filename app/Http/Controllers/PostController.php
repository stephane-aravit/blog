<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche la liste des articles.
     */
    public function index(Request $request)
    {
        // Vérification des droits de l'utilisateur connecté
        $this->authorize('viewAny', Post::class);   // On envoie ici juste la classe Post (string), pas l'instance car on ne manipule pas cette dernière

        // Requête de base sur les articles
        $query = Post::with('categories');

        // Ajout filtre par mot-clés si présent
        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%")->orWhere('content', 'like', "%{$request->search}%");
        }

        // Ajout ordre de tri
        $sortField = $request->input('sort_field', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        // Ajout pagination
        $posts = $query->paginate(10)->withQueryString();

        return Inertia::render('posts/Index', [
            'posts' => $posts,
            'filters' => $request->only(['search', 'sort_field', 'sort_order']),
        ]);
    }

    /**
     * Affiche le formulaire de création d'un article.
     */
    public function create()
    {
        $this->authorize('create', Post::class);

        return Inertia::render('posts/Create', [
            'categories' => Category::all(),
            'users' => User::all(),
        ]);
    }

    /**
     * Sauvegarde un nouvel article.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Post::class);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'categories' => 'array',
            'user_id' => 'required|exists:users,id',
        ]);

        try {

            $post = Post::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'user_id' => Auth::user()->role === 'admin' ? $validated['user_id'] : Auth::id(),
            ]);

            if (!empty($validated['categories'])) {
                $post->categories()->sync($validated['categories']);
            }

            Log::info('Création article #' . $post->id);

            return redirect()->route('posts.index')->with('flash', [
                'success' => 'Article créé avec succès.',
            ]);

        } catch (\Exception $e) {

            Log::error('Erreur création article', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('flash', [
                'error' => 'Erreur lors de la création de l\'article.',
            ]);
        }
    }

    /**
     * Affiche le détail d'un article.
     */
    public function show(Post $post)
    {
        $this->authorize('view', $post);    // On envoie ici au contraire l'instance

        return Inertia::render('posts/Show', [
            'post' => Post::with('categories')->find($post->id),    // On envoie aussi les catégories liées (table pivot)
            'categories' => Category::all(),
            'users' => User::all(),
        ]);
    }

    /**
     * Affiche le formulaire d'édition d'un article.
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return Inertia::render('posts/Edit', [
            'post' => Post::with('categories')->find($post->id),    // Idem show
            'categories' => Category::all(),
            'users' => User::all(),
        ]);
    }

    /**
     * Met à jour un article.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'categories' => 'array',
            'user_id' => 'required|exists:users,id',
        ]);

        try {

            $post->update([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'user_id' => Auth::user()->role === 'admin' ? $validated['user_id'] : Auth::id(),
            ]);

            if (!empty($validated['categories'])) {
                $post->categories()->sync($validated['categories']);
            } else {
                $post->categories()->detach();
            }

            Log::info('Mise à jour article #' . $post->id);

            return redirect()->route('posts.index')->with('flash', [
                'success' => 'Article mis à jour avec succès.',
            ]);

        } catch (\Exception $e) {

            Log::error('Erreur mise à jour article', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('flash', [
                'error' => 'Erreur lors de la mise à jour de l\'article.',
            ]);
        }
    }

    /**
     * Supprime un article.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        try {

            $post->categories()->detach();

            $post->delete();

            Log::info('Suppression article #' . $post->id);

            return redirect()->route('posts.index')->with('flash', [
                'success' => 'Article supprimé avec succès.',
            ]);

        } catch (\Exception $e) {

            Log::error('Erreur suppression article #' . $post->id, [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('flash', [
                'error' => 'Erreur lors de la supression de l\'article.',
            ]);
        }
    }
}
