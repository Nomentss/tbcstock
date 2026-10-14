<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table =('stock');

    protected $fillable = [
        'id_saison',
        'id_client',
        'id_produit',
        'id_couleur',
        'qte_livre',
        'date_livraison',
    ];
}