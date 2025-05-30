<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compte;
use Illuminate\Support\Str;

class CompteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type' => 'required|in:courant,epargne',
        ]);

        $compte = Compte::create([
            'numero' => Str::random(12), // Numéro de compte aléatoire
            'type' => $validated['type'],
            'solde' => 0,
            'date_creation' => now(),
            'client_id' => $validated['client_id'],
        ]);

        return $compte;
    }

    public function show($id)
    {
        return Compte::with(['client', 'cartes', 'transactionsSource', 'transactionsDest'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $compte = Compte::findOrFail($id);
        $validated = $request->validate([
            'type' => 'in:courant,epargne',
            'solde' => 'numeric|min:0',
        ]);

        $compte->update($validated);
        return $compte;
    }

    public function destroy($id)
    {
        $compte = Compte::findOrFail($id);
        $compte->delete();
        return response()->json(['message' => 'Compte deleted']);
    }
}
