<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;

use App\Models\DecesHop;

class DecesSheet1 implements FromCollection, WithHeadings, ShouldAutoSize
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
        $deces = DecesHop::where('nomHop', $this->communeAdmin)
            ->whereMonth('created_at', $this->selectedMonth)
            ->whereYear('created_at', $this->selectedYear)
            ->get();

        return $deces->map(function ($dece) {
            return [
                'type' => 'Décès',
                'codeCMD' => $dece->codeCMD,
                'date' => $dece->created_at,
                'Hôpital' => $dece->nomHop,
                'commune' => $dece->commune,
                'Nom et prénom du défunt(e)' => $dece->NomM .' '.$dece->PrM,
                'Date-Naissance' => $dece->DateNaissance ?? 'N/A',
                'Date-Deces' => $dece->DateDeces ?? 'N/A',
                'causes' => $dece->Remarques ?? 'N/A',
                'Docteur' => $dece->sous_admin ? $dece->sous_admin->name . ' ' . $dece->sous_admin->prenom : 'Demandeur inconnu'

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
            "N° CMD",
            'Date et heure-Déclaration',
            'Hôpital',
            'Commune',
            'Nom et prénom du défunt(e)',
            'Date de naissance',
            'Date de décès',
            'Cause du décès',
            'Docteur declarant',
            // ... other headings for DecesHop
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Décès';
    }
}