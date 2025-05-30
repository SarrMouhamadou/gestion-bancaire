<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketSupport;

class TicketSupportController extends Controller
{
    // Créer un ticket de support (client)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sujet' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $ticket = TicketSupport::create([
            'sujet' => $validated['sujet'],
            'description' => $validated['description'],
            'date_ouverture' => now(),
            'statut' => 'ouvert',
            'client_id' => $request->user()->id,
        ]);

        return response()->json($ticket, 201);
    }

    // Afficher un ticket
    public function show($id)
    {
        return TicketSupport::with(['client', 'admin'])->findOrFail($id);
    }

    // Mettre à jour un ticket (admin)
    public function update(Request $request, $id)
    {
        $ticket = TicketSupport::findOrFail($id);
        $validated = $request->validate([
            'statut' => 'required|in:ouvert,en_cours,ferme',
            'admin_id' => 'nullable|exists:admins,id',
        ]);

        $ticket->update([
            'statut' => $validated['statut'],
            'admin_id' => $validated['admin_id'] ?? $ticket->admin_id,
        ]);

        return $ticket;
    }

    // Supprimer un ticket
    public function destroy($id)
    {
        $ticket = TicketSupport::findOrFail($id);
        $ticket->delete();
        return response()->json(['message' => 'Ticket supprimé']);
    }
}
