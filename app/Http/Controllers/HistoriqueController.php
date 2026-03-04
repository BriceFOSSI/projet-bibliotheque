<?php 

namespace App\Http\Controllers;

use App\Models\Achat;
use Illuminate\Support\Facades\Auth;

class HistoriqueController extends Controller
{
    public function index()
    {
        $achats = Achat::with('livres')->where('user_id', Auth::id())->get();
        return view('achats.historique', compact('achats'));
    }
}
