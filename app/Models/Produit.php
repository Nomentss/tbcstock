<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $table = ('produit');

    protected $fillable = [
        'id_artisan',
        'designation_produit',
        'designation_catalogue',
    ];
}