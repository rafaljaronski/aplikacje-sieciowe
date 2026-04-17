<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleStatus;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request) {
        // strona glowna, zatwierdzone artykuly
        $query = Article::with(['author', 'status'])
            ->where('status_id', function($query) {
                $query->select('id')->from('article_status')->where('name', 'approved')->limit(1);
            });
        
        // wyszukiwanie po tytule caseinsensitive
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($search) . '%']);
        }
        
        $articles = $query->orderBy('created_at', 'desc')->get();
        
        return view('articles.index', compact('articles'));
    }
    
    // autor/moderator zarzadzanie artykulami
    public function manage(Request $request) {
        if (!session('user_roles') || 
            (!in_array('Autor', session('user_roles', [])) && !in_array('Moderator', session('user_roles', [])))) {
            return redirect()->route('home')->with('error', 'Nie masz dostępu');
        }
        
        if (in_array('Moderator', session('user_roles', []))) {
            // moderator widzi wszystkie artykuly
            $query = Article::with(['author', 'status']);
        } else {
            //autor widzi swoje artykuly
            $query = Article::with(['author', 'status'])
                ->where('author_id', session('user_id'));
        }
        
        // wyszukiwanie po tytule caseinsensitive
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($search) . '%']);
        }
        
        // filtrowanie po statusie
        if ($request->filled('status')) {
            $query->where('status_id', $request->input('status'));
        }
        
        $articles = $query->orderBy('created_at', 'desc')->get();
        $statuses = ArticleStatus::all();
        
        return view('articles.index', compact('articles', 'statuses'));
    }

    // nowy artykul
    public function create() {
        if (!session('user_roles') || !in_array('Autor', session('user_roles', []))) {
            return redirect()->route('home')->with('error', 'Nie masz uprawnień do tworzenia artykułów');
        }
        return view('articles.create');
    }

    // zapis nowego artykulu
    public function store(Request $request) {
        if (!session('user_roles') || !in_array('Autor', session('user_roles', []))) {
            return redirect()->route('home')->with('error', 'Nie masz uprawnień do tworzenia artykułów');
        }
        
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ], [
            'title.required' => 'Tytuł jest wymagany',
            'title.max' => 'Tytuł może mieć maksymalnie 255 znaków',
            'content.required' => 'Treść artykułu jest wymagana'
        ]);
        
        // akcja z przyciskow - draft lub pending
        $action = $request->input('action', 'draft');
        $status = ArticleStatus::where('name', $action)->first();
        
        Article::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status_id' => $status->id,
            'author_id' => session('user_id')
        ]);
        
        $message = $action === 'pending' ? 'Artykuł został wysłany do akceptacji' : 'Została utworzona wersja robocza artykułu';
        return redirect()->route('articles.index')->with('success', $message);
    }

    // szczegoly artykulu
    public function show(Article $article) {
        $article->load(['author', 'status', 'reviewer']);
        // gosc
        if (!session('user_roles') && $article->status->name !== 'approved') {
            return redirect()->route('home')->with('error', 'Nie masz dostępu do tego artykułu');
        }
        // zalogowany
        return view('articles.show', compact('article'));
    }

    // edycja artykulu
    public function edit(Article $article) {
        // edycja tylko przez autora
        if (!session('user_id') || $article->author_id !== session('user_id')) {
            return redirect()->route('home')->with('error', 'Nie masz uprawnień do edycji tego artykułu');
        }
        // nie mozna edytowac zatwierdzonego lub oczekujacego artykulu
        if (in_array($article->status->name, ['approved', 'pending'])) {
            return redirect()->route('articles.show', $article)->with('error', 'Można edytować tylko wersje robocze i odrzucone artykuły');
        }
        
        return view('articles.edit', compact('article'));
    }

    // zapis edytowanego artykulu
    public function update(Request $request, Article $article) {
        // edycja tylko przez autora
        if (!session('user_id') || $article->author_id !== session('user_id')) {
            return redirect()->route('home')->with('error', 'Nie masz uprawnień do edycji tego artykułu');
        }
        // nie mozna edytowac zatwierdzonego lub oczekujacego artykulu
        if (in_array($article->status->name, ['approved', 'pending'])) {
            return redirect()->route('articles.show', $article)->with('error', 'Można edytować tylko wersje robocze i odrzucone artykuły');
        }
        
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ], [
            'title.required' => 'Tytuł jest wymagany',
            'title.max' => 'Tytuł może mieć maksymalnie 255 znaków',
            'content.required' => 'Treść artykułu jest wymagana'
        ]);
        
        $updateData = [
            'title' => $validated['title'],
            'content' => $validated['content']
        ];
        
        // button z action, wyslanie odrzuconego do ponownej akceptacji
        $message = 'Artykuł został zaktualizowany';
        if ($request->input('action') === 'pending' && $article->status->name === 'rejected') {
            $pendingStatus = ArticleStatus::where('name', 'pending')->first();
            $updateData['status_id'] = $pendingStatus->id;
            $message = 'Artykuł został zaktualizowany i wysłany do akceptacji';
        }
        
        $article->update($updateData);
        
        return redirect()->route('articles.show', $article)->with('success', $message);
    }

    // usuwanie artykulu
    public function destroy(Article $article) {
        // usuwanie przez autora lub moderatora
        $canDelete = session('user_id') && (
            $article->author_id === session('user_id') ||
            in_array('Moderator', session('user_roles', []))
        );
        
        if (!$canDelete) {
            return redirect()->route('home')->with('error', 'Nie masz uprawnień do usunięcia tego artykułu');
        }
        
        $article->delete();
        return redirect()->route('articles.index')->with('success', 'Artykuł został usunięty');
    }
    
    // wysylanie artykulu do akceptacji
    public function submitForReview(Article $article) {
        // wysylanie tylko przez autora
        if (!session('user_id') || $article->author_id !== session('user_id')) {
            return redirect()->route('home')->with('error', 'Nie masz uprawnień do wysłania tego artykułu');
        }
        // tylko wersje robocze
        if ($article->status->name !== 'draft') {
            return redirect()->route('articles.show', $article)->with('error', 'Tylko wersje robocze można wysłać do akceptacji');
        }
        
        $pendingStatus = ArticleStatus::where('name', 'pending')->first();
        $article->update(['status_id' => $pendingStatus->id]);
        return redirect()->route('articles.show', $article)->with('success', 'Artykuł został wysłany do akceptacji');
    }
    
    // zatwierdzanie artykulu
    public function approve(Article $article) {
        if (!in_array('Moderator', session('user_roles', []))) {
            return redirect()->route('home')->with('error', 'Nie masz uprawnień do akceptacji');
        }
        
        $approvedStatus = ArticleStatus::where('name', 'approved')->first();
        $article->update([
            'status_id' => $approvedStatus->id,
            'reviewed_by' => session('user_id'),
            'reviewed_at' => now(),
            'rejection_reason' => null
        ]);
        return redirect()->route('articles.show', $article)->with('success', 'Artykuł został zatwierdzony');
    }
    
    // odrzucanie artykulu
    public function reject(Request $request, Article $article) {
        if (!in_array('Moderator', session('user_roles', []))) {
            return redirect()->route('home')->with('error', 'Nie masz uprawnień do odrzucenia');
        }
        
        $validated = $request->validate([
            'rejection_reason' => 'required'
        ], [
            'rejection_reason.required' => 'Powód odrzucenia jest wymagany'
        ]);
        
        $rejectedStatus = ArticleStatus::where('name', 'rejected')->first();
        $article->update([
            'status_id' => $rejectedStatus->id,
            'reviewed_by' => session('user_id'),
            'reviewed_at' => now(),
            'rejection_reason' => $validated['rejection_reason']
        ]);
        return redirect()->route('articles.show', $article)->with('success', 'Artykuł został odrzucony');
    }
}
