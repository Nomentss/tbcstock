<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livraison_detail extends Model
{
    use HasFactory;

    protected $table = ('livraison_detail');

    protected $fillable = [
        'id_livraison',
        'id_produit',
        'id_couleur',
        'qte_livre',
        'date_livraison',
        'bon_reception',
    ];
}
