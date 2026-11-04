<?php

namespace App\Http\Controllers;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

class ReceiptController extends Controller
{
    public function print()
    {
        try {

            $folder = storage_path('app/struk');

            if (!file_exists($folder)) {
                mkdir($folder, 0755, true);
            }

            $connector = new FilePrintConnector(
                $folder . '/struk.prn'
            );

            $printer = new Printer($connector);

            // Mencetak struk
            $printer->setJustification(Printer::JUSTIFY_CENTER);

            $printer->setEmphasis(true);
            $printer->text("TOKO MINIMARKET\n");
            $printer->setEmphasis(false);

            $printer->text("Jl. Mawar No. 123\n");

            $printer->text("============================\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);

            $printer->text("Tanggal : " . date('d-m-Y H:i:s') . "\n");

            $printer->text("----------------------------\n");

            $printer->text("Indomie       2 x 3.000\n");
            $printer->text("Aqua          1 x 5.000\n");

            $printer->text("----------------------------\n");

            $printer->text("Total       : Rp11.000\n");

            $printer->text("============================\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);

            $printer->text("Terima Kasih!\n");
            $printer->text("Selamat Berbelanja Kembali\n");

            // Memotong kertas
            $printer->cut();

            // Menutup koneksi printer
            $printer->close();

            return response()->json([
                'message' => 'Struk berhasil dicetak'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }
    }
}
