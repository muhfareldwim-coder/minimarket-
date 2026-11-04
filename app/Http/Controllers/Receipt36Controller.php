<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Receipt36Service;
use Barryvdh\DomPDF\Facade\Pdf;

class Receipt36Controller extends Controller
{
    public function print($id, Receipt36Service $receiptService)
    {
        $order = Order::with('items.product')->findOrFail($id);

        $filePath = $receiptService->print($order);

        return response()->json([
            'success' => true,
            'message' => 'Struk berhasil dibuat.',
            'file' => basename($filePath),
        ]);
    }
}
