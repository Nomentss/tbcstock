<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payement extends Model
{
    use HasFactory;

    protected $table = ('payement_artisan');

    protected $fillable = [
        'id_saison',
        'id_artisan',
        'detail_payement',
        'montant',
        'date_payement',
    ];
}