<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Livre;

class LivreController extends Controller
{
    public function index()
    {
        $livres = Livre::all();
        return view('livres.index', compact('livres'));
    }

    public function create()
    {
        return view('livres.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255|unique:livres,titre',
            'auteur' => 'required|string|max:255',
            'categorie' => 'required|string|max:100',
            'annee_publication' => 'required|digits:4|integer|min:1800|max:' . date('Y'),
            'resume' => 'required|string|min:20|max:2000',
            'prix' => 'required|numeric|min:0|max:1000',
        ], [
            'titre.required' => 'Le titre est obligatoire.',
            'titre.unique' => 'Un livre avec ce titre existe déjà.',
            'auteur.required' => 'Le nom de l’auteur est obligatoire.',
            'categorie.required' => 'La catégorie est obligatoire.',
            'annee_publication.required' => 'L’année de publication est obligatoire.',
            'annee_publication.digits' => 'L’année doit comporter 4 chiffres.',
            'annee_publication.min' => 'L’année de publication doit être supérieure ou égale à 1800.',
            'annee_publication.max' => 'L’année de publication ne peut pas être dans le futur.',
            'resume.required' => 'Le résumé est obligatoire.',
            'resume.min' => 'Le résumé doit comporter au moins 20 caractères.',
            'resume.max' => 'Le résumé ne peut pas dépasser 2000 caractères.',
            'prix.required' => 'Le prix est obligatoire.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'prix.min' => 'Le prix doit être au moins de 0€.',
            'prix.max' => 'Le prix ne peut pas dépasser 1000€.',
        ]);

        Livre::create($request->all());
        return redirect()->route('livres.index')->with('success', 'Livre ajouté avec succès !');
    }

    public function show(Livre $livre)
    {
        return view('livres.show', compact('livre'));
    }

    public function destroy(Livre $livre)
    {
        $livre->delete();
        return redirect()->route('livres.index')->with('success', 'Livre supprimé avec succès !');
    }

    public function searchForm()
    {
        return view('livres.search');
    }

    public function search(Request $request)
    {
        $query = Livre::query();

        if ($request->titre) {
            $query->where('titre', 'like', "%{$request->titre}%");
        }
        if ($request->auteur) {
            $query->where('auteur', 'like', "%{$request->auteur}%");
        }
        if ($request->categorie) {
            $query->where('categorie', 'like', "%{$request->categorie}%");
        }
        if ($request->annee_publication) {
            $query->where('annee_publication', $request->annee_publication);
        }
        if ($request->prix_min) {
            $query->where('prix', '>=', $request->prix_min);
        }
        if ($request->prix_max) {
            $query->where('prix', '<=', $request->prix_max);
        }

        $livres = $query->get();
        return view('livres.search', compact('livres'));
    }

    public function nouveautes()
    {
        $livres = Livre::where('created_at', '>=', now()->subDays(10))->get();
        return view('livres.nouveautes', compact('livres'));
    }

    public function edit(Livre $livre)
    {
        return view('livres.edit', compact('livre'));
    }

    public function update(Request $request, Livre $livre)
    {
        $request->validate([
            'titre' => 'required|string|max:255|unique:livres,titre,' . $livre->id,
            'auteur' => 'required|string|max:255',
            'categorie' => 'required|string|max:100',
            'annee_publication' => 'required|digits:4|integer|min:1800|max:' . date('Y'),
            'resume' => 'required|string|min:20|max:2000',
            'prix' => 'required|numeric|min:0|max:1000',
        ], [
            'titre.required' => 'Le titre est obligatoire.',
            'titre.unique' => 'Un autre livre possède déjà ce titre.',
            'auteur.required' => 'Le nom de l’auteur est obligatoire.',
            'categorie.required' => 'La catégorie est obligatoire.',
            'annee_publication.required' => 'L’année de publication est obligatoire.',
            'annee_publication.digits' => 'L’année doit comporter 4 chiffres.',
            'annee_publication.min' => 'L’année de publication doit être supérieure ou égale à 1800.',
            'annee_publication.max' => 'L’année de publication ne peut pas dépasser l’année actuelle.',
            'resume.required' => 'Le résumé est obligatoire.',
            'resume.min' => 'Le résumé doit comporter au moins 20 caractères.',
            'resume.max' => 'Le résumé ne peut pas dépasser 2000 caractères.',
            'prix.required' => 'Le prix est obligatoire.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'prix.min' => 'Le prix doit être au moins de 0€.',
            'prix.max' => 'Le prix ne peut pas dépasser 1000€.',
        ]);

        $livre->update($request->all());
        return redirect()->route('livres.index')->with('success', 'Livre mis à jour avec succès !');
    }
}
