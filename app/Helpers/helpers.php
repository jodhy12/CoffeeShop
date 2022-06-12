<?php

use App\Models\Product;

function dateGMT($value)
{
    $date = new DateTime($value);
    $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
    return $date->format('Y-m-d H:i:s');
}

function removeQtyProduct($id, $qty)
{
    $product = Product::findOrFail($id);
    $product->qty = $product->qty - $qty;
    $product->save();
}

function countCarts()
{
    return count(session()->get('cart'));
}
