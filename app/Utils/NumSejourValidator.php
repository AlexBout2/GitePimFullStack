<?php

namespace App\Utils;

use App\Models\Sejour;
use Carbon\Carbon;

class NumSejourValidator
{
    public static function validateSejour($sejourNumber)
    {
        // CORRECTION: Utiliser codeResaSejour au lieu de num_sejour
        $sejour = Sejour::where('codeResaSejour', $sejourNumber)->first();
        
        if (!$sejour) {
            return [
                'valid' => false,
                'message' => 'Ce numéro de séjour n\'existe pas.',
                'sejourId' => null
            ];
        }
        
        return [
            'valid' => true,
            'message' => 'Séjour validé avec succès.',
            'sejourId' => $sejour->id
        ];
    }
    
    public static function validateForActivity($sejourNumber, $activityDate = null)
    {
        // CORRECTION: Utiliser codeResaSejour au lieu de num_sejour
        $sejour = Sejour::where('codeResaSejour', $sejourNumber)->first();
        
        if (!$sejour) {
            return [
                'valid' => false,
                'message' => 'Ce numéro de séjour n\'existe pas.',
                'sejourId' => null
            ];
        }

        // Si une date d'activité est fournie, vérifier qu'elle est dans la période du séjour
        if ($activityDate) {
            try {
                $dateActivite = Carbon::parse($activityDate);
                $dateDebut = Carbon::parse($sejour->startDate);
                $dateFin = Carbon::parse($sejour->endDate);
                
                if ($dateActivite->lt($dateDebut) || $dateActivite->gt($dateFin)) {
                    return [
                        'valid' => false,
                        'message' => "La date de réservation doit être comprise dans votre période de séjour ({$dateDebut->format('d/m/Y')} au {$dateFin->format('d/m/Y')}).",
                        'sejourId' => null
                    ];
                }
            } catch (\Exception $e) {
                return [
                    'valid' => false,
                    'message' => "Erreur lors de la validation des dates: " . $e->getMessage(),
                    'sejourId' => null
                ];
            }
        }
        
        return [
            'valid' => true,
            'message' => 'Séjour et date validés avec succès.',
            'sejourId' => $sejour->id
        ];
    }
}
