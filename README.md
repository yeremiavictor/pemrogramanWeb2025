# Persiapan

1. Instalasi: XAMPP dan composer
2. Wajib setting: php.ini -> aktifkan mysqli, zip (menyesuaikan kebutuhan proyek)
3. Text editor: Visual studio code
4. Extension: Untuk helper PHP
5. Memiliki skill setidaknya html, css, dan php

## Instalasi Laravel

1. Buka terminal:

```bash
    composer create-project --prefer-dist laravel/laravel nama_proyek
```

2. buka folder proyek dengan VSCODE
3. arahkan terminal / cmd ke folder root proyek kemudian tes proyek dengan menggunakan

```bash
    php artisan serve
```

## Konfigurasi Laravel

1. Buka .env ubah pada bagian

```env
    FILESYSTEM_DISK=local
```

menjadi:

```env
    FILESYSTEM_DISK=public
```

2. Jalankan perintah berikut untuk membuat public link ke storage

```bash
    php artisan storage:link
```

## Konfigurasi Database

Buka .env ubah pada bagian

```env
    DB_CONNECTION=sqlite
    # DB_HOST=127.0.0.1
    # DB_PORT=3306
    # DB_DATABASE=laravel
    # DB_USERNAME=root
    # DB_PASSWORD=
```

menjadi

```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_anda
    DB_USERNAME=root
    DB_PASSWORD=
```

catatan: sesuaikan username dan password sesuai dengan setting Database Anda.

## Buat Model dan Migrasi

1. Buka Terminal:

```bash
php artisan make:model Produk -m
```

2. Buka folder database/migrations/
3. Buka file yang baru di create
4. pada bagian up silahkan tambahkan kebutuhan data field data anda
   contoh:

```php
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            //disini
            $table->string('foto');
            $table->string('nama');
            $table->text('deskripsi');
            $table->bigInteger('harga');
            $table->integer('stok')->default(0);
            //akhir
            $table->timestamps();
        });
    }
```

Agar dapat dimanipulasi maka diperlukan izin fillable pada model

6. masuk folder app/Models/Produk.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//import hasfactory
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    //panggil has factory
    use HasFactory;

    //berikan akses
    protected $fillable =[
        'foto','nama','deskripsi','harga','stok'
    ];
}

```

7. Lakukan migrasi model dengan:

```bash
php artisan migrate
```

8. cek apakah table sudah di generate di database, melalui phpmyadmin

## Membuat Read

1. Membuat controller untuk mengelola produk

```bash
    php artisan make:controller ProdukController
```

2. Buka app/Http/Controllers/ProdukController.php
   update produk sebagai berikut

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

//import model
use App\Models\Produk;

//use view
use Illuminate\View\View;

class ProdukController extends Controller
{
    //untuk view
    public function index(): View {
        //mengambil seluruh produk
        $produks = Produk::latest()->paginate(10);

        //render view dengan produks
        return view('produks.index', compact('produks'));
    }

}
```

3. Agar produk dapat diakses, maka diperlukan setting di routes (buka routes/web.php)

```php
<?php

use Illuminate\Support\Facades\Route;

//import ProdukController
use App\Http\Controllers\ProdukController;

//memberikan akses web ke ProdukController
Route::resource('/produk',ProdukController::class);

Route::get('/', function () {
    return view('welcome');
});

```

kemudian anda bisa cek dengan terminal terkait routes yang di buat:

```bash
php artisan route:list
```

