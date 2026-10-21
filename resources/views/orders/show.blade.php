<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice #{{ $order->id }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { margin: 0; background: #f5f6f2; color: #17211b; font-family: Inter, ui-sans-serif, system-ui, sans-serif; }
        main { width: min(860px, calc(100% - 32px)); margin: 40px auto; }
        .top { display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 22px; }
        h1 { margin: 0; font-size: 30px; }
        a { color: #167346; font-weight: 700; text-decoration: none; }
        .button { display: inline-block; padding: 11px 16px; border-radius: 9px; background: #167346; color: #fff; }
        .panel { padding: 24px; border: 1px solid #e5e9e3; border-radius: 14px; background: #fff; }
        .meta { display: flex; justify-content: space-between; gap: 16px; color: #69776e; }
        table { width: 100%; margin-top: 22px; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #e5e9e3; text-align: left; }
        th { color: #155e3b; }
        .number { text-align: right; }
        tfoot td { font-weight: 800; }
        @media (max-width: 560px) { .top, .meta { align-items: flex-start; flex-direction: column; } .panel { padding: 16px; } }
    </style>
</head>
<body>
    <main>
        <header class="top">
            <h1>Invoice INV-{{ str_pad((string) $order->id, 6, '0', STR_PAD_LEFT) }}</h1>
            <a class="button" href="{{ route('orders.invoice', $order) }}">Unduh PDF</a>
        </header>
        <section class="panel">
            <div class="meta">
                <span>Pelanggan: {{ $order->customer_name ?: 'Pelanggan umum' }}</span>
                <span>Tanggal: {{ $order->created_at?->format('d/m/Y H:i') ?? '-' }}</span>
            </div>
            <table>
                <thead>
                    <tr><th>Produk</th><th class="number">Harga</th><th class="number">Jumlah</th><th class="number">Subtotal</th></tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name ?: ($item->product?->name ?? 'Produk dihapus') }}</td>
                            <td class="number">Rp{{ number_format((float) $item->price, 0, ',', '.') }}</td>
                            <td class="number">{{ $item->quantity }}</td>
                            <td class="number">Rp{{ number_format((float) ($item->subtotal ?? $item->price * $item->quantity), 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="number">Total</td>
                        <td class="number">Rp{{ number_format((float) $order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </section>
    </main>
</body>
</html>
