<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleRetour extends Model
{
    use HasFactory;

    protected $table = ('article_retour');

    protected $fillable = [
        'id_saison',
        'id_artisan',
        'id_client',
    ];
    
}
