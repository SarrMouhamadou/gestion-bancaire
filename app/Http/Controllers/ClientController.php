<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        return Client::with(['comptes', 'credits', 'tickets'])->get();
    }

    public function show($id)
    {
        return Client::with(['comptes', 'credits', 'tickets'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $validated = $request->validate([
            'nom' => 'string|max:255',
            'prenom' => 'string|max:255',
            'email' => 'string|email|unique:clients,email,' . $id,
            'telephone' => 'string|max:20',
            'adresse' => 'string',
            'statut' => 'in:actif,inactif',
        ]);

        $client->update($validated);
        return $client;
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return response()->json(['message' => 'Client deleted']);
    }
}
