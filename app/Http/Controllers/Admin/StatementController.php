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
        $keyword = $request->input('keyword');  // Láº¥y tá»« khĂ³a tĂ¬m kiáº¿m tá»« request

        // XĂ¡c Ä‘á»‹nh khoáº£ng thá»i gian lá»c
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
            $q->where('status', 1); // Chá»‰ láº¥y Ä‘Æ¡n Ä‘Ă£ xĂ¡c nháº­n
        })
        ->whereBetween('date', [$from, $to])
        ->with(['yard.type']);

        // Náº¿u chá»n loáº¡i sĂ¢n
        if ($typeId) {
            $query->whereHas('yard', function ($q) use ($typeId) {
                $q->where('type_id', $typeId);
            });
        }

        // Náº¿u chá»n tĂªn sĂ¢n theo yard_id
        if ($yardId) {
            $query->where('yard_id', $yardId);
        }

        // Náº¿u nháº­p keyword tĂ¬m theo tĂªn sĂ¢n (dĂ¹ng whereHas Ä‘á»ƒ lá»c theo quan há»‡ yard)
        if ($keyword) {
            $query->whereHas('yard', function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%');
            });
        }

        $orderDetails = $query->get();
        $totalRevenue = $orderDetails->sum('price');

        // Gom doanh thu theo tĂªn sĂ¢n
        $byYard = $orderDetails->groupBy('yard.name')->map(function ($group) {
            return $group->sum('price');
        });

        // Dá»¯ liá»‡u cho dropdown
        $allTypes = Type::orderBy('name')->get();
        $allYards = Yard::orderBy('name')->get();

        return view('admin.statements.index', compact(
            'totalRevenue', 'byYard', 'allTypes', 'allYards'
        ));
    }

    public function exportExcel(Request $request)
    {
        $filterType = $request->input('filter_type', 'date');

        if ($filterType == 'date') {
            $date = $request->input('date', now()->format('Y-m-d'));
            $from = $date;
            $to = $date;
            $filterLabel = 'ngĂ y ' . \Carbon\Carbon::parse($date)->format('d/m/Y');
        } elseif ($filterType == 'month') {
            $month = $request->input('month', now()->format('Y-m'));
            $from = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth()->format('Y-m-d');
            $to = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth()->format('Y-m-d');
            $filterLabel = 'thĂ¡ng ' . \Carbon\Carbon::parse($from)->format('m/Y');
        } elseif ($filterType == 'year') {
            $year = $request->input('year', now()->year);
            $from = \Carbon\Carbon::createFromFormat('Y', $year)->startOfYear()->format('Y-m-d');
            $to = \Carbon\Carbon::createFromFormat('Y', $year)->endOfYear()->format('Y-m-d');
            $filterLabel = 'nÄƒm ' . $year;
        } else {
            $from = now()->format('Y-m-d');
            $to = now()->format('Y-m-d');
            $filterLabel = 'ngĂ y ' . now()->format('d/m/Y');
        }

        // TĂ­nh tá»•ng doanh thu
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
