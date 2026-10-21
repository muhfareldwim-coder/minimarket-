<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * Daftar semua order.
     */
    public function index()
    {
        $orders = Order::latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Detail satu order.
     */
    public function show(Order $order)
    {
        $order->load('items.product');

        return view('orders.show', compact('order'));
    }

    /**
     * Generate dan unduh invoice PDF (Acara 30).
     */
    public function invoice(Order $order)
    {
        $order->load('items.product');
        $items = $order->items->map(function ($item): array {
            return [
                'product_name' => $item->product_name ?: ($item->product?->name ?? 'Produk dihapus'),
                'price' => (float) $item->price,
                'quantity' => $item->quantity,
                'total' => (float) ($item->subtotal ?? ($item->price * $item->quantity)),
            ];
        });
        $itemTotal = $items->sum('total');
        $invoice = [
            'id' => $order->getKey(),
            'created_at' => $order->getAttribute('created_at'),
            'customer_name' => $order->getAttribute('customer_name'),
            'items' => $items,
            'total' => (float) ($order->total_amount ?: $itemTotal),
        ];

        $pdf = Pdf::loadView('invoices.invoice', ['invoice' => $invoice])
            ->setPaper('a4');

        return $pdf->download('invoice_'.$invoice['id'].'.pdf');
    }
}
