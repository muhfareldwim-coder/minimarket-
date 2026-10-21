<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Produk | Minimarket</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { margin: 0; background: #f5f6f2; color: #17211b; font-family: Inter, ui-sans-serif, system-ui, sans-serif; }
        .shell { width: min(920px, calc(100% - 36px)); margin: 0 auto; }
        .topbar { display: flex; justify-content: space-between; align-items: center; padding: 24px 0; }
        a { color: #167346; font-weight: 700; text-decoration: none; }
        .brand { font-size: 20px; font-weight: 800; letter-spacing: -.04em; }
        .panel { margin: 20px 0 48px; padding: clamp(22px, 5vw, 42px); border: 1px solid #e5e9e3; border-radius: 18px; background: #fff; box-shadow: 0 10px 32px #193c2510; }
        .eyebrow { color: #167346; font-size: 12px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
        h1 { margin: 8px 0; font-size: clamp(30px, 5vw, 40px); letter-spacing: -.05em; }
        .intro { margin: 0 0 26px; color: #69776e; }
        .fields { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
        .field { display: flex; flex-direction: column; gap: 7px; }
        .wide { grid-column: 1 / -1; }
        label { font-size: 13px; font-weight: 750; }
        input, textarea, select { width: 100%; box-sizing: border-box; padding: 11px 12px; border: 1px solid #d8e0da; border-radius: 9px; background: #fff; color: #17211b; font: inherit; }
        input:focus, textarea:focus, select:focus { border-color: #167346; outline: 2px solid #16734622; }
        textarea { min-height: 90px; resize: vertical; }
        .hint { color: #758179; font-size: 12px; }
        .error { color: #b42318; font-size: 12px; }
        .image-preview { display: none; width: 180px; aspect-ratio: 1 / 1; margin-top: 12px; border-radius: 12px; object-fit: cover; background: #e8efe9; }
        .actions { display: flex; align-items: center; gap: 16px; margin-top: 26px; }
        .button { display: inline-flex; justify-content: center; border: 0; border-radius: 10px; padding: 12px 18px; background: #167346; color: #fff; cursor: pointer; font: inherit; font-weight: 750; }
        .button:hover { background: #105c37; }
        .alert { margin-bottom: 20px; padding: 13px 15px; border-radius: 10px; background: #fff0ef; color: #a12620; }
        @media (max-width: 620px) { .fields { grid-template-columns: 1fr; } .wide { grid-column: auto; } }
    </style>
</head>
<body>
    <header class="shell topbar">
        <a class="brand" href="{{ route('products.index') }}">MINIMARKET</a>
        <a href="{{ route('products.index') }}">← Kembali ke katalog</a>
    </header>

    <main class="shell">
        <section class="panel">
            <div class="eyebrow">Kelola katalog</div>
            <h1>Tambah Produk</h1>
            <p class="intro">Gambar akan dipotong menjadi 800 × 800 piksel sebelum ditampilkan di katalog.</p>

            @if ($errors->any())
                <div class="alert" role="alert">Periksa kembali data yang diisi. Bagian yang perlu diperbaiki ditandai di bawah.</div>
            @endif

            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="fields">
                    <div class="field">
                        <label for="name">Nama produk</label>
                        <input id="name" name="name" value="{{ old('name') }}" required maxlength="255">
                        @error('name') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="field">
                        <label for="sku">Kode SKU</label>
                        <input id="sku" name="sku" value="{{ old('sku') }}" required maxlength="100">
                        @error('sku') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="field">
                        <label for="price">Harga (Rp)</label>
                        <input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price') }}" required>
                        @error('price') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="field">
                        <label for="stock">Stok</label>
                        <input id="stock" name="stock" type="number" min="0" step="1" value="{{ old('stock', 0) }}" required>
                        @error('stock') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="field wide">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" maxlength="5000">{{ old('description') }}</textarea>
                        @error('description') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="field wide">
                        <label for="image">Gambar produk</label>
                        <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required>
                        <span class="hint">Wajib diisi. JPG, PNG, atau WebP; ukuran maksimal 2 MB.</span>
                        @error('image') <span class="error">{{ $message }}</span> @enderror
                        <img id="image-preview" class="image-preview" alt="Pratinjau gambar yang dipilih">
                    </div>
                    <div class="field">
                        <label for="image_rotation">Rotasi gambar</label>
                        <select id="image_rotation" name="image_rotation">
                            <option value="0" @selected(old('image_rotation', '0') === '0')>Tanpa rotasi</option>
                            <option value="90" @selected(old('image_rotation') === '90')>90° searah jarum jam</option>
                            <option value="-90" @selected(old('image_rotation') === '-90')>90° berlawanan arah jarum jam</option>
                            <option value="180" @selected(old('image_rotation') === '180')>180°</option>
                        </select>
                        @error('image_rotation') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="field">
                        <label for="watermark_text">Watermark teks (opsional)</label>
                        <input id="watermark_text" name="watermark_text" value="{{ old('watermark_text') }}" maxlength="50" placeholder="Contoh: Minimarket Kita">
                        @error('watermark_text') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="actions">
                    <button class="button" type="submit">Simpan dan lihat hasil</button>
                    <a href="{{ route('products.index') }}">Batal</a>
                </div>
            </form>
        </section>
    </main>
    <script>
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        let previewUrl;

        imageInput.addEventListener('change', () => {
            if (previewUrl) URL.revokeObjectURL(previewUrl);

            const [file] = imageInput.files;
            if (!file) {
                imagePreview.removeAttribute('src');
                imagePreview.style.display = 'none';
                return;
            }

            previewUrl = URL.createObjectURL(file);
            imagePreview.src = previewUrl;
            imagePreview.style.display = 'block';
        });
    </script>
</body>
</html>
