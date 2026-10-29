<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Invoice</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/realtime.js'])
    <style>
        body { margin: 0; background: #f5f6f2; color: #17211b; font-family: Inter, ui-sans-serif, system-ui, sans-serif; }
        main { width: min(960px, calc(100% - 32px)); margin: 40px auto; }
        .top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; }
        h1 { margin: 0; font-size: 30px; }
        a { color: #167346; font-weight: 700; text-decoration: none; }
        .panel { overflow: hidden; border: 1px solid #e5e9e3; border-radius: 14px; background: #fff; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px 16px; border-bottom: 1px solid #e5e9e3; text-align: left; }
        th { background: #eef5f0; color: #155e3b; font-size: 12px; text-transform: uppercase; }
        .empty { padding: 28px 16px; color: #69776e; text-align: center; }
        .pagination { padding: 16px; }
    </style>
</head>
<body data-user-id="{{ auth()->id() }}">
    <main>
        <header class="top">
            <h1>Daftar Invoice</h1>
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('kasir.dashboard') }}">Kembali ke dashboard</a>
        </header>
        <section class="panel">
            @if ($orders->isEmpty())
                <div class="empty">Belum ada transaksi yang dapat dibuatkan invoice.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td><a href="{{ route('orders.show', $order) }}">INV-{{ str_pad((string) $order->id, 6, '0', STR_PAD_LEFT) }}</a></td>
                                <td>{{ $order->customer_name ?: 'Pelanggan umum' }}</td>
                                <td>{{ $order->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>Rp{{ number_format((float) $order->total_amount, 0, ',', '.') }}</td>
                                <td><a href="{{ route('orders.invoice', $order) }}">Unduh PDF</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination">{{ $orders->links() }}</div>
            @endif
        </section>
    </main>
    <form action="/orders" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Buat Order Baru</button>
    </form>

</body>
</html>
