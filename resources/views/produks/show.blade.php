<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Menampilkan Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: lightgray">
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col md-4">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body text-center">
                        <img src="{{asset("/storage/produk/".$produk->foto)}}" alt="Foto Produk" class="rounded" style="width:400px">
                    </div>
                </div>
            </div>
            <div class="col md-8">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <h3>{{$produk->nama}}</h3>
                        <hr>
                        <p>{{'RP'.number_format($produk->harga,2,',','.')}}</p>
                        <code>
                            <p>{!! $produk->deskripsi !!}</p>
                        </code>
                        <hr>
                        <p>{{$produk->stok}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
