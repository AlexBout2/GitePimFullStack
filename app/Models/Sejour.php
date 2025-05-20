<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sejour extends Model
{
    use HasFactory;
    
    protected $table = 'sejour';
    
    public $timestamps = false;
    
    protected $fillable = [
        'codeResaSejour',
        'bungalowId',
        'startDate',
        'endDate',
        'nbrPersonnes'
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            // Génération automatique du code de réservation pour le séjour
            $model->codeResaSejour = 'SEJ-' . strtoupper(Str::random(6));
        });
    }
    
    /**
     * Relation avec le bungalow
     */
    public function bungalow()
    {
        return $this->belongsTo(Bungalow::class, 'bungalowId');
    }
    
    /**
     * Relations avec les différentes activités
     */
    public function kayaks()
    {
        return $this->hasMany(Kayak::class, 'sejourId');
    }
    
    public function randosCheval()
    {
        return $this->hasMany(RandoCheval::class, 'sejourId');
    }
    
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, 'sejourId');
    }
    
    public function garderies()
    {
        return $this->hasMany(Garderie::class, 'sejourId');
    }
    
    public function bagnes()
    {
        return $this->hasMany(Bagne::class, 'sejourId');
    }
    
    /**
     * Vérifie si le séjour est en cours ou à venir
     */
    public function getEstActifAttribute()
    {
        return $this->endDate >= date('Y-m-d');
    }
    
    /**
     * Obtenir toutes les réservations d'activités associées au séjour
     * Utile pour une page admin de récapitulatif
     */
    public function getAllActivites()
    {
        $activites = [
            'kayaks' => $this->kayaks()->get(),
            'randosCheval' => $this->randosCheval()->get(),
            'restaurants' => $this->restaurants()->get(),
            'garderies' => $this->garderies()->get(),
            'bagnes' => $this->bagnes()->get()
        ];
        
        return $activites;
    }
    
    /**
     * Trouve un séjour par son code de réservation
     */
    public static function findByCode($codeResaSejour)
    {
        return self::where('codeResaSejour', $codeResaSejour)->first();
    }
}
