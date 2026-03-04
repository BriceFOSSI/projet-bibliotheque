<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achat;
use App\Models\Livre;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAchats = Achat::count();
        $revenusTotal = Achat::sum('total');

        $topLivres = Livre::select('livres.titre')
            ->join('achat_livre', 'livres.id', '=', 'achat_livre.livre_id')
            ->selectRaw('livres.titre, SUM(achat_livre.quantite) as total_vendus')
            ->groupBy('livres.titre')
            ->orderByDesc('total_vendus')
            ->take(5)
            ->get();

        $dernieresTransactions = Achat::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalAchats',
            'revenusTotal',
            'topLivres',
            'dernieresTransactions'
        ));
    }
}
