<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CommandeExport implements FromCollection, WithHeadings
{
    protected $typeSelect;
    protected $id_artisan;
    protected $id_saison;

    public function __construct($typeSelect, $id_artisan, $id_saison)
    {
        $this->typeSelect = $typeSelect;
        $this->id_artisan = $id_artisan;
        $this->id_saison = $id_saison;
    }

    public function collection()
    {
        $query = DB::table('v_detail_commande')
            ->where('id_artisan', $this->id_artisan)
            ->where('id_saison', $this->id_saison);

        if ($this->typeSelect) {
        $query->where('type_commande', $this->typeSelect);
        }

    $detail = $query->get();

    $totalQte = $detail->sum('qte');
    $valeurTotal = $detail->sum('total');

    $data = $detail->map(function ($item) {
        return [
            $item->type_commande,
            $item->designation_produit,
            $item->designation_catalogue,
            $item->couleur,
            $item->prix_unitaire,
            $item->qte,
            $item->total,
        ];
    });

            $data->push(['', '', '', '', '', '', '']);
            $data->push(['', '', '', '', '', 'TOTAL Quantite : ' .$totalQte, 'VALEUR TOTAL : ' .$valeurTotal]);
            

            return $data;
    }

    public function headings(): array
    {
        return [
            'Type commande',
            'designation',
            'designation_catalogue',
            'Couleur',
            'Prix Unitaire',
            'Quantite',
            'Total',
        ];
    }
}