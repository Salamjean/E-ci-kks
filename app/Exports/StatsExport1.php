<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StatsExport1 implements WithMultipleSheets
{
    use Exportable;

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
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new NaissanceSheet1(
            $this->selectedMonth,
            $this->selectedYear,
            $this->communeAdmin
        );
        $sheets[] = new DecesSheet1(
            $this->selectedMonth,
            $this->selectedYear,
            $this->communeAdmin
        );

        return $sheets;
    }
}