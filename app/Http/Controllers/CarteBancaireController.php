<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarteBancaire;
use App\Models\Compte;
use Illuminate\Support\Str;

class CarteBancaireController extends Controller
{
    // Créer une nouvelle carte bancaire
    public function store(Request $request)
    {
        $validated = $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'code_pin' => 'required|string|size:4',
        ]);

        $carte = CarteBancaire::create([
            'numero' => Str::random(16), // Numéro de carte aléatoire (16 chiffres)
            'cvv' => str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT), // CVV aléatoire (3 chiffres)
            'date_expiration' => now()->addYears(3), // Valide pour 3 ans
            'solde' => 0,
            'statut' => 'active',
            'compte_id' => $validated['compte_id'],
            'code_pi' => $validated['code_pin'],
        ]);

        return response()->json($carte, 201);
    }

    // Afficher les détails d'une carte bancaire
    public function show($id)
    {
        return CarteBancaire::with('compte')->findOrFail($id);
    }

    // Bloquer ou débloquer une carte bancaire
    public function updateStatus(Request $request, $id)
    {
        $carte = CarteBancaire::findOrFail($id);
        $validated = $request->validate([
            'statut' => 'required|in:active,bloquee',
        ]);

        $carte->update(['statut' => $validated['statut']]);
        return $carte;
    }

    // Supprimer une carte bancaire
    public function destroy($id)
    {
        $carte = CarteBancaire::findOrFail($id);
        $carte->delete();
        return response()->json(['message' => 'Carte bancaire supprimée']);
    }
}
