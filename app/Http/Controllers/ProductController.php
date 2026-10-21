<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(12);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_rotation' => 'nullable|integer|between:-180,180',
            'watermark_text' => 'nullable|string|max:50',
        ]);

        $data['image'] = $this->storeProcessedImage(
            $request->file('image'),
            (int) ($data['image_rotation'] ?? 0),
            $data['watermark_text'] ?? null,
        );

        unset($data['image_rotation'], $data['watermark_text']);

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function updateImage(Request $request, Product $product)
    {
        $data = $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_rotation' => 'nullable|integer|between:-180,180',
            'watermark_text' => 'nullable|string|max:50',
        ]);

        $previousImage = $product->image;
        $product->image = $this->storeProcessedImage(
            $request->file('image'),
            (int) ($data['image_rotation'] ?? 0),
            $data['watermark_text'] ?? null,
        );
        $product->save();

        if ($previousImage) {
            Storage::disk('public')->delete($previousImage);
        }

        return redirect()->route('products.index')->with('success', 'Gambar produk berhasil disimpan.');
    }

    private function storeProcessedImage(UploadedFile $file, int $rotation, ?string $watermarkText): string
    {
        $extension = $file->extension();
        $image = ImageManager::gd()->read($file->getRealPath());

        if ($rotation !== 0) {
            $image->rotate($rotation);
        }

        $image->cover(800, 800);

        if ($watermarkText) {
            $image->text(
                $watermarkText,
                $image->width() - 16,
                $image->height() - 16,
                function ($font) {
                    $font->size(18);
                    $font->color('ffffff');
                    $font->align('right');
                    $font->valign('bottom');
                }
            );
        }

        $encoded = $image->encodeByExtension($extension, quality: 85);
        $path = 'products/'.Str::uuid().'.'.$extension;

        if (! Storage::disk('public')->put($path, $encoded->toString())) {
            throw new \RuntimeException('Gambar produk gagal disimpan.');
        }

        return $path;
    }
}
