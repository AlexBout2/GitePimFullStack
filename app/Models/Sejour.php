<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Utils\ReservationCodeGenerator;

class Sejour extends Model
{
    use HasFactory;
    
    protected $table = 'sejour';
    
    // Désactiver les timestamps automatiques
    public $timestamps = false;
    
    protected $fillable = [
        'codeResaSejour',
        'bungalowId',
        'startDate',
        'endDate',
        'nbrPersonnes'
    ];
    
    /**
     * Génère un code de réservation unique au format CH+YYMM+000x
     *
     * @param string $startDate
     * @return string
     */
    public static function generateReservationCode($startDate)
    {
        $generator = new ReservationCodeGenerator();
        return $generator->generateCode(
            'sejour',           // Nom de la table
            'CH',               // Préfixe pour les chambres
            'codeResaSejour',   // Nom du champ contenant le code
            $startDate          // Date de départ
        );
    }
    
    /**
     * Vérifie si le bungalow est disponible pour la période donnée
     *
     * @param int $bungalowId
     * @param string $startDate
     * @param string $endDate
     * @return bool
     */
    public static function isBungalowAvailable($bungalowId, $startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();
        
        // Recherche de réservations qui se chevauchent
        $existingBooking = self::where('bungalowId', $bungalowId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('startDate', [$startDate, $endDate])
                    ->orWhereBetween('endDate', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('startDate', '<=', $startDate)
                          ->where('endDate', '>=', $endDate);
                    });
            })
            ->first();
            
        // Si aucune réservation existante n'est trouvée, le bungalow est disponible
        return $existingBooking === null;
    }
    
    /**
     * Vérifie si les dates sont valides pour une réservation
     * 
     * @param string $startDate
     * @param string $endDate
     * @return array [bool $isValid, string $errorMessage]
     */
    public static function validateDates($startDate, $endDate)
    {
        $today = Carbon::today();
        $startCarbon = Carbon::parse($startDate);
        $endCarbon = Carbon::parse($endDate);
        
        // Vérifier que la date de début est supérieure ou égale à aujourd'hui
        if ($startCarbon->lt($today)) {
            return [false, 'La date de début doit être égale ou postérieure à aujourd\'hui.'];
        }
        
        // Vérifier que la date de fin est supérieure à la date de début
        if ($endCarbon->lte($startCarbon)) {
            return [false, 'La date de fin doit être postérieure à la date de début.'];
        }
        
        return [true, ''];
    }
    
    /**
     * Crée une nouvelle réservation en vérifiant les contraintes
     * 
     * @param array $data Les données de réservation (bungalowId, startDate, endDate, nbrPersonnes)
     * @return array [bool $success, Sejour|null $sejour, string $message, string $code]
     */
    public static function createValidatedReservation($data)
    {
        // 1 & 2. Valider les dates
        $dateValidation = self::validateDates($data['startDate'], $data['endDate']);
        if (!$dateValidation[0]) {
            return [false, null, $dateValidation[1], ''];
        }
        
        // 3. Vérifier la disponibilité du bungalow
        if (!self::isBungalowAvailable($data['bungalowId'], $data['startDate'], $data['endDate'])) {
            return [false, null, 'Ce bungalow n\'est pas disponible pour les dates sélectionnées.', ''];
        }
        
        // 4. Créer le code de réservation
        $codeResaSejour = self::generateReservationCode($data['startDate']);
        
        // Créer la réservation
        try {
            $sejour = self::create([
                'codeResaSejour' => $codeResaSejour,
                'bungalowId' => $data['bungalowId'],
                'startDate' => $data['startDate'],
                'endDate' => $data['endDate'],
                'nbrPersonnes' => $data['nbrPersonnes']
            ]);
            
            // 5. Retourner le succès avec le numéro de réservation
            return [true, $sejour, 'Réservation créée avec succès.', $codeResaSejour];
        } catch (\Exception $e) {
            return [false, null, 'Erreur lors de la création de la réservation: ' . $e->getMessage(), ''];
        }
    }
    
    /**
     * Relation avec le bungalow
     */
    public function bungalow()
    {
        return $this->belongsTo(Bungalow::class, 'bungalowId');
    }
}
