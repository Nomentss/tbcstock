<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleRetourDetail extends Model
{
    use HasFactory;

    protected $table = ('article_retour_detail');

    protected $fillable = [
        'id_article_retour',
        'id_produit',
        'id_couleur',
        'qte',
        'date_retour',
        'bon_sortie',
    ];
}
