<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Katalog Produk | Minimarket</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { margin: 0; background: #f5f6f2; color: #17211b; font-family: Inter, ui-sans-serif, system-ui, sans-serif; }
        .shell { width: min(1120px, calc(100% - 40px)); margin: 0 auto; }
        .topbar { display: flex; justify-content: space-between; align-items: center; padding: 24px 0; }
        .brand { color: #155e3b; font-size: 20px; font-weight: 800; letter-spacing: -.04em; text-decoration: none; }
        .button { display: inline-flex; align-items: center; justify-content: center; padding: 11px 17px; border-radius: 10px; background: #167346; color: white; font-size: 14px; font-weight: 700; text-decoration: none; }
        .button:hover { background: #105c37; }
        .hero { padding: 44px 0 30px; }
        .eyebrow { color: #167346; font-size: 12px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
        h1 { margin: 10px 0 8px; font-size: clamp(32px, 5vw, 48px); letter-spacing: -.05em; }
        .subheading { margin: 0; color: #66736b; }
        .notice { margin: 0 0 24px; padding: 14px 16px; border: 1px solid #a7e0bd; border-radius: 12px; background: #eaf8ef; color: #155e3b; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 20px; padding-bottom: 44px; }
        .card { overflow: hidden; border: 1px solid #e5e9e3; border-radius: 17px; background: #fff; box-shadow: 0 7px 22px #193c2510; }
        .photo { display: block; width: 100%; aspect-ratio: 1 / 1; object-fit: cover; background: #e8efe9; }
        .placeholder { display: grid; place-items: center; color: #72917c; font-size: 14px; }
        .content { padding: 17px; }
        .sku { color: #7a877f; font-size: 11px; font-weight: 700; letter-spacing: .08em; }
        .name { margin: 7px 0; font-size: 18px; font-weight: 750; }
        .description { min-height: 38px; margin: 0 0 14px; color: #69776e; font-size: 13px; line-height: 1.5; }
        .details { display: flex; justify-content: space-between; align-items: center; gap: 8px; }
        .price { color: #167346; font-size: 18px; font-weight: 800; }
        .stock { color: #69776e; font-size: 12px; }
        .empty { padding: 54px 20px; border: 1px dashed #cbd5cd; border-radius: 17px; background: white; text-align: center; color: #69776e; }
        .pagination { display: flex; justify-content: center; padding: 0 0 40px; }
        @media (max-width: 520px) { .shell { width: min(100% - 28px, 1120px); } .topbar { padding: 18px 0; } .button { padding: 10px 12px; } }
    </style>
</head>
<body>
    <header class="shell topbar">
        <a class="brand" href="{{ route('products.index') }}">MINIMARKET</a>
        @auth
            @if(auth()->user()->role === 'admin')
                <a class="button" href="{{ route('products.create') }}">+ Tambah produk</a>
            @endif
        @else
            <a class="button" href="{{ route('login') }}">Login admin</a>
        @endauth
    </header>

    <main class="shell">
        <section class="hero">
            <div class="eyebrow">Produk pilihan untuk kebutuhan harian</div>
            <h1>Katalog Produk</h1>
            <p class="subheading">Lihat produk minimarket dan stok yang tersedia.</p>
        </section>

        @if (session('success'))
            <div class="notice" role="status">{{ session('success') }}</div>
        @endif

        @if ($products->isEmpty())
            <section class="empty">
                <strong>Belum ada produk.</strong>
                <p>Admin bisa menambahkan produk untuk melihat hasil upload dan manipulasi gambar di sini.</p>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a class="button" href="{{ route('products.create') }}">Tambah produk pertama</a>
                    @endif
                @endauth
            </section>
        @else
            <section class="grid" aria-label="Daftar produk">
                @foreach ($products as $product)
                    <article class="card">
                        @if ($product->image)
                            <img class="photo" src="{{ asset('storage/'.$product->image) }}" alt="Gambar {{ $product->name }}">
                        @else
                            <div class="photo placeholder" role="img" aria-label="Tidak ada gambar produk">Gambar belum tersedia</div>
                        @endif
                        <div class="content">
                            <div class="sku">{{ $product->sku }}</div>
                            <h2 class="name">{{ $product->name }}</h2>
                            <p class="description">{{ $product->description ?: 'Produk kebutuhan sehari-hari.' }}</p>
                            <div class="details">
                                <span class="price">Rp{{ number_format((float) $product->price, 0, ',', '.') }}</span>
                                <span class="stock">Stok {{ $product->stock }}</span>
                            </div>
                            @auth
                                @if(auth()->user()->role === 'admin' && ! $product->image)
                                    <form method="POST" action="{{ route('products.image.update', $product) }}" enctype="multipart/form-data" style="margin-top: 16px; padding-top: 14px; border-top: 1px solid #e5e9e3;">
                                        @csrf
                                        <label for="image-{{ $product->id }}" style="display: block; margin-bottom: 8px; font-size: 12px; font-weight: 700;">Unggah gambar produk ini</label>
                                        <input id="image-{{ $product->id }}" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required style="width: 100%; font-size: 12px;">
                                        <input type="hidden" name="image_rotation" value="0">
                                        <button class="button" type="submit" style="margin-top: 10px; border: 0; cursor: pointer;">Simpan gambar</button>
                                        @error('image')
                                            <p role="alert" style="margin: 8px 0 0; color: #b42318; font-size: 12px;">{{ $message }}</p>
                                        @enderror
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </article>
                @endforeach
            </section>
            <div class="pagination">{{ $products->links() }}</div>
        @endif
    </main>
</body>
</html>
