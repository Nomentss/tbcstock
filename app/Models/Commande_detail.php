<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande_detail extends Model
{
    use HasFactory;

    protected $table = ('commande_detail');

    protected $fillable = [
        'id_commande',
        'id_produit',
        'id_couleur',
        'qte',
        'prix_unitaire',
    ];
}
