<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use App\Exports\RevenueExport;
use App\Models\Type;
use App\Models\Yard;

class StatementController extends Controller
{
    public function index(Request $request)
    {
        $filterType = $request->input('filter_type', 'date');
        $typeId = $request->input('type_id');
        $yardId = $request->input('yard_id');
        $keyword = $request->input('keyword');  // Lấy từ khóa tìm kiếm từ request

        // Xác định khoảng thời gian lọc
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $year = $request->input('year', Carbon::now()->year);

        if ($filterType == 'date') {
            $from = $date;
            $to = $date;
        } elseif ($filterType == 'month') {
            $from = Carbon::createFromFormat('Y-m', $month)->startOfMonth()->format('Y-m-d');
            $to = Carbon::createFromFormat('Y-m', $month)->endOfMonth()->format('Y-m-d');
        } elseif ($filterType == 'year') {
            $from = Carbon::createFromFormat('Y', $year)->startOfYear()->format('Y-m-d');
            $to = Carbon::createFromFormat('Y', $year)->endOfYear()->format('Y-m-d');
        } else {
            $from = $to = Carbon::now()->format('Y-m-d');
        }

        $query = OrderDetail::whereHas('order', function ($q) {
            $q->where('status', 1); // Chỉ lấy đơn đã xác nhận
        })
        ->whereBetween('date', [$from, $to])
        ->with(['yard.type']);

        // Nếu chọn loại sân
        if ($typeId) {
            $query->whereHas('yard', function ($q) use ($typeId) {
                $q->where('type_id', $typeId);
            });
        }

        // Nếu chọn tên sân theo yard_id
        if ($yardId) {
            $query->where('yard_id', $yardId);
        }

        // Nếu nhập keyword tìm theo tên sân
        if ($keyword) {
            $query->whereHas('yard', function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%');
            });
        }

        $orderDetails = $query->get();

        $totalRevenue = $orderDetails->sum('price');

        // Nhóm theo loại sân, rồi theo tên sân
        $groupByTypeThenYard = $orderDetails->groupBy(function ($item) {
            return optional(optional($item->yard)->type)->name ?? 'Loại sân không tồn tại';
        })->map(function ($group) {
            return $group->groupBy(function ($item) {
                return optional($item->yard)->name ?? 'Sân không tồn tại';
            })->map(function ($yardGroup) {
                return [
                    'total_revenue' => $yardGroup->sum('price'),
                    'booking_count' => $yardGroup->pluck('order_id')->unique()->count(),
                ];
            });
        });

        // Dữ liệu cho dropdown
        $allTypes = Type::orderBy('name')->get();
        $allYards = Yard::orderBy('name')->get();

        return view('admin.statements.index', compact(
            'totalRevenue', 'groupByTypeThenYard', 'allTypes', 'allYards'
        ));
    }

    public function exportExcel(Request $request)
    {
        $filterType = $request->input('filter_type', 'date');

        if ($filterType == 'date') {
            $date = $request->input('date', now()->format('Y-m-d'));
            $from = $date;
            $to = $date;
            $filterLabel = 'Ngày ' . \Carbon\Carbon::parse($date)->format('d/m/Y');
        } elseif ($filterType == 'month') {
            $month = $request->input('month', now()->format('Y-m'));
            $from = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth()->format('Y-m-d');
            $to = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth()->format('Y-m-d');
            $filterLabel = 'Tháng ' . \Carbon\Carbon::parse($from)->format('m/Y');
        } elseif ($filterType == 'year') {
            $year = $request->input('year', now()->year);
            $from = \Carbon\Carbon::createFromFormat('Y', $year)->startOfYear()->format('Y-m-d');
            $to = \Carbon\Carbon::createFromFormat('Y', $year)->endOfYear()->format('Y-m-d');
            $filterLabel = 'Năm ' . $year;
        } else {
            $from = now()->format('Y-m-d');
            $to = now()->format('Y-m-d');
            $filterLabel = 'Ngày ' . now()->format('d/m/Y');
        }

        // Tổng doanh thu
        $totalRevenue = \App\Models\OrderDetail::whereBetween('date', [$from, $to])
            ->whereHas('order', function ($q) {
                $q->where('status', 1);
            })
            ->sum('price');

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\RevenueExport($from, $to, $filterLabel, $totalRevenue),
            'Doanh_Thu_Dat_San.xlsx'
        );
    }
}
