<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StatsExport implements WithMultipleSheets
{
    use Exportable;

    private $selectedMonth;
    private $selectedYear;
    private $sousAdminId;
    private $communeAdmin;

    public function __construct(int $selectedMonth, int $selectedYear, int $sousAdminId, string $communeAdmin)
    {
        $this->selectedMonth = $selectedMonth;
        $this->selectedYear = $selectedYear;
        $this->sousAdminId = $sousAdminId;
        $this->communeAdmin = $communeAdmin;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new NaissanceSheet(
            $this->selectedMonth,
            $this->selectedYear,
            $this->sousAdminId,
            $this->communeAdmin
        );
        $sheets[] = new DecesSheet(
            $this->selectedMonth,
            $this->selectedYear,
            $this->sousAdminId,
            $this->communeAdmin
        );

        return $sheets;
    }
}