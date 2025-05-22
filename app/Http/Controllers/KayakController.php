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

public function validateSejourNumber(Request $request)
    {
        try {
            $numSejour = $request->input('sejour_number');
            
            if (empty($numSejour)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Le numéro de séjour est requis'
                ]);
            }
            
            // Validation du numéro de séjour uniquement
            $sejourValidation = NumSejourValidator::validateSejour($numSejour);
            
            return response()->json($sejourValidation);
        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Erreur lors de la validation: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Valide le séjour et la date d'activité (utilisée pour la validation finale)
     */
    public function validateSejour(Request $request)
    {
        try {
            $numSejour = $request->input('sejour_number');
            $dateActivite = $request->input('date');

            if (empty($dateActivite)) {
                return response()->json([
                    'valid' => false, 
                    'message' => 'La date est requise'
                ]);
            }
            
            // Validation complète (séjour + date)
            $sejourValidation = NumSejourValidator::validateForActivity($numSejour, $dateActivite);
            
            return response()->json($sejourValidation);
        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Erreur lors de la validation: ' . $e->getMessage()
            ]);
        }
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
    try {
        // Validation des données
        $validated = $request->validate([
            'sejour_number' => 'required|string',
            'date' => 'required|date',
            'heure_debut' => 'required|integer|min:9|max:15',
            'nbrPersonnes' => 'required|integer|min:1|max:8',
            'nbr_kayaks_simple' => 'required|integer|min:0|max:2',
            'nbr_kayaks_double' => 'required|integer|min:0|max:3',
        ]);

        // Vérifier que le séjour existe
        $sejour = \App\Models\Sejour::where('codeResaSejour', $validated['sejour_number'])->first();
        if (!$sejour) {
            throw new \Exception('Séjour introuvable.');
        }

        // Vérifier que la date est dans la période du séjour
        $dateReservation = \Carbon\Carbon::parse($validated['date']);
        $startDate = \Carbon\Carbon::parse($sejour->startDate);
        $endDate = \Carbon\Carbon::parse($sejour->endDate);
        
        if ($dateReservation->lt($startDate) || $dateReservation->gt($endDate)) {
            throw new \Exception("La date de réservation doit être comprise dans votre période de séjour.");
        }

        // Vérifier que les kayaks sont disponibles
        // (Code simplifié pour l'exemple)

        // Créer la réservation
        $kayak = new \App\Models\Kayak();
        $kayak->codeResaKayak = 'KA' . date('ymd') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $kayak->dateReservation = $validated['date'];
        $kayak->creneauKayak = $validated['heure_debut'];
        $kayak->nbKayakSimple = $validated['nbr_kayaks_simple'];
        $kayak->nbKayakDouble = $validated['nbr_kayaks_double'];
        $kayak->nbPersonnesTotales = $validated['nbrPersonnes'];
        $kayak->sejourId = $sejour->id;
        $kayak->save();

        // Redirection avec un message de succès
        return redirect()->route('kayak.show', ['id' => $kayak->id])
            ->with('success', 'Votre réservation de kayak a été créée avec succès!');
    
            Log::info('Données du formulaire kayak:', $request->all());
    } catch (\Exception $e) {
        \Log::error('Erreur lors de la réservation de kayak: ' . $e->getMessage());
        return back()
            ->withInput()
            ->withErrors(['error' => 'Une erreur est survenue lors de la réservation: ' . $e->getMessage()]);
    }
}

}
