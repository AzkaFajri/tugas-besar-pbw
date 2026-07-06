<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Customer;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'pegawai' => Pegawai::count(),
            'customer' => Customer::count(),
            'products' => Product::count(),
            'purchase_orders' => PurchaseOrder::count(),
        ];

        $latest_po = PurchaseOrder::with('customer','pegawai')->orderBy('tgl_po', 'desc')->limit(5)->get();

        return view('dashboard', compact('counts', 'latest_po'));
    }
}
