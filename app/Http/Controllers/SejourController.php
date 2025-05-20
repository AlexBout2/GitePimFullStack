<?php

namespace App\Http\Controllers;

use App\Models\Bungalow;
use App\Models\Sejour;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        
        // Vérifier que le nombre de personnes est compatible avec le type de bungalow
        $bungalow = Bungalow::findOrFail($validated['bungalowId']);
        
        if ($bungalow->typeBungalow == 'mer' && $validated['nbrPersonnes'] > 2) {
            return back()
                ->withInput()
                ->withErrors(['nbrPersonnes' => 'Les bungalows côté mer peuvent accueillir maximum 2 personnes.']);
        }
        
        // Vérifier disponibilité
        if ($bungalow->isReserved($validated['startDate'], $validated['endDate'])) {
            return back()
                ->withInput()
                ->withErrors(['bungalowId' => 'Ce bungalow n\'est pas disponible pour les dates sélectionnées.']);
        }
        
        // Générer un code de réservation unique
        $codeResaSejour = 'S-' . strtoupper(Str::random(6));
        
        // Créer la réservation
        $sejour = new Sejour();
        $sejour->codeResaSejour = $codeResaSejour;
        $sejour->bungalowId = $validated['bungalowId'];
        $sejour->startDate = $validated['startDate'];
        $sejour->endDate = $validated['endDate'];
        $sejour->nbrPersonnes = $validated['nbrPersonnes'];
        $sejour->save();
        
        // Rediriger avec confirmation
return redirect()->route('chambres.create')
    ->with([
        'codeResaSejour' => $codeResaSejour,
        'typeBungalow' => $validated['typeBungalow'],
        'startDate' => $validated['startDate'],
        'endDate' => $validated['endDate'],
        'nbrPersonnes' => $validated['nbrPersonnes'],
    ]);

    }
}
