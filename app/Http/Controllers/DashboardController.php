<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Banner;

class DashboardController extends Controller
{
    public function index()
    {
        $productCount = Product::count();
        $categoryCount = Category::count();
        $userCount = User::where('user_type', '2')->count();
        $bannerCount = Banner::count();
        return view('dashboard.index', compact('productCount', 'categoryCount','userCount', 'bannerCount'));
    }

}
