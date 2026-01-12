<?php

namespace App\Exports;

use App\Models\OrderDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class RevenueExport implements FromCollection, WithEvents, ShouldAutoSize, WithDrawings
{
    protected $from, $to, $filterLabel, $totalRevenue;
    protected $rowMerges = [];

    public function __construct($from, $to, $filterLabel, $yardDetails, $monthRents, $productOrders)
    {
        $this->from = $from;
        $this->to = $to;
        $this->filterLabel = $filterLabel;
        $this->yardDetails = $yardDetails;
        $this->monthRents = $monthRents;
        $this->productOrders = $productOrders;
    }

    public function collection()
    {
        $data = [];

        // 11 hàng trống đầu để dành chỗ logo và tiêu đề
        for ($i = 0; $i < 11; $i++) {
            $data[] = [''];
        }

        $rowIndex = 12;
        $stt = 1;

        // =========================
        // Bảng 1: Thuê sân cố định
        // =========================
        $data[] = ['', '', '', 'Doanh thu thuê sân cố định', '', '', ''];
        $rowIndex++;

        $data[] = ['STT','Loại sân','Tên sân','Số đơn đặt','Doanh thu','',''];
        $rowIndex++;

        $groupFixed = $this->monthRents->groupBy(fn($item) => $item->yard->type->name ?? 'Loại sân không xác định')
            ->map(fn($group) => $group->groupBy(fn($item) => $item->yard->name));

        $sttFixed = 1;
        $totalFixed = 0;

        foreach ($groupFixed as $typeName => $yards) {
            $startRow = $rowIndex;
            foreach ($yards as $yardName => $items) {
                $countOrders = $items->pluck('month_rent_id')->unique()->count();
                $sumRevenue = $items->sum('price');
                $totalFixed += $sumRevenue;

                $data[] = [
                    $sttFixed++,
                    $typeName,
                    $yardName,
                    $countOrders,
                    $sumRevenue,
                    '',
                    ''
                ];
                $rowIndex++;
            }
            $endRow = $rowIndex - 1;
            if ($endRow > $startRow) {
                $this->rowMerges[] = [
                    'column' => 'B',
                    'start' => $startRow,
                    'end' => $endRow,
                ];
            }
        }

        // Tổng
        $data[] = ['', '', 'Tổng doanh thu:', $totalFixed, '', '', ''];
        $rowIndex += 2; // Thêm 1 dòng trống

        // =========================
        // Bảng 2: Thuê sân lẻ
        // =========================
        $data[] = ['', '', '', 'Doanh thu thuê sân lẻ', '', '', ''];
        $rowIndex++;

        $data[] = ['STT','Loại sân','Tên sân','Số đơn đặt','Doanh thu','',''];
        $rowIndex++;

        $groupYard = $this->yardDetails->groupBy(fn($item) => $item->yard->type->name ?? 'Loại sân không xác định')
            ->map(fn($group) => $group->groupBy(fn($item) => $item->yard->name));

        $sttYard = 1;
        $totalYard = 0;

        foreach ($groupYard as $typeName => $yards) {
            $startRow = $rowIndex;
            foreach ($yards as $yardName => $items) {
                $countOrders = $items->pluck('order_id')->unique()->count();
                $sumRevenue = $items->sum('price');
                $totalYard += $sumRevenue;

                $data[] = [
                    $sttYard++,
                    $typeName,
                    $yardName,
                    $countOrders,
                    $sumRevenue,
                    '',
                    ''
                ];
                $rowIndex++;
            }
            $endRow = $rowIndex - 1;
            if ($endRow > $startRow) {
                $this->rowMerges[] = [
                    'column' => 'B',
                    'start' => $startRow,
                    'end' => $endRow,
                ];
            }
        }

        $data[] = ['', '', 'Tổng doanh thu:', $totalYard, '', '', ''];
        $rowIndex += 2;

        // =========================
        // Bảng 3: Bán hàng
        // =========================
        $data[] = ['', '', '', 'Doanh thu bán hàng', '', '', ''];
        $rowIndex++;

        $data[] = ['STT','Loại sản phẩm','Sản phẩm','Số đơn đặt','Doanh thu','',''];
        $rowIndex++;

        $sttProduct = 1;
        $totalProduct = 0;

        foreach ($this->productOrders as $dataProd) {
            $sumRevenue = $dataProd['total_revenue'];
            $totalProduct += $sumRevenue;

            $data[] = [
                $sttProduct++,
                $dataProd['type_name'],
                $dataProd['product_name'],
                $dataProd['total_orders'],
                $sumRevenue,
                '',
                ''
            ];
            $rowIndex++;
        }

        $data[] = ['', '', 'Tổng doanh thu:', $totalProduct, '', '', ''];

        $this->totalRevenue = $totalFixed + $totalYard + $totalProduct;
        $this->dataCount = $rowIndex - 12;

        return new Collection($data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $now = now();

                // Gộp ô cho logo
                $sheet->mergeCells('A1:B5');

                $sheet->mergeCells('D2:G2');
                $sheet->setCellValue('D2', 'BÁO CÁO DOANH THU SÂN THỂ THAO');
                $sheet->getStyle('D2:G2')->getFont()->setBold(true);
                $sheet->getStyle('D2:G2')->getAlignment()->setHorizontal('center');

                $sheet->mergeCells('D3:G3');
                $sheet->setCellValue('D3', 'Trung tâm Giáo dục Thể chất và Thể thao – Học viện Nông nghiệp Việt Nam');
                $sheet->getStyle('D3:G3')->getAlignment()->setHorizontal('center');

                $sheet->mergeCells('C5:G5');
                $sheet->setCellValue('C5', 'Đơn vị quản lý: Trung tâm Giáo dục Thể chất và Thể thao – Học viện Nông nghiệp Việt Nam');
                $sheet->getStyle('C5:G5')->getAlignment()->setHorizontal('center');

                $sheet->mergeCells('D6:G6');
                $sheet->setCellValue('D6', 'Số điện thoại: 024.6261.7528');
                $sheet->getStyle('D6:G6')->getAlignment()->setHorizontal('center');

                $sheet->mergeCells('D7:G7');
                $sheet->setCellValue('D7', 'Địa chỉ: Trâu Quỳ, Gia Lâm, Hà Nội');
                $sheet->getStyle('D7:G7')->getAlignment()->setHorizontal('center');

                $sheet->mergeCells('C9:G9');
                $sheet->setCellValue('C9', 'Thống kê báo cáo doanh thu theo ' . $this->filterLabel);
                $sheet->getStyle('C9:G9')->getFont()->setBold(true);
                $sheet->getStyle('C9:G9')->getAlignment()->setHorizontal('center');

                $sheet->setCellValue('C11', 'STT');
                $sheet->setCellValue('D11', 'Loại sân');
                $sheet->setCellValue('E11', 'Tên sân');
                $sheet->setCellValue('F11', 'Số đơn đặt');
                $sheet->setCellValue('G11', 'Doanh thu từng sân');

                $sheet->getStyle('C11:G11')->getFont()->setBold(true);
                $sheet->getStyle('C11:G11')->getAlignment()->setHorizontal('center');

                $startRow = 12;
                $endRow = $startRow + $this->dataCount - 1;

                for ($row = $startRow; $row <= $endRow; $row++) {
                    $sheet->getStyle("C{$row}")->getAlignment()->setHorizontal('center');
                    $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal('left');
                    $sheet->getStyle("E{$row}")->getAlignment()->setHorizontal('left');
                    $sheet->getStyle("F{$row}")->getAlignment()->setHorizontal('center');
                    $sheet->getStyle("G{$row}")->getAlignment()->setHorizontal('center');
                    $sheet->getStyle("G{$row}")->getNumberFormat()->setFormatCode('#,##0" đ"');
                }

                foreach ($this->rowMerges as $merge) {
                    $sheet->mergeCells("D{$merge['start']}:D{$merge['end']}");
                    $sheet->getStyle("D{$merge['start']}")->getAlignment()->setVertical('center')->setHorizontal('left');
                }

                $totalRow = $endRow + 1;
                $sheet->mergeCells("C{$totalRow}:F{$totalRow}");
                $sheet->setCellValue("C{$totalRow}", 'Tổng doanh thu:');
                $sheet->setCellValue("G{$totalRow}", number_format($this->totalRevenue, 0, ',', '.') . ' đ');
                $sheet->getStyle("C{$totalRow}:G{$totalRow}")->getFont()->setBold(true);
                $sheet->getStyle("C{$totalRow}")->getAlignment()->setHorizontal('right');
                $sheet->getStyle("G{$totalRow}")->getAlignment()->setHorizontal('center');

                $sheet->mergeCells("F" . ($totalRow + 2) . ":G" . ($totalRow + 2));
                $sheet->setCellValue("F" . ($totalRow + 2), 'Hà Nội, ngày ' . $now->day . ' tháng ' . $now->month . ' năm ' . $now->year);
                $sheet->getStyle("F" . ($totalRow + 2) . ":G" . ($totalRow + 2))->getAlignment()->setHorizontal('center');

                $sheet->mergeCells("F" . ($totalRow + 4) . ":G" . ($totalRow + 4));
                $sheet->setCellValue("F" . ($totalRow + 4), 'Người lập báo cáo');
                $sheet->mergeCells("F" . ($totalRow + 5) . ":G" . ($totalRow + 5));
                $sheet->setCellValue("F" . ($totalRow + 5), 'Quản lý sân');
                $sheet->getStyle("F" . ($totalRow + 4) . ":G" . ($totalRow + 5))->getAlignment()->setHorizontal('center');
            },
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo Trung tâm');
        $drawing->setPath(public_path('image/logo.png'));
        $drawing->setHeight(80); // chiều cao ảnh
        $drawing->setCoordinates('A1');

        // Căn giữa trong ô A1:B5 (giả lập)
        $drawing->setOffsetX(40); // lệch ngang để căn giữa 2 cột
        $drawing->setOffsetY(10); // lệch dọc để nằm giữa 5 hàng

        return [$drawing];
    }
}
