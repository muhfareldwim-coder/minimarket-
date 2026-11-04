<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Receipt36PdfController;
use App\Mail\NotifyUser;
use App\Models\User;
use App\Notifications\InvoicePaid;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\Receipt36Controller;

Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:admin,kasir')->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    });
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {

    Route::middleware('role:admin')->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::post('/products/{product}/image', [ProductController::class, 'updateImage'])->name('products.image.update');

        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
            ->name('admin.dashboard');
    });

    Route::middleware('role:kasir')->group(function () {
        Route::get('/kasir/dashboard', [KasirController::class, 'dashboard'])
            ->name('kasir.dashboard');
    });

});
Route::get('/produk-gambar', [
    ProductImageController::class,
    'index'
])->name('produk-gambar.index');

Route::post('/produk-gambar', [
    ProductImageController::class,
    'upload'
])->name('produk-gambar.upload');

Route::post('/produk-gambar/tampilkan', [
    ProductImageController::class,
    'tampilkan'
])->name('produk-gambar.tampilkan');

Route::get('/uji-email', [EmailController::class, 'index'])
    ->name('email.index');

Route::post('/uji-email', [EmailController::class, 'send'])
    ->name('email.send');

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (
        EmailVerificationRequest $request
    ) {
        $request->fulfill();

        return redirect('/dashboard');
    })->middleware(['signed', 'throttle:6,1'])
      ->name('verification.verify');

    Route::post('/email/verification-notification', function (
        Request $request
    ) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with(
            'message',
            'Link verifikasi dikirim ulang!'
        );
    })->middleware('throttle:6,1')
      ->name('verification.send');
});

Route::get('/uji-markdown', function () {
    return view('uji-markdown');
});

Route::post('/uji-markdown', function (Request $request) {
    $data = $request->validate([
        'name' => 'required|string|max:100',
        'email' => 'required|email',
    ]);

    $user = (object) [
        'name' => $data['name'],
        'email' => $data['email'],
    ];

    Mail::to($user->email)->send(new NotifyUser($user));

    return back()->with('success', 'Email Markdown sudah diproses.');
});
Route::get('/uji-notifikasi/{id}', function ($id) {
    $user = User::findOrFail($id);

    $user->notify(new InvoicePaid());

    return 'Notifikasi email sudah diproses.';
});

Route::post('/orders', [OrderController::class, 'store'])->middleware('auth');



Route::get('/print-receipt', [ReceiptController::class, 'print']);



// ACARA 36 - Simulasi cetak struk ESC/POS
Route::get('/transaksi/{id}/struk-acara36', [
    Receipt36Controller::class,
    'print'
])->name('transaksi.struk.acara36');

Route::get('/transaksi/{order}/pdf-acara36', [
    Receipt36PdfController::class,
    'show',
])->name('transaksi.pdf.acara36');

Route::get('/transaksi/{order}/struk', [
    Receipt36PdfController::class,
    'show',
])->name('transaksi.struk');
