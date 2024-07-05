<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'is_available',
        'is_favorite',
        'user_id',
        'image',
    ];
    public function user(){
        return $this->belongsTo('App\Models\User');
        
    }
}
