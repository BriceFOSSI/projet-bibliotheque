<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Livre;

class PanierController extends Controller
{
    public function index()
    {
        $panier = session()->get('panier', []);
        return view('panier.index', compact('panier'));
    }

    public function ajouter(Request $request, $id)
    {
        $livre = Livre::findOrFail($id);

        $panier = session()->get('panier', []);

        if(isset($panier[$id])) {
            $panier[$id]['quantite']++;
        } else {
            $panier[$id] = [
                "titre" => $livre->titre,
                "prix" => $livre->prix,
                "quantite" => 1
            ];
        }

        session()->put('panier', $panier);

        return redirect()->back()->with('success', 'Livre ajouté au panier 🛒');
    }

    public function retirer($id)
    {
        $panier = session()->get('panier', []);

        if(isset($panier[$id])) {
            unset($panier[$id]);
            session()->put('panier', $panier);
        }

        return redirect()->back()->with('success', 'Livre retiré du panier ❌');
    }

    public function vider()
    {
        session()->forget('panier');
        return redirect()->back()->with('success', 'Panier vidé 🗑️');
    }
}
