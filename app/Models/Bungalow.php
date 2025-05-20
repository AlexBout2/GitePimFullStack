<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Bungalow extends Model
{
    use HasFactory;
    
    protected $table = 'bungalow';
    
    protected $fillable = [
        'codeBungalow', 
        'typeBungalow',
        'capacite'
    ];
    
    /**
     * Vérifie si le bungalow est réservé pour les dates spécifiées.
     */
    public function isReserved($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        // Vérifier s'il y a déjà des réservations qui se chevauchent
        return Sejour::where('bungalowId', $this->id)
            ->where(function($query) use ($start, $end) {
                $query->where(function($q) use ($start, $end) {
                    // Cas 1: La nouvelle réservation englobe une réservation existante
                    $q->where('startDate', '>=', $start)
                      ->where('endDate', '<=', $end);
                })->orWhere(function($q) use ($start, $end) {
                    // Cas 2: La nouvelle réservation commence pendant une réservation existante
                    $q->where('startDate', '<=', $start)
                      ->where('endDate', '>=', $start);
                })->orWhere(function($q) use ($start, $end) {
                    // Cas 3: La nouvelle réservation se termine pendant une réservation existante
                    $q->where('startDate', '<=', $end)
                      ->where('endDate', '>=', $end);
                })->orWhere(function($q) use ($start, $end) {
                    // Cas 4: Une réservation existante englobe la nouvelle réservation
                    $q->where('startDate', '<=', $start)
                      ->where('endDate', '>=', $end);
                });
            })
            ->exists();
    }
    
    /**
     * Relation avec les séjours
     */
    public function sejours()
    {
        return $this->hasMany(Sejour::class, 'bungalowId');
    }
}
