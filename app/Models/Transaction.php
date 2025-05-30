<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'montant', 'date', 'compte_source_id', 'compte_dest_id', 'statut'
    ];

    public function compteSource()
    {
        return $this->belongsTo(Compte::class, 'compte_source_id');
    }

    public function compteDest()
    {
        return $this->belongsTo(Compte::class, 'compte_dest_id');
    }


}
