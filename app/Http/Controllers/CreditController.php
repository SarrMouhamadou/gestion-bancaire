<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Credit;

class CreditController extends Controller
{
    // Demander un crédit
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'montant' => 'required|numeric|min:1000',
            'taux_interet' => 'required|numeric|min:0|max:20',
            'duree_mois' => 'required|integer|min:1|max:360',
        ]);

        // Calcul de la mensualité (formule simple : mensualité = (montant * (1 + taux_interet/100)) / durée_mois)
        $mensualite = ($validated['montant'] * (1 + $validated['taux_interet'] / 100)) / $validated['duree_mois'];

        $credit = Credit::create([
            'montant' => $validated['montant'],
            'taux_interet' => $validated['taux_interet'],
            'duree_mois' => $validated['duree_mois'],
            'mensualite' => $mensualite,
            'date_demande' => now(),
            'statut' => 'en_cours',
            'client_id' => $validated['client_id'],
        ]);

        return response()->json($credit, 201);
    }

    // Afficher les détails d'un crédit
    public function show($id)
    {
        return Credit::with(['client', 'remboursements'])->findOrFail($id);
    }

    // Mettre à jour le statut d'un crédit (ex. : rejeter ou terminer)
    public function update(Request $request, $id)
    {
        $credit = Credit::findOrFail($id);
        $validated = $request->validate([
            'statut' => 'required|in:en_cours,termine,rejete',
        ]);

        $credit->update($validated);
        return $credit;
    }

    // Supprimer un crédit
    public function destroy($id)
    {
        $credit = Credit::findOrFail($id);
        $credit->delete();
        return response()->json(['message' => 'Crédit supprimé']);
    }
}
