<?php

namespace App\Http\Controllers;

use App\Models\Bungalow;
use App\Models\Sejour;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SejourController extends Controller
{
    /**
     * Affiche le formulaire de réservation.
     */
    public function create()
    {
        // Récupérer tous les bungalows par type
        $bungalowsMer = Bungalow::where('typeBungalow', 'mer')->get();
        $bungalowsJardin = Bungalow::where('typeBungalow', 'jardin')->get();
        
        return view('chambres.create', compact('bungalowsMer', 'bungalowsJardin'));
    }

    /**
     * Enregistre une nouvelle réservation de séjour.
     */
    public function store(Request $request)
    {
        // Valider les entrées
        $validated = $request->validate([
            'startDate' => 'required|date|after_or_equal:today',
            'endDate' => 'required|date|after_or_equal:startDate',
            'typeBungalow' => 'required|in:mer,jardin',
            'bungalowId' => 'required|exists:bungalow,id',
            'nbrPersonnes' => 'required|integer|min:1|max:4',
        ]);
        
        $bungalow = Bungalow::findOrFail($validated['bungalowId']);
        
        // Vérifier la compatibilité du nombre de personnes
        if (!Sejour::isValidOccupancy($validated['bungalowId'], $validated['nbrPersonnes'])) {
            return back()
                ->withInput()
                ->withErrors(['nbrPersonnes' => 'Le nombre de personnes dépasse la capacité du bungalow sélectionné.']);
        }
        
        // Vérifier disponibilité
        if ($bungalow->isReserved($validated['startDate'], $validated['endDate'])) {
            return back()
                ->withInput()
                ->withErrors(['bungalowId' => 'Ce bungalow n\'est pas disponible pour les dates sélectionnées.']);
        }
        
        // Créer la réservation
        $sejour = Sejour::createReservation(
            $validated['bungalowId'],
            $validated['startDate'],
            $validated['endDate'],
            $validated['nbrPersonnes']
        );
        
        // Rediriger avec confirmation
        return redirect()->route('chambres.create')
            ->with([
                'codeResaSejour' => $sejour->codeResaSejour,
                'typeBungalow' => $bungalow->typeBungalow,
                'startDate' => $validated['startDate'],
                'endDate' => $validated['endDate'],
                'nbrPersonnes' => $validated['nbrPersonnes'],
            ]);
    }
}
