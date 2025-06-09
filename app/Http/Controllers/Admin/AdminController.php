<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.statements.index');
    }
    
}
