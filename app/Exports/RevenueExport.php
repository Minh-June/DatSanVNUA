<?php

namespace App\Exports;

use App\Models\OrderDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class RevenueExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    protected $from;
    protected $to;
    protected $filterLabel;
    protected $totalRevenue;
    protected $data = [];

    public function __construct($from, $to, $filterLabel, $totalRevenue)
    {
        $this->from = $from;
        $this->to = $to;
        $this->filterLabel = $filterLabel;
        $this->totalRevenue = $totalRevenue;
    }

    public function collection()
    {
        $grouped = OrderDetail::whereBetween('date', [$this->from, $this->to])
            ->whereHas('order', fn($q) => $q->where('status', 1))
            ->with('yard')
            ->get()
            ->groupBy('yard.name')
            ->map(function ($group, $yardName) {
                return [
                    $yardName, '', '', number_format($group->sum('price'), 0, ',', '.') . ' VNÄ', '', ''
                ];
            })->values();

        return collect($grouped);
    }

    public function headings(): array
    {
        return [
            'TĂªn sĂ¢n', '', '', 'Doanh thu tá»«ng sĂ¢n', '', ''
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Gá»™p Ă´ dĂ²ng 1
                $sheet->mergeCells('A1:C1');
                $sheet->setCellValue('A1', "Tá»•ng doanh thu {$this->filterLabel}");

                $sheet->mergeCells('D1:F1');
                $sheet->setCellValue('D1', number_format($this->totalRevenue, 0, ',', '.') . ' VNÄ');

                // Gá»™p tiĂªu Ä‘á» dĂ²ng 2
                $sheet->mergeCells('A2:C2');
                $sheet->mergeCells('D2:F2');

                // CÄƒn giá»¯a, in Ä‘áº­m
                $sheet->getStyle('A1:F2')->getFont()->setBold(true);
                $sheet->getStyle('A1:F2')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A1:F2')->getAlignment()->setVertical('center');
            },
        ];
    }
}
