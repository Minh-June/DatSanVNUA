<?php

namespace App\Exports;

use App\Models\OrderDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class RevenueExport implements FromCollection, WithEvents, ShouldAutoSize
{
    protected $from, $to, $filterLabel, $totalRevenue;

    public function __construct($from, $to, $filterLabel, $totalRevenue)
    {
        $this->from = $from;
        $this->to = $to;
        $this->filterLabel = $filterLabel;
        $this->totalRevenue = $totalRevenue;
    }

    public function collection()
    {
        $data = [];

        $orderDetails = OrderDetail::whereBetween('date', [$this->from, $this->to])
            ->whereHas('order', fn($q) => $q->where('status', 1))
            ->with('yard')
            ->get();

        $grouped = $orderDetails->groupBy('yard.name');
        $i = 1;

        foreach ($grouped as $yardName => $group) {
            $data[] = [
                $i++,
                $yardName,
                $group->pluck('order_id')->unique()->count(),
                number_format($group->sum('price'), 0, ',', '.') . 'đ',
            ];
        }

        return new Collection($data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $now = now();

                // Căn giữa tiêu đề
                $sheet->mergeCells('B2:K2');
                $sheet->setCellValue('B2', 'BÁO CÁO DOANH THU SÂN THỂ THAO');

                $sheet->mergeCells('B3:K3');
                $sheet->setCellValue('B3', 'Trung tâm Giáo dục Thể chất và Thể thao – Học viện Nông nghiệp Việt Nam');

                $sheet->mergeCells('B5:K5');
                $sheet->setCellValue('B5', 'Đơn vị quản lý: Trung tâm Giáo dục Thể chất và Thể thao – Học viện Nông nghiệp Việt Nam');

                $sheet->mergeCells('D6:H6');
                $sheet->setCellValue('D6', 'Số điện thoại: 024.6261.7528');

                $sheet->mergeCells('D7:H7');
                $sheet->setCellValue('D7', 'Địa chỉ: Trâu Quỳ, Gia Lâm, Hà Nội');

                $sheet->mergeCells('C9:J9');
                $sheet->setCellValue('C9', 'Thống kê báo cáo doanh thu theo ' . $this->filterLabel);

                // Dòng tiêu đề bảng
                $sheet->setCellValue('B11', 'STT');
                $sheet->mergeCells('C11:F11');
                $sheet->setCellValue('C11', 'Tên sân');
                $sheet->mergeCells('G11:H11');
                $sheet->setCellValue('G11', 'Số đơn đặt');
                $sheet->mergeCells('I11:K11');
                $sheet->setCellValue('I11', 'Doanh thu từng sân');

                // Định dạng in đậm + căn giữa
                $sheet->getStyle('B2:K3')->getFont()->setBold(true)->setSize(13);
                $sheet->getStyle('B2:K3')->getAlignment()->setHorizontal('center');

                $sheet->getStyle('B5:K5')->getFont()->setSize(11);
                $sheet->getStyle('B5:K5')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('D6:H7')->getAlignment()->setHorizontal('center');

                $sheet->getStyle('C9:J9')->getFont()->setBold(true);
                $sheet->getStyle('C9:J9')->getAlignment()->setHorizontal('center');

                $sheet->getStyle('B11:K11')->getFont()->setBold(true);
                $sheet->getStyle('B11:K11')->getAlignment()->setHorizontal('center');

                // Dòng dữ liệu (bắt đầu từ B12)
                $dataCount = OrderDetail::whereBetween('date', [$this->from, $this->to])
                    ->whereHas('order', fn($q) => $q->where('status', 1))
                    ->with('yard')->get()
                    ->groupBy('yard.name')->count();
                $startRow = 12;
                $endRow = $startRow + $dataCount - 1;

                for ($row = $startRow; $row <= $endRow; $row++) {
                    $sheet->mergeCells("C$row:F$row");
                    $sheet->mergeCells("G$row:H$row");
                    $sheet->mergeCells("I$row:K$row");
                    $sheet->getStyle("B$row:K$row")->getAlignment()->setHorizontal('center');
                }

                // Tổng doanh thu
                $totalRow = $endRow + 1;
                $sheet->mergeCells("E$totalRow:H$totalRow");
                $sheet->setCellValue("E$totalRow", 'Tổng doanh thu:');
                $sheet->mergeCells("I$totalRow:K$totalRow");
                $sheet->setCellValue("I$totalRow", number_format($this->totalRevenue, 0, ',', '.') . 'đ');
                $sheet->getStyle("E$totalRow:K$totalRow")->getFont()->setBold(true);

                // Hà Nội ngày tháng năm
                $sheet->mergeCells("H" . ($totalRow + 3) . ":K" . ($totalRow + 3));
                $sheet->setCellValue("H" . ($totalRow + 3), 'Hà Nội, ngày ' . $now->day . ' tháng ' . $now->month . ' năm ' . $now->year);
                $sheet->getStyle("H" . ($totalRow + 3) . ":K" . ($totalRow + 3))->getAlignment()->setHorizontal('right');

                // Người lập đơn
                $sheet->mergeCells("H" . ($totalRow + 5) . ":K" . ($totalRow + 5));
                $sheet->setCellValue("H" . ($totalRow + 5), 'Người lập đơn');

                $sheet->mergeCells("H" . ($totalRow + 6) . ":K" . ($totalRow + 6));
                $sheet->setCellValue("H" . ($totalRow + 6), 'Admin');
                $sheet->getStyle("H" . ($totalRow + 5) . ":K" . ($totalRow + 6))->getAlignment()->setHorizontal('center');
            },
        ];
    }
}
