<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche la liste des commentaires.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Comment::class);

        // Requête de base
        $query = Comment::with(['post', 'post.categories', 'user']);

        // Ajout filtre par mot-clés si présent
        if ($request->filled('search')) {
            $query->where('content', 'like', "%{$request->search}%");
        }

        // Ajout ordre de tri
        $sortField = $request->input('sort_field', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        // Ajout pagination
        $comments = $query->paginate(10)->withQueryString();

        return Inertia::render('comments/Index', [
            'comments' => $comments,
            'filters' => $request->only(['search', 'sort_field', 'sort_order']),
        ]);
    }

    /**
     * Affiche le formulaire pour créer un nouveau commentaire.
     */
    public function create()
    {
        $this->authorize('create', Comment::class);

        return Inertia::render('comments/Create', [
            'posts' => Post::all(),
            'users' => User::all(),
        ]);
    }

    /**
     * Sauvegarde un nouveau commentaire.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Comment::class);

        $validated = $request->validate([
            'content' => 'required',
            'post_id' => 'required|exists:posts,id',
            'user_id' => 'required|exists:users,id',
        ]);

        try {

            $comment = Comment::create([
                'content' => $validated['content'],
                'post_id' => $validated['post_id'],
                'user_id' => Auth::user()->role === 'admin' ? $validated['user_id'] : Auth::id(),
            ]);

            Log::info('Création commentaire #' . $comment->id);

            return redirect()->route('comments.index')->with('flash', [
                'success' => 'Commentaire créé avec succès.',
            ]);

        } catch (\Exception $e) {

            Log::error('Erreur création commentaire', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('flash', [
                'error' => 'Erreur lors de la création du commentaire.',
            ]);

        }
    }

    /**
     * Affiche le détail d'un commentaire.
     */
    public function show(Comment $comment)
    {
        $this->authorize('view', $comment);

        return Inertia::render('comments/Show', [
            'comment' => $comment,
            'posts' => Post::all(),
            'users' => User::all(),
        ]);
    }

    /**
     * Affiche le formulaire d'édition d'un commentaire.
     */
    public function edit(Comment $comment)
    {
        $this->authorize('update', $comment);

        return Inertia::render('comments/Edit', [
            'comment' => $comment,
            'posts' => Post::all(),
            'users' => User::all(),
        ]);
    }

    /**
     * Met à jour un commentaire.
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('create', $comment);

        $validated = $request->validate([
            'content' => 'required',
            'post_id' => 'required|exists:posts,id',
            'user_id' => 'required|exists:users,id',
        ]);

        try {

            $comment->update([
                'content' => $validated['content'],
                'post_id' => $validated['post_id'],
                'user_id' => Auth::user()->role === 'admin' ? $validated['user_id'] : Auth::id(),
            ]);

            Log::info('Mise à jour commentaire #' . $comment->id);

            return redirect()->route('comments.index')->with('flash', [
                'success' => 'Commentaire mis à jour avec succès.',
            ]);

        } catch (\Exception $e) {

            Log::error('Erreur mise à jour commentaire', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('flash', [
                'error' => 'Erreur lors de la mise à jour du commentaire.',
            ]);

        }
    }

    /**
     * Supprime un commentaire.
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        try {

            $comment->delete();

            Log::info('Suppression commentaire #' . $comment->id);

            return redirect()->route('comments.index')->with('flash', [
                'success' => 'Commentaire supprimé avec succès.',
            ]);

        } catch (\Exception $e) {

            Log::error('Erreur lors de la suppression du commentaire', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('flash', [
                'error' => 'Erreur lors de la suppression du commentaire.',
            ]);

        }
    }
}
