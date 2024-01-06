<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    function getImageAttribute($value){
        return url('upload/bannerImage/'.$value);;
    }
    
    
    protected $table = 'banners';
    
    protected $fillable = ['image','status'];
    
}
