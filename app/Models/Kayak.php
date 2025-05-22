<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utils\NumSejourValidator;
use App\Utils\ReservationCodeGenerator;

class KayakReservation extends Model
{
    use HasFactory;

    protected $table = 'kayak_reservations';
    
    protected $fillable = [
        'code',
        'sejour_id',
        'date',
        'heure_debut',
        'duree',
        'nombre_personnes',
        'nombre_kayaks_simples',
        'nombre_kayaks_doubles',
        'statut'
    ];

    /**
     * Crée une nouvelle réservation de kayak après validation des données
     *
     * @param array $data Les données validées du formulaire
     * @return KayakReservation
     */
    public static function createReservation(array $data)
    {
        // 1. Valider le numéro de séjour et récupérer le séjour associé
        $sejourValidation = NumSejourValidator::validateForActivity(
            $data['sejour_number'], 
            $data['date']
        );
        
        if (!$sejourValidation['valid']) {
            throw new \Exception($sejourValidation['message']);
        }
        
        $sejourId = $sejourValidation['sejourId'];
        
        // 2. Générer un code unique pour cette réservation
        $codeGenerator = new ReservationCodeGenerator();
        $reservationCode = $codeGenerator->generateCode(
            'kayak_reservations',
            'KA',  // Préfixe pour les kayaks
            'code',
            $data['date']
        );
        
        // 3. Créer la réservation
        return self::create([
            'code' => $reservationCode,
            'sejour_id' => $sejourId,
            'date' => $data['date'],
            'heure_debut' => $data['heure_debut'],
            'duree' => $data['duree'],
            'nombre_personnes' => $data['nbrPersonnes'],
            'nombre_kayaks_simples' => $data['nbr_kayaks_simples'],
            'nombre_kayaks_doubles' => $data['nbr_kayaks_doubles'],
            'statut' => 'confirmé'  // Statut par défaut
        ]);
    }
    
    /**
     * Relation avec le modèle Sejour
     */
    public function sejour()
    {
        return $this->belongsTo(Sejour::class, 'sejour_id');
    }
}
