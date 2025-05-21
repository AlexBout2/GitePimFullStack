<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sejour extends Model
{
    use HasFactory;
    
    protected $table = 'sejour';
    
    protected $fillable = [
        'codeResaSejour',
        'bungalowId',
        'startDate',
        'endDate',
        'nbrPersonnes'
    ];
    
    /**
     * Génère un code de réservation unique au format CH+YYMM+000x
     */
    public static function generateReservationCode($startDate)
    {
        $date = Carbon::parse($startDate);
        $prefix = 'CH'; // Préfixe pour Chambre
        $yearMonth = $date->format('ym'); // Format année-mois (comme 2505 pour mai 2025)
        
        // Recherche du dernier code utilisé ce mois-ci
        $lastCodeForMonth = self::where('codeResaSejour', 'like', $prefix . $yearMonth . '%')
            ->orderBy('codeResaSejour', 'desc')
            ->value('codeResaSejour');
        
        if ($lastCodeForMonth) {
            // Extraction du numéro séquentiel et incrémentation
            $lastNumber = (int)substr($lastCodeForMonth, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        // Formatage avec zéros à gauche
        $sequential = str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        
        return $prefix . $yearMonth . $sequential;
    }
    
    /**
     * Vérifie si le nombre de personnes est compatible avec le type de bungalow
     */
    public static function isValidOccupancy($bungalowId, $nbrPersonnes)
    {
        $bungalow = Bungalow::findOrFail($bungalowId);
        
        // Vérification spécifique pour les bungalows côté mer (max 2 personnes)
        if ($bungalow->typeBungalow == 'mer' && $nbrPersonnes > 2) {
            return false;
        }
        
        // Vérification générale de capacité
        return $nbrPersonnes <= $bungalow->capacite;
    }
    
    /**
     * Crée une nouvelle réservation
     */
    public static function createReservation($bungalowId, $startDate, $endDate, $nbrPersonnes)
    {
        $codeResaSejour = self::generateReservationCode($startDate);
        
        $sejour = new self();
        $sejour->codeResaSejour = $codeResaSejour;
        $sejour->bungalowId = $bungalowId;
        $sejour->startDate = $startDate;
        $sejour->endDate = $endDate;
        $sejour->nbrPersonnes = $nbrPersonnes;
        $sejour->save();
        
        return $sejour;
    }
    
    /**
     * Relation avec le bungalow
     */
    public function bungalow()
    {
        return $this->belongsTo(Bungalow::class, 'bungalowId');
    }
}
