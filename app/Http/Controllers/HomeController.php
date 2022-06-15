<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use function PHPUnit\Framework\isNull;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $exists = [];
        $products = Product::with('category')
            ->orderByRaw('qty > 0 desc')
            ->orderBy('id')
            ->paginate(12);

        foreach ($products as $key => $product) {
            $exists[$key] = File::exists($product->image_path);
        }

        return view('admin.dashboard', compact('products', 'exists'));
    }
}
