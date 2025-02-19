<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;

use App\Models\NaissHop;

class NaissanceSheet1 implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $selectedMonth;
    private $selectedYear;
    private $sousAdminId;
    private $communeAdmin;

    public function __construct(int $selectedMonth, int $selectedYear, string $communeAdmin)
    {
        $this->selectedMonth = $selectedMonth;
        $this->selectedYear = $selectedYear;
        $this->communeAdmin = $communeAdmin;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $naissances = NaissHop::where('NomEnf', $this->communeAdmin)
            ->whereMonth('created_at', $this->selectedMonth)
            ->whereYear('created_at', $this->selectedYear)
            ->get();

        return $naissances->map(function ($naissance) {
            return [
                'type' => 'Naissance',
                'codeCMN' => $naissance->codeCMN,
                'date' => $naissance->created_at,
                'Nom et prénoms-mère' => $naissance->NomM .' '.$naissance->PrM,
                'Contact-mère' => $naissance->contM,
                'Date de Naissance-mère' => $naissance->dateM,
                'Nom et prénoms-accompagnateur' => $naissance->NomP .' '.$naissance->PrP,
                'Contact-accompagnateur' => $naissance->contP,
                'Lien de parenté' => $naissance->lien,
                'Hôpital' => $naissance->NomEnf,
                'Commune' => $naissance->commune,
                'Docteur' => $naissance->sous_admin ? $naissance->sous_admin->name . ' ' . $naissance->sous_admin->prenom : 'Demandeur inconnu'
            ];
        });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Type',
            'N° CMN',
            "Date et Heure d'accouchement",
            'Nom et prénoms-mère',
            'Contact-mère',
            'Date de Naissance-mère',
            'Nom et prénoms-accompagnateur',
            'Contact-accompagnateur',
            'Lien de parenté',
            'Hôpital',
            'Commune',
            'Docteur déclarant',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Naissances';
    }
}