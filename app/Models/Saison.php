<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saison extends Model
{
    use HasFactory;

    protected $table =('saison');

    protected $fillable = [
        'debut',
        'fin',
        'nom_saison',
    ];

}