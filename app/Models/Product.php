<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    function getProductImageAttribute($value){
        return url('upload/productImage/'.$value);;
    }
     
    protected $table = 'products';
    
    protected $fillable = ['p_name','category_id','price','product_image','description','status'];
    
}
