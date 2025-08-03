<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expedition_detail extends Model
{
    use HasFactory;

    protected $table = ('expedition_detail');

    protected $fillable = [
        'id_saison',
        'id_expedition',
        'id_artisan',
        'date_expedition',
        'id_produit',
        'id_couleur',
        'qte',
    ];
}
