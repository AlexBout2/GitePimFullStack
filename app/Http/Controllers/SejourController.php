<?php

namespace App\Http\Controllers;

use App\Models\Bungalow;
use App\Models\Sejour;
use App\Utils\AvailabilityChecker; // Import de la classe utilitaire
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
        $bungalowsMer = Bungalow::where('typeBungalow', 'Mer')->get();
        $bungalowsJardin = Bungalow::where('typeBungalow', 'Jardin')->get();
        
        return view('chambres.create', compact('bungalowsMer', 'bungalowsJardin'));
    }

    /**
     * Endpoint pour vérifier la disponibilité d'un bungalow via AJAX
     */
    public function checkAvailability(Request $request)
    {
            $request->input('bungalowId');
            $request->input('startDate');
            $request->input('endDate'); 
            
            $checker = new \App\Utils\AvailabilityChecker();
            $available = $checker->checkBungalowAvailability($bungalowId, $startDate, $endDate);
    return response()->json([
        'available' => $available
    ]);
 
    }

    public function store(Request $request)
    {
        // Validation des données (inchangé)
        $validated = $request->validate([
            'startDate' => 'required|date|after_or_equal:today',
            'endDate' => 'required|date|after_or_equal:startDate',
            'bungalowType' => 'required|in:Mer,Jardin',
            'personCount' => 'required|integer|min:1|max:10',
            'bungalowMerId' => 'required_if:bungalowType,Mer|nullable|string',
            'bungalowJardinId' => 'required_if:bungalowType,Jardin|nullable|string',
        ]);
        
        // Déterminer le type de bungalow et l'identifiant choisi
        $bungalowType = $validated['bungalowType'];
        $bungalowId = ($bungalowType == 'Mer') 
            ? $validated['bungalowMerId'] 
            : $validated['bungalowJardinId'];
        
        // Vérifier que l'identifiant existe
        $bungalow = Bungalow::where('codeBungalow', $bungalowId)
                          ->where('typeBungalow', $bungalowType)
                          ->first();
        
        if (!$bungalow) {
            return back()
                ->withInput()
                ->withErrors(['bungalow' => 'Le bungalow sélectionné n\'existe pas ou ne correspond pas au type choisi.']);
        }
        
        // Vérification de capacité (inchangé)
        if ($validated['personCount'] > $bungalow->capacite) {
            return back()
                ->withInput()
                ->withErrors(['personCount' => 'Le nombre de personnes dépasse la capacité du bungalow sélectionné (' . $bungalow->capacite . ' personnes maximum).']);
        }
        
        // Vérification de disponibilité avec AvailabilityChecker
        $availability = AvailabilityChecker::checkBungalowAvailability(
            $bungalowId,
            $validated['startDate'],
            $validated['endDate']
        );
        
        // Convertir la réponse JSON en tableau
        $availabilityData = json_decode($availability->getContent(), true);
        
        // Si le bungalow n'est pas disponible, retourner une erreur
        if (!$availabilityData['available']) {
            return back()
                ->withInput()
                ->withErrors(['dates' => $availabilityData['message']]);
        }
        
        // Préparation des données pour le modèle
        $sejourData = [
            'bungalowId' => $bungalow->id,
            'startDate' => $validated['startDate'],
            'endDate' => $validated['endDate'],
            'nbrPersonnes' => $validated['personCount'],
        ];
        
        try {
            // Délégation de la création au modèle avec la méthode existante
            $result = Sejour::createValidatedReservation($sejourData);
            
            if (!$result[0]) {
                // La création a échoué
                return back()
                    ->withInput()
                    ->withErrors(['system' => $result[2]]);
            }
            
            // La création a réussi
            $sejour = $result[1];
            $codeResaSejour = $result[3];
            
            // Calcul de la durée du séjour
            $duration = Carbon::parse($validated['startDate'])->diffInDays(Carbon::parse($validated['endDate']));
            
            // Redirection avec message de succès
            return redirect()->route('chambres.create')->with([
                'reservation' => [
                    'number' => $codeResaSejour,
                    'type' => $bungalowType,
                    'startDate' => $validated['startDate'],
                    'endDate' => $validated['endDate'],
                    'personCount' => $validated['personCount'],
                    'duration' => $duration,
                ],
                'success' => 'Votre réservation a été enregistrée avec succès! Votre code de réservation est ' . $codeResaSejour,
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création du séjour: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->withErrors(['system' => 'Une erreur est survenue lors de l\'enregistrement de votre réservation. Veuillez réessayer.']);
        }
    }
}
