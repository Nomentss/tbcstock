<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit_couleur extends Model
{
    use HasFactory;

    protected $table = ('produit_couleur');

    protected $fillable = [
        'id_produit',
        'id_couleur',
    ];
}