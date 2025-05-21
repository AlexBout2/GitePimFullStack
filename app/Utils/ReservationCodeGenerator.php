<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationCodeGenerator
{
    /**
     * Génère un code de réservation unique pour n'importe quelle entité
     * 
     * @param string $tableName Nom de la table où chercher les codes existants
     * @param string $prefix Préfixe spécifique à l'activité (CH, AC, etc.)
     * @param string $codeField Nom du champ contenant le code dans la table
     * @param string $date Date à partir de laquelle extraire YY et MM
     * @return string
     */
    public function generateCode($tableName, $prefix, $codeField, $date)
    {
        $dateCarbon = Carbon::parse($date);
        $yearMonth = $dateCarbon->format('ym');
        
        // Recherche du dernier code utilisé ce mois-ci dans la table spécifiée
        $lastCode = DB::table($tableName)
            ->where($codeField, 'like', $prefix . $yearMonth . '%')
            ->orderBy($codeField, 'desc')
            ->value($codeField);
        
        if ($lastCode) {
            // Extraction du numéro séquentiel et incrémentation
            $lastNumber = (int)substr($lastCode, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        // Formatage avec zéros à gauche
        $sequential = str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        
        return $prefix . $yearMonth . $sequential;
    }
}
