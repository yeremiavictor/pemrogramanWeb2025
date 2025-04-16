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
