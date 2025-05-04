<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//import model
use App\Models\Produk;

//use view
use Illuminate\View\View;

//import untuk C.U.D
use Illuminate\Http\RedirectResponse;

class ProdukController extends Controller
{
    public function index(): View {
        //mengambil seluruh produk
        $produks = Produk::latest()->paginate(10);

        //render view dengan produks
        return view('produks.index', compact('produks'));
    }

    //create

    public function create(): View
    {
        return view('produks.create');
    }

    public function store(Request $request): RedirectResponse{
        //form validasi
        $request->validate([
            'foto'      => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'nama'      => 'required|min:5',
            'deskripsi' => 'required|min:10',
            'harga'     => 'required|numeric',
            'stok'      => 'required|numeric',

        ]);

        //upload foto
        $foto=$request->file('foto');
        $foto->storeAs('produk',$foto->hashName());

        //buat produk
        Produk::create([
            'foto'      =>$foto->hashName(),
            'nama'      =>$request->nama,
            'deskripsi' =>$request->deskripsi,
            'harga'     =>$request->harga,
            'stok'      =>$request->stok,
        ]);

        //membuat redirect halaman ke index, setelah berhasil create
        return redirect()->route('produk.index')->with(['success'=>'data tersimpan']);
    }

    public function show(string $id): View{
        //dapatkan produk berdasarkan id
        $produk = Produk::findOrFail($id);

        //render view
        return view('produks.show', compact('produk'));
    }

}
