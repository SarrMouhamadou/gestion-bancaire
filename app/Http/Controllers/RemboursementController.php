<?php

namespace App\Http\Controllers;

use App\Models\Remboursement;
use App\Models\Credit;
use Illuminate\Http\Request;
use App\Notifications\RemboursementNotification;

class RemboursementController extends Controller
{
    // Enregistrer un remboursement
    public function store(Request $request)
    {
        $validated = $request->validate([
            'credit_id' => 'required|exists:credits,id',
            'montant' => 'required|numeric|min:0',
        ]);

        $credit = Credit::findOrFail($validated['credit_id']);
        $remboursement = Remboursement::create([
            'montant' => $validated['montant'],
            'date' => now(),
            'credit_id' => $validated['credit_id'],
        ]);

        // Vérifier si le crédit est totalement remboursé
        $totalRembourse = $credit->remboursements()->sum('montant');
        $totalDu = $credit->montant * (1 + $credit->taux_interet / 100);
        if ($totalRembourse >= $totalDu) {
            $credit->update(['statut' => 'termine']);
        }

        // Envoyer la notification au client
        $client = $credit->client;
        $client->notify(new RemboursementNotification($remboursement));

        return response()->json($remboursement, 201);
    }

    // Afficher un remboursement
    public function show($id)
    {
        return Remboursement::with('credit')->findOrFail($id);
    }

    // Supprimer un remboursement
    public function destroy($id)
    {
        $remboursement = Remboursement::findOrFail($id);
        $remboursement->delete();
        return response()->json(['message' => 'Remboursement supprimé']);
    }
}
