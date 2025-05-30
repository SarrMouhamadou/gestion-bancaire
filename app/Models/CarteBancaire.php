<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarteBancaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero', 'cvv', 'date_expiration', 'solde', 'statut', 'compte_id', 'code_pin'
    ];

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }
}
