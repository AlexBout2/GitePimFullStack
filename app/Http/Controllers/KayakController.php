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
            // Validation des données d'entrée
            $request->validate([
                'sejour_number' => 'required|string',
                'date' => 'required|date',
                'creneauKayak' => 'required|integer|min:1|max:4',
                'nbrPersonnes' => 'required|integer|min:1',
                'nbr_kayaks_simples' => 'required|integer|min:0',
                'nbr_kayaks_doubles' => 'required|integer|min:0'
            ]);
            
            // Vérifier que la somme des kayaks n'est pas nulle
            if ($request->nbr_kayaks_simples + $request->nbr_kayaks_doubles <= 0) {
                return back()->withErrors(['kayaks' => 'Vous devez réserver au moins un kayak.']);
            }
            
            // Créer la réservation en utilisant notre modèle
            $reservation = Kayak::createReservation($request->all());
            
            // Redirection avec message de succès
            return redirect()->route('kayak.confirmation', ['code' => $reservation->codeResaKayak])
                             ->with('success', 'Votre réservation a été confirmée!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
