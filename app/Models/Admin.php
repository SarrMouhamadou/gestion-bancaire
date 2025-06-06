<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'username', 'password', 'role'
    ];

    protected $hidden = [
        'password'
    ];

    public function tickets()
    {
        return $this->hasMany(TicketSupport::class);
    }
}
