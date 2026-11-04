<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk Kopi Kita</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 10px;
        }

        .center {
            text-align: center;
        }

        h2 {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 4px 0;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .right {
            text-align: right;
        }

        .total {
            font-weight: bold;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <div class="center">
        <h2>KOPI KITA</h2>
        <p>STRUK PEMBAYARAN</p>
    </div>

    <div class="line"></div>

    <p>No. Transaksi: {{ $transaksi->id }}</p>
    <p>Pelanggan: {{ $transaksi->customer_name ?? 'Guest' }}</p>
    <p>Tanggal: {{ $transaksi->created_at->format('d-m-Y H:i:s') }}</p>

    <div class="line"></div>

    <table>
        @foreach ($transaksi->items as $item)
            <tr>
                <td colspan="2">
                    {{ $item->product_name ?: ($item->product?->name ?? 'Produk dihapus') }}
                </td>
            </tr>
            <tr>
                <td>
                    {{ $item->quantity }} x
                    Rp{{ number_format($item->price, 0, ',', '.') }}
                </td>
                <td class="right">
                    Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                </td>
            </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td>Subtotal</td>
            <td class="right">
                Rp{{ number_format($subtotal, 0, ',', '.') }}
            </td>
        </tr>

        <tr>
            <td>Diskon</td>
            <td class="right">
                Rp{{ number_format($diskon, 0, ',', '.') }}
            </td>
        </tr>

        <tr class="total">
            <td>Total Bayar</td>
            <td class="right">
                Rp{{ number_format($totalBayar, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="center">
        <p>Terima kasih telah berbelanja!</p>
        <strong>KOPI KITA</strong>
    </div>

</body>
</html> 
