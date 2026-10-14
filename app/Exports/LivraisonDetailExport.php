<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LivraisonDetailExport implements FromCollection, WithHeadings
{
    protected $typeSelect;
    protected $id_artisan;
    protected $id_saison;

    public function __construct($date_select, $id_artisan, $id_saison)
    {
        $this->date_select = $date_select;
        $this->id_artisan = $id_artisan;
        $this->id_saison = $id_saison;
    }

    public function collection()
    {
        $query = DB::table('v_commande_livraison')
                 ->where('id_artisan', $this->id_artisan)
                 ->where('id_saison', $this->id_saison)
                 ->where('id_client', session('id_client'))
                 ->orderBy('date_livraison', 'asc');

        if ($this->date_select) {
            $query->where('date_livraison', $this->date_select);
        }

        $detail = $query->get();

    $data = $detail->map(function ($item) {
        return [
            $item->designation_produit,
            $item->designation_catalogue,
            $item->couleur,
            $item->prix_unitaire,
            $item->qte_livre,
            $item->valeur,
            $item->date_livraison,
        ];
    });     
        return $data;
    }

    public function headings(): array
    {
        return [
            'designation_produit',
            'designation_catalogue',
            'Couleur',
            'Prix Unitaire',
            'Quantite Livre',
            'valeur',
            'Date livraison',
        ];
    }
}