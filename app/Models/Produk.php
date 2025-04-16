<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//import hasfactory
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    //panggil has factory
    use HasFactory;

    protected $fillable =[
        'foto','nama','deskripsi','harga','stok'
    ];
}
