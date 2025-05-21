<?php

namespace App\Utils;

use App\Models\Bungalow;
use App\Models\Sejour;
use Illuminate\Http\JsonResponse;

class AvailabilityChecker
{
    /**
     * Vérifie la disponibilité d'un bungalow pour des dates spécifiques
     *
     * @param string $bungalowId
     * @param string $startDate
     * @param string $endDate
     * @return JsonResponse
     */
    public static function checkBungalowAvailability($bungalowId, $startDate, $endDate): JsonResponse
    {
        if (!$bungalowId || !$startDate || !$endDate) {
            return response()->json([
                'available' => false,
                'message' => 'Données incomplètes pour la vérification'
            ]);
        }
        
        $bungalow = Bungalow::where('codeBungalow', $bungalowId)->first();
        
        if (!$bungalow) {
            return response()->json([
                'available' => false,
                'message' => 'Bungalow introuvable'
            ]);
        }
        
        $isAvailable = Sejour::isBungalowAvailable($bungalow->id, $startDate, $endDate);
        
        return response()->json([
            'available' => $isAvailable,
            'message' => $isAvailable 
                ? 'Ce bungalow est disponible pour les dates sélectionnées' 
                : 'Ce bungalow est déjà réservé pour les dates sélectionnées'
        ]);
    }
    
    /**
     * Structure extensible pour d'autres vérifications de disponibilité
     * Exemple: checkActivityAvailability(), checkTableAvailability(), etc.
     */
}
