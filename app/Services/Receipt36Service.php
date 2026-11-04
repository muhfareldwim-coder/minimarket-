<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\File;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

class Receipt36Service
{
    public function print(Order $order): string
    {
        $order->load('items.product');

        $directory = storage_path('app/receipts');

        File::ensureDirectoryExists($directory);

        $filePath = $directory . '/struk-' . $order->id . '.prn';

        $connector = new FilePrintConnector($filePath);
        $printer = new Printer($connector);

        try {
            // Nama toko
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("KOPI KITA\n");
            $printer->setEmphasis(false);
            $printer->text("STRUK PEMBAYARAN\n");
            $printer->text("--------------------------------\n");

            // Informasi transaksi
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("No. Transaksi : {$order->id}\n");
            $printer->text(
                "Pelanggan     : " .
                ($order->customer_name ?? 'Guest') . "\n"
            );
            $printer->text(
                "Tanggal       : " .
                $order->created_at->format('d-m-Y H:i:s') . "\n"
            );

            $printer->text("--------------------------------\n");
            $printer->text("ITEM             QTY       TOTAL\n");
            $printer->text("--------------------------------\n");

            // Daftar barang
            foreach ($order->items as $item) {
                $nama = $item->product_name
                    ?: ($item->product?->name ?? 'Produk dihapus');

                $printer->text($nama . "\n");
                $printer->text(
                    "Rp " . number_format((float) $item->price, 0, ',', '.') .
                    " x " . $item->quantity .
                    " = Rp " .
                    number_format((float) $item->subtotal, 0, ',', '.') .
                    "\n"
                );
            }

            $subtotal = (float) $order->items->sum('subtotal');
            $diskon = 0;
            $totalBayar = (float) $order->total_amount;

            $printer->text("--------------------------------\n");
            $printer->text(
                "Subtotal : Rp " .
                number_format($subtotal, 0, ',', '.') . "\n"
            );
            $printer->text(
                "Diskon   : Rp " .
                number_format($diskon, 0, ',', '.') . "\n"
            );

            $printer->setEmphasis(true);
            $printer->text(
                "TOTAL    : Rp " .
                number_format($totalBayar, 0, ',', '.') . "\n"
            );
            $printer->setEmphasis(false);

            $printer->text("--------------------------------\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Terima kasih telah berbelanja!\n");
            $printer->text("KOPI KITA\n\n\n");

            $printer->cut();
        } finally {
            $printer->close();
        }

        return $filePath;
    }
}
