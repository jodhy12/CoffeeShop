<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'qty', 'price', 'category_id', 'image_path'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_details')->withTimestamps()->withPivot('qty', 'price');
    }
}
