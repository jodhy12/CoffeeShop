<?php

use App\Models\Product;

function dateGMT($value)
{
    $date = new DateTime($value);
    $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
    return $date->format('Y-m-d H:i:s');
}

function dateGMT7($value)
{
    $date = new DateTime($value);
    $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
    return $date->format('d-m-Y');
}

function removeQtyProduct($id, $qty)
{
    $product = Product::findOrFail($id);
    $product->qty = $product->qty - $qty;
    $product->save();
}

function countCarts()
{
    if (session()->get('cart'))
        return count(session()->get('cart'));
    else
        return 0;
}

function displayMessage()
{
    if (session()->has('message')) {
        echo '<div class="alert ' . session()->get('alert-class', 'alert-info') . ' ">' . session()->get('message') . '</div>';
    }
}
