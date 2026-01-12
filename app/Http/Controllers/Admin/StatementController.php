<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use App\Exports\RevenueExport;
use Carbon\Carbon;
use App\Models\OrderDetail;
use App\Models\MonthRent;
use App\Models\ProductOrder;
use App\Models\Yard;
use App\Models\Store;

class StatementController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $filterType = $request->input('filter_type', 'date');
        $keyword = $request->input('keyword');

        // Xác định khoảng thời gian lọc
        $date = $request->input('date', now()->format('Y-m-d'));
        $month = $request->input('month', now()->format('Y-m'));
        $year = $request->input('year', now()->year);

        if ($filterType == 'date') {
            $from = Carbon::parse($date)->startOfDay()->format('Y-m-d H:i:s');
            $to   = Carbon::parse($date)->endOfDay()->format('Y-m-d H:i:s');
        } elseif ($filterType == 'month') {
            $from = Carbon::createFromFormat('Y-m', $month)->startOfMonth()->startOfDay()->format('Y-m-d H:i:s');
            $to   = Carbon::createFromFormat('Y-m', $month)->endOfMonth()->endOfDay()->format('Y-m-d H:i:s');
        } else {
            $from = Carbon::createFromFormat('Y', $year)->startOfYear()->startOfDay()->format('Y-m-d H:i:s');
            $to   = Carbon::createFromFormat('Y', $year)->endOfYear()->endOfDay()->format('Y-m-d H:i:s');
        }

        // Xác định quyền xem
        $ownerId = null;
        if ($user->role == 2) $ownerId = $user->user_id;
        elseif ($user->role == 3) $ownerId = $user->manager_id;

        // 1️⃣ Doanh thu sân lẻ
        $yardDetails = OrderDetail::with('yard.type')
        ->whereHas('order', function ($q) use ($from, $to) {
            $q->whereIn('status', [1,3])
            ->whereBetween('date', [$from, $to]); // ✅ orders.date
        })
        ->when($ownerId, fn($q) => $q->whereIn(
            'yard_id',
            Yard::where('user_id', $ownerId)->pluck('yard_id')
        ))
        ->when($keyword, fn($q) =>
            $q->whereHas('yard', fn($q2) =>
                $q2->where('name', 'like', "%$keyword%")
            )
        )
        ->get();

        // 2️⃣ Doanh thu sân cố định
        $monthRents = MonthRent::with('yard.type')
            ->whereIn('status', [1,3])
            ->when($ownerId, fn($q) => $q->whereIn(
                'yard_id',
                Yard::where('user_id', $ownerId)->pluck('yard_id')
            ))
            ->whereBetween('date', [$from, $to]) // Dùng cột date: ngày tạo đơn
            ->when($keyword, fn($q) => $q->whereHas('yard', fn($q2) => $q2->where('name','like',"%$keyword%")))
            ->get();

        $fixedOrderCount = $monthRents->count();

        // 3️⃣ Doanh thu bán hàng
        $productOrders = ProductOrder::with(['orderDetails.product.type', 'store'])
            ->whereIn('status', [1, 3])
            ->whereBetween('date', [$from, $to])
            ->when($ownerId, fn($q) => $q->whereHas('store', fn($q2) => $q2->where('user_id', $ownerId)))
            ->get();

        // Tổng doanh thu
        $totalRevenue = $yardDetails->sum('price') + $monthRents->sum('price') + $productOrders->sum('total_price');

        // Gom nhóm sân lẻ
        $groupByTypeThenYard = $yardDetails->groupBy(fn($item) => $item->yard->type->name ?? 'Loại sân không xác định')
            ->map(fn($group) => $group->groupBy(fn($item) => $item->yard->name)
            ->map(fn($yardGroup) => [
                'total_revenue' => $yardGroup->sum('price'),
                'booking_count' => $yardGroup->pluck('order_id')->unique()->count(),
            ]));

        // Gom nhóm sân cố định
        $groupFixed = $monthRents->groupBy(fn($item) => $item->yard->type->name ?? 'Loại sân không xác định')
            ->map(fn($group) => $group->groupBy(fn($item) => $item->yard->name)
            ->map(fn($yardGroup) => [
                'total_revenue' => $yardGroup->sum('price'),
                'booking_count' => $yardGroup->pluck('month_rent_id')->unique()->count(), // số đơn
            ]));

        // Gom nhóm theo loại sản phẩm và tên sản phẩm
        $groupProduct = $productOrders->flatMap(fn($order) => $order->orderDetails)
            ->map(fn($detail) => [
                'type_name' => $detail->product->type->name ?? 'Loại sản phẩm không xác định',
                'product_name' => $detail->product->name ?? 'Sản phẩm không xác định',
                'total_orders' => 1, // mỗi chi tiết đơn là 1 đơn
                'total_revenue' => $detail->price * $detail->quantity,
            ])
            ->groupBy(fn($item) => $item['type_name'] . '|' . $item['product_name'])
            ->map(fn($items) => [
                'type_name' => $items->first()['type_name'],
                'product_name' => $items->first()['product_name'],
                'total_orders' => $items->sum('total_orders'),
                'total_revenue' => $items->sum('total_revenue'),
            ]);

        return view('admin.statements.index', compact(
            'totalRevenue',
            'groupByTypeThenYard',
            'groupFixed',
            'productOrders',
            'fixedOrderCount',
            'groupProduct'
        ));
    }

    public function exportExcel(Request $request)
    {
        $user = auth()->user();
        $filterType = $request->input('filter_type', 'date');

        // Xác định khoảng thời gian
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
        } else {
            $year = $request->input('year', now()->year);
            $from = \Carbon\Carbon::createFromFormat('Y', $year)->startOfYear()->format('Y-m-d');
            $to = \Carbon\Carbon::createFromFormat('Y', $year)->endOfYear()->format('Y-m-d');
            $filterLabel = 'Năm ' . $year;
        }

        // Chuẩn bị dữ liệu sân lẻ
        $yardDetails = \App\Models\OrderDetail::with('yard.type', 'order')
            ->whereHas('order', fn($q) => $q->whereIn('status', [1,3]))
            ->whereBetween('date', [$from, $to])
            ->get();

        // Chuẩn bị dữ liệu sân cố định
        $monthRents = \App\Models\MonthRent::with('yard.type')
            ->whereIn('status', [1,3])
            ->whereBetween('date', [$from, $to])
            ->get();

        // Chuẩn bị dữ liệu bán hàng
        $productOrders = \App\Models\ProductOrder::with(['orderDetails.product.type', 'store'])
            ->where('status',1)
            ->whereBetween('date', [$from, $to])
            ->get();

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\RevenueExport(
                $from, 
                $to, 
                $filterLabel, 
                $yardDetails, 
                $monthRents, 
                $productOrders
            ),
            'Doanh_Thu_Dat_San.xlsx'
        );
    }
}
