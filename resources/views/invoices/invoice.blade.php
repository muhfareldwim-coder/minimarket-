<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice['id'] }}</title>
    <style>
        @page { margin: 32px; }
        body { color: #1f2937; font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { border-bottom: 2px solid #167346; margin-bottom: 24px; padding-bottom: 14px; }
        .header h1 { color: #167346; font-size: 24px; margin: 0 0 10px; }
        .meta { color: #4b5563; line-height: 1.7; }
        .table { border-collapse: collapse; margin-top: 20px; width: 100%; }
        .table th { background: #eef5f0; color: #155e3b; text-align: left; }
        .table th, .table td { border: 1px solid #d1d5db; padding: 9px; }
        .number { text-align: right; }
        .total { font-size: 14px; font-weight: bold; text-align: right; }
        .footer { color: #6b7280; margin-top: 28px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE</h1>
        <div class="meta">
            <div>Nomor: INV-{{ str_pad((string) $invoice['id'], 6, '0', STR_PAD_LEFT) }}</div>
            <div>Tanggal: {{ $invoice['created_at']?->format('d/m/Y H:i') ?? '-' }}</div>
            <div>Pelanggan: {{ $invoice['customer_name'] ?: 'Pelanggan umum' }}</div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th class="number">Harga</th>
                <th class="number">Jumlah</th>
                <th class="number">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice['items'] as $item)
            <tr>
                <td>{{ $item['product_name'] }}</td>
                <td class="number">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                <td class="number">{{ $item['quantity'] }}</td>
                <td class="number">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="total">Total:</td>
                <td class="total">Rp {{ number_format($invoice['total'], 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
    <div class="footer">Terima kasih telah berbelanja.</div>
</body>
</html>
