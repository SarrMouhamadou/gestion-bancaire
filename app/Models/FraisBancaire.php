<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FraisBancaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'compte_id', 'type', 'montant', 'date', 'statut'
    ];

    public function compte()
    {
        $this->belongsTo(Compte::class);
    }
}
