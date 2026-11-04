<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class Receipt36PdfController extends Controller
{
    public function show(Order $order)
    {
        $transaksi = $order->load('items.product');

        $subtotal = (float) $transaksi->items()->sum('subtotal');
        $diskon = 0;
        $totalBayar = $transaksi->total_amount;

        $pdf = Pdf::loadView('struk.receipt', compact(
            'transaksi',
            'subtotal',
            'diskon',
            'totalBayar'
        ));

        $pdf->setPaper([0, 0, 226.77, 500], 'portrait');

        return $pdf->stream('struk-kopi-kita-' . $transaksi->getKey() . '.pdf');
    }
}
