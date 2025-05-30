<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero', 'type', 'solde', 'date_creation', 'client_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function transactionSource()
    {
        return $this->hasMany(Transaction::class, 'compte_source_id');
    }

    public function cartes()
    {
        return $this->hasMany(CarteBancaire::class);
    }
    public function frais()
    {
        return $this->hasMany(FraisBancaire::class);
    }
}
