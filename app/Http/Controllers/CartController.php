<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $carts = session()->get('cart');
        return view('admin.cart.index', compact('carts'));
    }

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['qty']++;
        } else {
            $cart[$id] = [
                'name' => $product->name,
                'qty' => 1,
                'price' => $product->price,
                'image_path' => $product->image_path,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('message', 'Product added to cart');
    }

    public function updateCart(Request $request)
    {
        if ($request->id && $request->qty) {
            $cart = session()->get('cart');
            $cart[$request->id]['qty'] = $request->qty;
            session()->put('cart', $cart);
            session()->flash('success', 'Cart Updated');
        }
    }

    public function removeCart(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }

            session()->flash('success', 'Product removed');
        }
    }
}
