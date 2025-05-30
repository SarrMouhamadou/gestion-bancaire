<?php

namespace App\Http\Controllers;

use App\Models\FraisBancaire;
use App\Models\Compte;
use Illuminate\Http\Request;

class FraisBancaireController extends Controller
{
    // Appliquer des frais bancaires
    public function store(Request $request)
    {
        $validated = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'type' => 'required|string',
            'montant' => 'required|numeric|min:0',
        ]);

        $compte = Compte::findOrFail($validated['compte_id']);
        if ($compte->solde < $validated['montant']) {
            return response()->json(['message' => 'Solde insuffisant pour appliquer les frais'], 400);
        }

        $frais = FraisBancaire::create([
            'compte_id' => $validated['compte_id'],
            'type' => $validated['type'],
            'montant' => $validated['montant'],
            'date' => now(),
            'statut' => 'applique',
        ]);

        $compte->solde -= $validated['montant'];
        $compte->save();

        return response()->json($frais, 201);
    }

    // Afficher un frais bancaire
    public function show($id)
    {
        return FraisBancaire::with('compte')->findOrFail($id);
    }

    // Annuler un frais bancaire
    public function cancel($id)
    {
        $frais = FraisBancaire::findOrFail($id);
        if ($frais->statut === 'annule') {
            return response()->json(['message' => 'Frais déjà annulé'], 400);
        }

        $frais->update(['statut' => 'annule']);
        $compte = $frais->compte;
        $compte->solde += $frais->montant;
        $compte->save();

        return response()->json(['message' => 'Frais annulé']);
    }
}

