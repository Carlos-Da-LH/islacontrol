<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        $categories = Category::all();
        $products = Product::with('category')->get();
        
        return view('dashboard', compact('customers', 'categories', 'products'));
    }
}