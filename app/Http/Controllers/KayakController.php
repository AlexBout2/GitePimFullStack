<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\AvailabilityChecker;
use App\Utils\NumSejourValidator;
use Carbon\Carbon; 

class KayakController extends Controller
{
    public function index()
    {
        return view('kayak.index');
    }

    public function create()
    {
        return view('kayak.create');
    }

    public function validateSejour(Request $request)
    {
        $numSejour = $request->input('sejour_number');
        $dateActivite = $request->input('date');
        
        $sejourValidation = NumSejourValidator::validateForActivity($numSejour, $dateActivite);
        
        return response()->json($sejourValidation);
    }

    /**
     * Endpoint pour vérifier la disponibilité des kayaks via AJAX
     */
    public function checkAvailability(Request $request)
    {
        $date = $request->input('date');
        $heure = $request->input('heure_debut');
        
        // Utiliser la classe utilitaire pour vérifier la disponibilité
        return AvailabilityChecker::checkKayakAvailability($date, $heure);
    }

    public function store(Request $request)
    {
        // 1. VÉRIFICATION DES DONNÉES DU FORM
        $validated = $request->validate([
            'sejour_number' => 'required|string|regex:/^CH\d{6}$/', 
            'date' => 'required|date|after_or_equal:today',
            'heure_debut' => 'required|integer|min:9|max:15',
            'duree' => 'required|integer|in:1', // Durée fixe d'une heure
            'nbrPersonnes' => 'required|integer|min:1|max:8',
            'nbr_kayaks_simples' => 'required|integer|min:0',
            'nbr_kayaks_doubles' => 'required|integer|min:0',
        ]);
        
        // Vérifier la validité du séjour et de la date
        $sejourValidation = NumSejourValidator::validateForActivity(
            $validated['sejour_number'], 
            $validated['date']
        );
        
        if (!$sejourValidation['valid']) {
            return back()
                ->withInput()
                ->withErrors(['sejour_number' => $sejourValidation['message']]);
        }

        // Vérifications supplémentaires des règles métier
        $totalPersonnes = $validated['nbrPersonnes'];
        $totalCapacite = $validated['nbr_kayaks_simples'] + ($validated['nbr_kayaks_doubles'] * 2);

        if ($totalCapacite < $totalPersonnes) {
            return back()->withErrors(['nbr_kayaks_simples' => 'Nombre insuffisant de kayaks pour toutes les personnes'])->withInput();
        }

        if ($totalCapacite > $totalPersonnes) {
            return back()->withErrors(['nbr_kayaks_simples' => 'Capacité des kayaks supérieure au nombre de personnes'])->withInput();
        }

        if ($validated['nbr_kayaks_simples'] == 0 && $validated['nbrPersonnes'] == 1) {
            return back()->withErrors(['nbrPersonnes' => 'Les personnes seules ne peuvent pas réserver uniquement des kayaks doubles'])->withInput();
        }

        // Vérifier la disponibilité une dernière fois
        $availabilityCheck = AvailabilityChecker::checkKayakAvailability(
            $validated['date'], 
            $validated['heure_debut']
        );
        
        $availabilityData = json_decode($availabilityCheck->getContent(), true);
        
        if (!$availabilityData['available']) {
            return back()
                ->withInput()
                ->withErrors(['heure_debut' => $availabilityData['message']]);
        }

        // 2. TRANSMISSION AU MODÈLE
        try {
            $reservation = KayakReservation::createReservation($validated);
            
            // Pour l'instant, simulons le succès de l'opération
            return redirect()->route('kayak.index')->with('success', 
                'Votre demande de réservation de kayak a été soumise avec succès.');
                
        } catch (\Exception $e) {
            // En cas d'erreur lors de l'enregistrement
            return back()
                ->withInput()
                ->withErrors(['system' => 'Une erreur est survenue lors du traitement de votre réservation. Veuillez réessayer.']);
        }
    }
}
