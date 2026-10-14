<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;


class StockGlobaleExport implements FromCollection, WithHeadings
{
    protected $saison_select;
    protected $artisan_select;
    protected $catalogue_select;

    public function __construct($saison_select, $artisan_select, $catalogue_select)
    {
        $this->saison_select = $saison_select;
        $this->artisan_select = $artisan_select;
        $this->catalogue_select = $catalogue_select;
    }

    public function collection()
    {
        $query = DB::table('v_stock_commande')
                ->where('id_client', session('id_client'));

        $query2 = DB::table('v_stock_commande')
                ->where('id_client', session('id_client'))
                ->selectRaw('SUM(qte_livre) as qte, SUM(qte_livre*prix_unitaire) as total');

        if($this->saison_select){
            $query->where('id_saison', $this->saison_select);
            $query2->where('id_saison', $this->saison_select);
        }

        if($this->artisan_select){
            $query->where('id_artisan', $this->artisan_select);
            $query2->where('id_artisan', $this->artisan_select);
        }

        if($this->catalogue_select){
            $query->whereRaw('UPPER(designation_catalogue) = UPPER(?)', [$this->catalogue_select]);
            $query2->whereRaw('UPPER(designation_catalogue) = UPPER(?)', [$this->catalogue_select]);

        }

        $detail = $query->get();

        $total = $query2->first();

    $data = $detail->map(function ($item) {
        return [
            $item->nom_saison,
            $item->artisan,
            $item->designation_produit,
            $item->designation_catalogue,
            $item->couleur,
            $item->qte_livre,
            $item->prix_unitaire,
        ];
    });

            $data->push(['', '', '', '', '', '', '']);
            $data->push(['', '', '', '', '', 'TOTAL Quantite : ' .$total->qte ?? 0, 'VALEUR TOTAL : ' .$total->total ?? 0]);
            

            return $data;
    }

    public function headings(): array
    {
        return [
            'Saison',
            'Artisan',
            'designation produit',
            'designation catalogue',
            'Couleur',
            'Quantite restant',
            'Prix unitaire',
        ];
    }
}
