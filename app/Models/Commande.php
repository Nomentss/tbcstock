<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $table = ('commande');

    protected $fillable = [
        'id_saison',
        'id_artisan',
        'id_client',
        'type_commande',
        'date_commande',
    ];
}