
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Image Manipulation</title>
</head>
<body>
    <h2>Laravel Image Manipulation</h2>

    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p style="color: red">{{ $error }}</p>
        @endforeach
    @endif

    <h3>Upload dan Manipulasi Gambar</h3>

    <form action="{{ route('produk-gambar.upload') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <p>
            <label>Pilih gambar:</label><br>
            <input type="file" name="photo" accept="image/jpeg,image/png" required>
        </p>

        <p>
            <label>Pilih operasi:</label><br>
            <select name="operasi" required>
                <option value="resize">Resize 300 x 300</option>
                <option value="crop">Crop 200 x 200</option>
                <option value="rotate">Rotate 45 derajat</option>
                <option value="watermark">Watermark</option>
                <option value="format">Ubah JPG ke PNG</option>
            </select>
        </p>

        <button type="submit">Proses dan Simpan Gambar</button>
    </form>

    <hr>

    <h3>Menampilkan Gambar Tanpa Menyimpan</h3>

    <form action="{{ route('produk-gambar.tampilkan') }}"
          method="POST"
          enctype="multipart/form-data"
          target="_blank">
        @csrf

        <p>
            <label>Pilih gambar:</label><br>
            <input type="file" name="photo" accept="image/jpeg,image/png" required>
        </p>

        <button type="submit">Tampilkan di Browser</button>
    </form>

    @if (session('filename'))
        <hr>
        <h3>Hasil Manipulasi</h3>

        <img
            src="{{ asset('uploads/' . session('gambar')) }}"
            alt="Hasil manipulasi"
            style="max-width: 300px; height: auto;"
        >

        <p>
            <a href="{{ asset('uploads/' . session('gambar')) }}"
               target="_blank">
                Buka gambar
            </a>
        </p>
    @endif
</body>
</html>
