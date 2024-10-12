<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImageYard;
use App\Models\Order;
use App\Models\Timeslot;
use App\Models\User;
use App\Models\Yard;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.admin');
    }
    
}
