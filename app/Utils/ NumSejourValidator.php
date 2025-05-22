<?php

namespace App\Utils;

use App\Models\Sejour;
use Carbon\Carbon;

class NumSejourValidator
{
    /**
     * Vérifie si un numéro de séjour est valide et si la date d'activité est dans la période du séjour
     *
     * @param string $numSejour Le numéro de séjour à vérifier
     * @param string $dateActivite La date prévue pour l'activité
     * @return array Un tableau contenant le statut de validation et un message
     */
    public static function validateForActivity(string $numSejour, string $dateActivite): array
    {
        // 1. Vérifier l'existence du numéro de séjour
        $sejour = Sejour::where('codeResaSejour', $numSejour)->first();
        
        if (!$sejour) {
            return [
                'valid' => false,
                'message' => 'Numéro de séjour invalide. Vérifiez votre numéro et réessayez.'
            ];
        }
        
        // 2. Vérifier si la date d'activité est comprise dans la période de séjour
        $dateActivite = Carbon::parse($dateActivite);
        $startDate = Carbon::parse($sejour->dateArrivee);
        $endDate = Carbon::parse($sejour->dateDepart);
        
        if ($dateActivite->lt($startDate) || $dateActivite->gt($endDate)) {
            return [
                'valid' => false,
                'message' => 'La date de réservation doit être comprise dans votre période de séjour (' . 
                             $startDate->format('d/m/Y') . ' au ' . $endDate->format('d/m/Y') . ').'
            ];
        }
        
        // Si tout est valide, renvoyer un statut positif
        return [
            'valid' => true,
            'message' => 'Numéro de séjour validé pour cette date.',
            'sejourId' => $sejour->id // Peut être utile pour lier l'activité au séjour
        ];
    }
}
