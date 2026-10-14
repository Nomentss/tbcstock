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
        'id_client',
        'type_payement',
        'detail_payement',
        'numero_piece_cheque',
        'montant',
        'date_payement',
    ];
}