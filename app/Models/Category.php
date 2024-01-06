<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory;
    function getImageAttribute($value){
        return url('upload/categoryImage/'.$value);;
    }
        public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected $table = 'categories';
    
    protected $fillable = ['name','image','status'];
    
    
}
