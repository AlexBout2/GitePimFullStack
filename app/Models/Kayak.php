<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utils\NumSejourValidator;
use App\Utils\ReservationCodeGenerator;
use Illuminate\Support\Facades\DB;

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
        
        // 2. Vérifier la capacité totale disponible dans la table capaciteactivites
        $capaciteSimples = DB::table('capaciteactivites')
                            ->where('typeActivite', 'like', 'KayakSimple')
                            ->value('nombreUnites');
                            
        $capaciteDoubles = DB::table('capaciteactivites')
                            ->where('typeActivite', 'like', 'KayakDouble')
                            ->value('nombreUnites');
        
        if ($capaciteSimples === null || $capaciteDoubles === null) {
            throw new \Exception("Impossible de déterminer la capacité des kayaks disponibles.");
        }
        
        // 3. Vérifier les réservations existantes pour cette date et heure
        $reservationsExistantes = self::where('date', $data['date'])
                                     ->where('heure_debut', $data['heure_debut'])
                                     ->where('statut', 'confirmé')
                                     ->get();
        
        $simplesReserves = $reservationsExistantes->sum('nombre_kayaks_simples');
        $doublesReserves = $reservationsExistantes->sum('nombre_kayaks_doubles');
        
        $simplesDisponibles = $capaciteSimples - $simplesReserves;
        $doublesDisponibles = $capaciteDoubles - $doublesReserves;
        
        // Vérifier si assez de kayaks disponibles
        if ($simplesDisponibles < $data['nbr_kayaks_simples']) {
            throw new \Exception("Désolé, il n'y a pas assez de kayaks simples disponibles pour cette période.");
        }
        
        if ($doublesDisponibles < $data['nbr_kayaks_doubles']) {
            throw new \Exception("Désolé, il n'y a pas assez de kayaks doubles disponibles pour cette période.");
        }
        
        // 4. Générer un code unique pour cette réservation
        $codeGenerator = new ReservationCodeGenerator();
        $reservationCode = $codeGenerator->generateCode(
            'kayak_reservations',
            'KA',  // Préfixe pour les kayaks (corrigé de KY à KA)
            'code',
            $data['date']
        );
        
        // 5. Créer la réservation
        return self::create([
            'code' => $reservationCode,
            'sejour_id' => $sejourId,
            'date' => $data['date'],
            'heure_debut' => $data['heure_debut'],
            'duree' => 1,
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