4. tambahkan folder "produks" dalam resource/views/
5. buat file baru dengan nama "index.blade.php"
6. Masukan rumus html dengan contoh sebagai berikut:

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: lightgray">

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <h3 class="text-center my-4">Data Produk</h3>
                </div>
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <a href="{{ route('produk.create') }}" class="btn btn-md btn-success mb-3">Tambah</a>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Foto</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Stok</th>
                                    <th scope="col" style="width: 20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($produks as $produk)
                                    <tr>
                                        <td class="text-center">
                                            <img src="{{ asset('/storage/produk/'.$produk->foto) }}" class="rounded" style="width: 150px">
                                        </td>
                                        <td>{{ $produk->nama }}</td>
                                        <td>{{ "Rp " . number_format($produk->harga,2,',','.') }}</td>
                                        <td>{{ $produk->stok }}</td>
                                        <td class="text-center">
                                            <form onsubmit="return confirm('Anda yakin ?');" action="{{ route('produk.destroy', $produk->id) }}" method="POST">
                                                <a href="{{ route('produk.show', $produk->id) }}" class="btn btn-sm btn-dark">Lihat</a>
                                                <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-sm btn-primary">EDIT</a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <div class="alert alert-danger">
                                        Data Produk kosong
                                    </div>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $produks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        //message with sweetalert
        @if(session('success'))
            Swal.fire({
                icon: "success",
                title: "BERHASIL",
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @elseif(session('error'))
            Swal.fire({
                icon: "error",
                title: "GAGAL!",
                text: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @endif

    </script>

</body>
</html>
```

## Insert Data (Create)

1. Import redirect response, dan tambahkan function untuk view dan store data
2. Pada bagian import (atas) tambahkan redirect response (untuk auto lompat link setelah proses input selesai) dengan syntax berikut:

```php

// --- Tambahkan ini import untuk C.U.D
use Illuminate\Http\RedirectResponse;
// ----
```

3. Kemudian setelah bagian akhir kurawal dari

```php
        //render view dengan produks
        return view('produks.index', compact('produks'));
    }

```

4. Tambahkan function untuk create dan function untuk store data

```php

    //--- tambahkan ini setelah tanda kurawal class Produk Controller
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

    //--- sampai disini

```

5. Langkah selanjutnya adalah membuat view create
6. Buat file baru dengan nama create.blade.php pada folder resources\view\produks
7. Buat form html dengan syntax sebagai berikut:

```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>Tambah Produk</title>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
        />
    </head>
    <body style="background: lightgray">
        <div class="container mt-5 mb-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm rounded">
                        <div class="card-body">
                            <form
                                action="{{ route('produk.store') }}"
                                method="POST"
                                enctype="multipart/form-data"
                            >
                                @csrf

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Foto</label>
                                    <input
                                        type="file"
                                        class="form-control @error('foto') is-invalid @enderror"
                                        name="foto"
                                    />

                                    <!-- error message untuk foto -->
                                    @error('foto')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Nama</label>
                                    <input
                                        type="text"
                                        class="form-control @error('nama') is-invalid @enderror"
                                        name="nama"
                                        value="{{ old('nama') }}"
                                        placeholder="Masukkan Nama Product"
                                    />

                                    <!-- error message untuk nama -->
                                    @error('nama')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold"
                                        >Deskripsi</label
                                    >
                                    <textarea
                                        class="form-control @error('deskripsi') is-invalid @enderror"
                                        name="deskripsi"
                                        rows="5"
                                        placeholder="Masukkan deskripsi Product"
                                    >
                                    {{ old('deskripsi') }}
                                    </textarea>

                                    <!-- error message untuk deskripsi -->
                                    @error('deskripsi')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold"
                                                >Harga</label
                                            >
                                            <input
                                                type="number"
                                                class="form-control @error('harga') is-invalid @enderror"
                                                name="harga"
                                                value="{{ old('harga') }}"
                                                placeholder="Masukkan Harga Product"
                                            />

                                            <!-- error message untuk harga -->
                                            @error('harga')
                                            <div
                                                class="alert alert-danger mt-2"
                                            >
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold"
                                                >Stok</label
                                            >
                                            <input
                                                type="number"
                                                class="form-control @error('stok') is-invalid @enderror"
                                                name="stok"
                                                value="{{ old('stok') }}"
                                                placeholder="Masukkan stok Product"
                                            />

                                            <!-- error message untuk stok -->
                                            @error('stok')
                                            <div
                                                class="alert alert-danger mt-2"
                                            >
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <button
                                    type="submit"
                                    class="btn btn-md btn-primary me-3"
                                >
                                    SAVE
                                </button>
                                <button
                                    type="reset"
                                    class="btn btn-md btn-warning"
                                >
                                    RESET
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
        <script>
            CKEDITOR.replace("deskripsi");
        </script>
    </body>
</html>
```

## Membuat Detail View

1. Langkah selanjutnya adalah membuat fitur edit
2. Masih dalam controller yang sama tambahkan function berikut setelah akhir kurawal dari

```php
     return redirect()->route('produk.index')->with(['success'=>'data tersimpan']);
    }
```

3. Tambahkan function berikut

```php
    public function show(string $id): View{
        //dapatkan produk berdasarkan id
        $produk = Produk::findOrFail($id);

        //render view
        return view('produks.show', compact('produk'));
    }
```

4. Kemudian pada folder resources > views > produk buat file html baru dengan nama show.blade.php dengan syntax html berikut

```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>Menampilkan Produk</title>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
        />
    </head>
    <body style="background: lightgray">
        <div class="container mt-5 mb-5">
            <div class="row">
                <div class="col md-4">
                    <div class="card border-0 shadow-sm rounded">
                        <div class="card-body text-center">
                            <img
                            src="{{asset("/storage/produk/".$produk->foto)}}"
                            alt="Foto Produk" class="rounded"
                            style="width:400px">
                        </div>
                    </div>
                </div>
                <div class="col md-8">
                    <div class="card border-0 shadow-sm rounded">
                        <div class="card-body">
                            <h3>{{$produk->nama}}</h3>
                            <hr />
                            <p>
                                {{'RP'.number_format($produk->harga,2,',','.')}}
                            </p>
                            <code>
                                <p>{!! $produk->deskripsi !!}</p>
                            </code>
                            <hr />
                            <p>{{$produk->stok}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
```

## Create dan Update Data

1. Langkah selanjutnya adalah membuat detail view (view khusus dengan id tertentu)
2. Masih dalam controller yang sama import facades storage (untuk manajemen storage pada bagian atas)

```php
//import untuk update
use Illuminate\Support\Facades\Storage;
```

3. Cari akhir kurawal dari show produk detail

```php
        //render view
        return view('produks.show', compact('produk'));
    }
```

4. Tambahkan function edit sebagai berikut setelah kurawal:

```php
    public function edit(string $id):View{
        //ambil produk berdasarkan id
        $produk = Produk::findOrFail($id);

        //render view dari produk
        return view('produks.edit', compact('produk'));
    }

    public function update(Request $request, $id):RedirectResponse{
        //melakukan validasi form
        $request->validate([
            'foto'      => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'nama'      => 'required|min:5',
            'deskripsi' => 'required|min:10',
            'harga'     => 'required|numeric',
            'stok'      => 'required|numeric',
        ]);

        //mencari produk berdasarkan ID
        $produk = Produk::findOrFail($id);

        //cek apakah gambar sudah ada?
        if($request->hasFile('foto')){
            // Hapus gambarnya
            Storage::delete('produk/'.$produk->foto);

            //upload yang baru
            $foto = $request->file('foto');
            $foto->storeAs('produk', $foto->hashName());

            //update produk dengan gambar
            $produk->update([
                'foto'      =>$foto->hashName(),
                'nama'      =>$request->nama,
                'deskripsi' =>$request->deskripsi,
                'harga'     =>$request->harga,
                'stok'      =>$request->stok,
            ]);
        }

        //kalau ga ada?
        else{
            //ya update tanpa gambar produk
            $produk->update([
                'nama'      =>$request->nama,
                'deskripsi' =>$request->deskripsi,
                'harga'     =>$request->harga,
                'stok'      =>$request->stok,
            ]);
        }

        //Redirect ke index, setelah isi form
        return redirect()->route('produk.index')->with(['success'=>'data diperbarui']);

    }
```

5. Kemudian pada folder resources > views > produk buat file html baru dengan nama edit.blade.php dengan syntax html berikut

```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>Edit</title>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
        />
    </head>
    <body style="background: lightgray">
        <div class="container mt-5 mb-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm rounded">
                        <div class="card-body">
                            <form
                                action="{{ route('produk.update', $produk->id) }}"
                                method="POST"
                                enctype="multipart/form-data"
                            >
                                @csrf @method('PUT')

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Foto</label>
                                    <input
                                        type="file"
                                        class="form-control @error('foto') is-invalid @enderror"
                                        name="foto"
                                    />

                                    <!-- error message untuk foto -->
                                    @error('foto')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold"
                                        >TITLE</label
                                    >
                                    <input
                                        type="text"
                                        class="form-control @error('title') is-invalid @enderror"
                                        name="nama"
                                        value="{{ old('nama', $produk->nama) }}"
                                        placeholder="Masukkan Judul Produk"
                                    />

                                    <!-- error message untuk nama -->
                                    @error('nama')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold"
                                        >Keterangan</label
                                    >
                                    <textarea
                                        class="form-control @error('deskripsi') is-invalid @enderror"
                                        name="deskripsi"
                                        rows="5"
                                        placeholder="Masukkan Description Produk"
                                    >
                                    {{ old('deskripsi', $produk->deskripsi) }}
                                    </textarea>

                                    <!-- error message untuk deskripsi -->
                                    @error('deskripsi')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold"
                                                >Harga</label
                                            >
                                            <input
                                                type="number"
                                                class="form-control @error('harga') is-invalid @enderror"
                                                name="harga"
                                                value="{{ old('harga', $produk->harga) }}"
                                                placeholder="Masukkan Harga Produk"
                                            />

                                            <!-- error message untuk harga -->
                                            @error('harga')
                                            <div
                                                class="alert alert-danger mt-2"
                                            >
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold"
                                                >Stok</label
                                            >
                                            <input
                                                type="number"
                                                class="form-control @error('stok') is-invalid @enderror"
                                                name="stok"
                                                value="{{ old('stok', $produk->stok) }}"
                                                placeholder="Masukkan Stock Produk"
                                            />

                                            <!-- error message untuk stok -->
                                            @error('stok')
                                            <div
                                                class="alert alert-danger mt-2"
                                            >
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <button
                                    type="submit"
                                    class="btn btn-md btn-primary me-3"
                                >
                                    UPDATE
                                </button>
                                <button
                                    type="reset"
                                    class="btn btn-md btn-warning"
                                >
                                    RESET
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.ckeditor.com/4.25.1/standard/ckeditor.js"></script>
        <script>
            CKEDITOR.replace("deskripsi");
        </script>
    </body>
</html>
```

### Hapus Data

1. Langkah selanjutnya adalah fungsi delete
2. Cari akhir kurawal dari show produk detail

```php
 //Redirect ke index, setelah isi form
        return redirect()->route('produk.index')->with(['success'=>'data diperbarui']);

    }

```

3. Tambahkan function destroy / delete sebagai berikut setelah kurawal:

```php
public function destroy($id): RedirectResponse{
        //cari produk berdasarkan id
        $produk = Produk::findOrFail($id);

        //hapus foto
        Storage::delete('produk/'.$produk->foto);


        //hapus produk
        $produk->delete();
        // Kembalikan ke index
        return redirect()->route('produk.index')->with(['success'=>'data sudah dihapus']);
    }
```
