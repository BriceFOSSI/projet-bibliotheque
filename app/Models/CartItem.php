<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CartItem extends Model
{
    protected $fillable = ['product_name', 'price', 'quantity'];
}
