<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//import model
use App\Models\Produk;

//use view
use Illuminate\View\View;

class ProdukController extends Controller
{
    public function index(): View {
        //mengambil seluruh produk
        $produks = Produk::latest()->paginate(10);

        //render view dengan produks
        return view('produks.index', compact('produks'));
    }

}
