# Persiapan

1. Instalasi: XAMPP dan composer
2. Wajib setting: php.ini -> aktifkan mysqli, zip (menyesuaikan kebutuhan proyek)
3. Text editor: Visual studio code
4. Extension: Untuk helper PHP
5. Memiliki skill setidaknya html, css, dan php

# Instalasi Laravel

1. Buka terminal:

```bash
    composer create-project --prefer-dist laravel/laravel nama_proyek
```

2. buka folder proyek dengan VSCODE
3. arahkan terminal / cmd ke folder root proyek kemudian tes proyek dengan menggunakan

```bash
    php artisan serve
```

# Konfigurasi Laravel

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

# Konfigurasi Database

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
