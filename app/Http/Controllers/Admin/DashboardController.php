<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flower;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalFlowers = Flower::count();
        
        // Menghitung data aman menggunakan table query (mencegah error jika model belum ada)
        $totalOrders = DB::getSchemaBuilder()->hasTable('orders') 
            ? DB::table('orders')->where('created_at', '>=', now()->subDays(7))->count() 
            : 5;

        $totalBranches = DB::getSchemaBuilder()->hasTable('branches') 
            ? DB::table('branches')->count() 
            : 2;

        return view('admin.dashboard', compact('totalFlowers', 'totalOrders', 'totalBranches'));
    }
}