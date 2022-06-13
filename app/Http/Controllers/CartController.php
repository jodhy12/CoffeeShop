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
        if (!$carts) {
            $carts = [];
        }
        return view('admin.cart.index', compact('carts'));
    }

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id]['qty'] >= $product->qty) {
                session()->flash('message', 'Not enough quantity, Quantity max is ' . $product->qty . '');
                session()->flash('alert-class', 'alert-danger');
                return redirect()->back();
            }
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

        session()->flash('message', '' . $product->name . ' added to cart');
        session()->flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function updateCart(Request $request)
    {
        $product = Product::findOrFail($request->id);
        if ($request->id && $request->qty) {
            if ($request->qty > $product->id) {
                session()->flash('message', 'Not enough quantity, Quantity max is ' . $product->qty . '');
                session()->flash('alert-class', 'alert-danger');
            } else {
                $cart = session()->get('cart');
                $cart[$request->id]['qty'] = $request->qty;

                session()->put('cart', $cart);

                session()->flash('message', 'Cart Updated');
                session()->flash('alert-class', 'alert-success');
            }
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

            session()->flash('message', 'Product removed');
            session()->flash('alert-class', 'alert-success');
        }
    }
}
