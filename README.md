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
