<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kayak extends Model
{
    use HasFactory;

    // Spécifier le nom de la table explicitement
    protected $table = 'kayak';
    
    // Définir les champs qui peuvent être assignés en masse
    protected $fillable = [
        'codeResaKayak',
        'dateReservation',
        'creneauKayak',
        'nbKayakSimple',
        'nbKayakDouble',
        'nbPersonnesTotales',
        'sejourId'
    ];
    
    // Si vos colonnes de date/heure doivent être converties en objets Carbon
    protected $dates = [
        'dateReservation'
    ];
    
    // Relation avec le séjour
    public function sejour()
    {
        return $this->belongsTo(Sejour::class, 'sejourId');
    }
    
    // Méthode statique pour vérifier la disponibilité
    public static function checkAvailability($date, $creneau)
    {
        // Récupérer la capacité totale depuis la table capaciteactivites
        $capaciteSimples = \DB::table('capaciteactivites')
                           ->where('typeActivite', 'KayakSimple')
                           ->value('nombreUnites');
                           
        $capaciteDoubles = \DB::table('capaciteactivites')
                           ->where('typeActivite', 'KayakDouble')
                           ->value('nombreUnites');
        
        // Récupérer les kayaks déjà réservés pour cette date et ce créneau
        $reservations = self::where('dateReservation', $date)
                           ->where('creneauKayak', $creneau)
                           ->get();
        
        $simplesReserves = $reservations->sum('nbKayakSimple');
        $doublesReserves = $reservations->sum('nbKayakDouble');
        
        return [
            'simplesDisponibles' => $capaciteSimples - $simplesReserves,
            'doublesDisponibles' => $capaciteDoubles - $doublesReserves,
            'creneauDisponible' => ($simplesDisponibles > 0 || $doublesDisponibles > 0)
        ];
    }
    
    // Méthode pour créer une nouvelle réservation de kayak
    public static function createReservation($data)
    {
        // Vérifier que le séjour existe
        $sejour = Sejour::where('codeResaSejour', $data['sejour_number'])->first();
        if (!$sejour) {
            throw new \Exception("Le séjour spécifié n'existe pas.");
        }
        
        // Vérifier que la date est dans la période du séjour
        $dateReservation = \Carbon\Carbon::parse($data['date']);
        $startDate = \Carbon\Carbon::parse($sejour->startDate);
        $endDate = \Carbon\Carbon::parse($sejour->endDate);
        
        if ($dateReservation->lt($startDate) || $dateReservation->gt($endDate)) {
            throw new \Exception("La date de réservation doit être comprise dans votre période de séjour ({$startDate->format('d/m/Y')} au {$endDate->format('d/m/Y')}).");
        }
        
        // Vérifier la disponibilité
        $disponibilite = self::checkAvailability($data['date'], $data['creneauKayak']);
        
        if ($data['nbr_kayaks_simples'] > $disponibilite['simplesDisponibles']) {
            throw new \Exception("Il n'y a pas assez de kayaks simples disponibles pour cette date et ce créneau.");
        }
        
        if ($data['nbr_kayaks_doubles'] > $disponibilite['doublesDisponibles']) {
            throw new \Exception("Il n'y a pas assez de kayaks doubles disponibles pour cette date et ce créneau.");
        }
        
        // Générer un code de réservation unique
        $codeGenerator = new \App\Utils\ReservationCodeGenerator();
        $reservationCode = $codeGenerator->generateCode(
            'kayak',
            'KA',
            'codeResaKayak',
            $data['date']
        );
        
        // Créer la réservation
        return self::create([
            'codeResaKayak' => $reservationCode,
            'dateReservation' => $data['date'],
            'creneauKayak' => $data['creneauKayak'],
            'nbKayakSimple' => $data['nbr_kayaks_simples'],
            'nbKayakDouble' => $data['nbr_kayaks_doubles'],
            'nbPersonnesTotales' => $data['nbrPersonnes'],
            'sejourId' => $sejour->id
        ]);
    }
}
