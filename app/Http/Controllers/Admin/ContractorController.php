<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductOrder;
use App\Models\MonthRent;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class ContractorController extends Controller
{
    public function index(Request $request)
    {
        // 1. Lấy danh sách chủ thầu + admin
        $contractors = User::whereIn('role', [0,2])->get();

        // 2. Lấy contractor theo query param hoặc mặc định first
        $userId = $request->query('user_id');
        $contractor = $userId ? User::find($userId) : $contractors->first();

        if(!$contractor) {
            return redirect()->back()->with('error', 'Không tìm thấy chủ thầu');
        }

        // 3. Lấy danh sách nhân viên của chủ thầu
        $employees = User::where('role', 3)
                        ->where('manager_id', $contractor->user_id)
                        ->get();

        // 4. Xác định kiểu lọc thống kê: date / month / year
        $filterType = $request->query('filter_type', 'date');
        $today = Carbon::now()->format('Y-m-d');
        $monthNow = Carbon::now()->format('Y-m');
        $yearNow = Carbon::now()->format('Y');

        switch ($filterType) {
            case 'month':
                $start = Carbon::parse($request->query('month', $monthNow))->startOfMonth();
                $end   = Carbon::parse($request->query('month', $monthNow))->endOfMonth();
                break;
            case 'year':
                $year  = $request->query('year', $yearNow);
                $start = Carbon::parse("$year-01-01")->startOfYear();
                $end   = Carbon::parse("$year-12-31")->endOfYear();
                break;
            default: // date
                $date  = $request->query('date', $today);
                break;
        }

        // 5. Đơn thuê cố định (MonthRent)
        $fixedOrderQuery = MonthRent::whereHas('yard', function($q) use ($contractor){
            $q->where('user_id', $contractor->user_id); // sân thuộc chủ thầu
        })->whereIn('status', [1,3]); // chỉ tính đơn xác nhận & đặt cọc

        if($filterType === 'date'){
            $fixedOrderQuery->whereDate('date', $date); // dùng ngày đặt
        } else {
            $fixedOrderQuery->where(function($q) use ($start, $end){
                $q->whereBetween('from_date', [$start, $end])
                  ->orWhereBetween('to_date', [$start, $end])
                  ->orWhere(function($q2) use ($start, $end){
                      $q2->where('from_date', '<=', $start)
                         ->where('to_date', '>=', $end);
                  });
            });
        }
        $fixedOrderCount = $fixedOrderQuery->count();

        // 6. Đơn thuê lẻ (Order)
        $orderQuery = Order::whereHas('orderDetails.yard', function($q) use ($contractor){
            $q->where('user_id', $contractor->user_id);
        })->whereIn('status', [1,3]);

        if($filterType === 'date'){
            $orderQuery->whereDate('date', $date);
        } else {
            $orderQuery->whereBetween('date', [$start, $end]);
        }
        $orderCount = $orderQuery->count();

        // 7. Đơn mua hàng (ProductOrder)
        $purchaseQuery = ProductOrder::whereHas('store', function($q) use ($contractor){
            $q->where('user_id', $contractor->user_id);
        })->whereIn('status', [1,3]);

        if($filterType === 'date'){
            $purchaseQuery->whereDate('date', $date);
        } else {
            $purchaseQuery->whereBetween('date', [$start, $end]);
        }
        $purchaseCount = $purchaseQuery->count();

        // 8. Trả về view với tất cả dữ liệu
        return view('admin.contractor.index', [
            'contractors'      => $contractors,
            'contractor'       => $contractor,
            'employees'        => $employees,
            'fixedOrderCount'  => $fixedOrderCount,
            'orderCount'       => $orderCount,
            'purchaseCount'    => $purchaseCount,
            'filterType'       => $filterType,
            'selectedDate'     => $filterType === 'date' ? $date : null,
            'selectedMonth'    => $filterType === 'month' ? $request->query('month', $monthNow) : null,
            'selectedYear'     => $filterType === 'year' ? $request->query('year', $yearNow) : null,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'yard_id' => 'required|exists:yards,yard_id',
            'user_id' => 'required|exists:users,user_id',
        ]);

        $yard = Yard::find($request->yard_id);
        $yard->user_id = $request->user_id;
        $yard->save();

        return redirect()->route('quan-ly-san')->with('success', 'Cập nhật thành công!');
    }
}
