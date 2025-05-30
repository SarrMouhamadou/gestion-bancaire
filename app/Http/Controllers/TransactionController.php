<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Compte;
use App\Notifications\TransactionNotification;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:depot,retrait,virement',
            'montant' => 'required|numeric|min:0',
            'compte_source_id' => 'required|exists:comptes,id',
            'compte_dest_id' => 'nullable|exists:comptes,id',
        ]);

        $compteSource = Compte::findOrFail($validated['compte_source_id']);

        if ($validated['type'] === 'retrait' || $validated['type'] === 'virement') {
            if ($compteSource->solde < $validated['montant']) {
                return response()->json(['message' => 'Solde insuffisant'], 400);
            }
        }

        $transaction = Transaction::create([
            'type' => $validated['type'],
            'montant' => $validated['montant'],
            'date' => now(),
            'compte_source_id' => $validated['compte_source_id'],
            'compte_dest_id' => $validated['compte_dest_id'],
            'statut' => 'valide',
        ]);

        // Mise à jour des soldes
        if ($validated['type'] === 'depot') {
            $compteSource->solde += $validated['montant'];
        } elseif ($validated['type'] === 'retrait') {
            $compteSource->solde -= $validated['montant'];
        } elseif ($validated['type'] === 'virement') {
            $compteSource->solde -= $validated['montant'];
            $compteDest = Compte::findOrFail($validated['compte_dest_id']);
            $compteDest->solde += $validated['montant'];
            $compteDest->save();
        }

        $compteSource->save();

        // Envoyer la notification au client
        $client = $compteSource->client;
        $client->notify(new TransactionNotification($transaction));

        return $transaction;
    }

    public function show($id)
    {
        return Transaction::with(['compteSource', 'compteDest'])->findOrFail($id);
    }
}
