<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductImageController extends Controller
{
    private function manager()
    {
        return new ImageManager(new Driver());
    }

    public function index()
    {
        return view('produk-gambar');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'operasi' => 'required|in:resize,crop,rotate,watermark,format',
        ]);

        $manager = $this->manager();
        $image = $manager->read($request->file('photo'));

        $folder = public_path('uploads');

        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        switch ($request->operasi) {
            case 'resize':
                $image->resize(300, 300);
                $filename = time() . '.jpg';
                $image->toJpeg(quality: 75)
                    ->save($folder . '/' . $filename);
                break;

            case 'crop':
                $image->crop(200, 200);
                $filename = time() . '.jpg';
                $image->toJpeg(quality: 75)
                    ->save($folder . '/' . $filename);
                break;

            case 'rotate':
                $image->rotate(45);
                $filename = time() . '.jpg';
                $image->toJpeg(quality: 75)
                    ->save($folder . '/' . $filename);
                break;

            case 'watermark':
                $watermarkPath = public_path('watermark.png');

                if (!file_exists($watermarkPath)) {
                    return back()->withErrors([
                        'photo' => 'Letakkan watermark.png di folder public terlebih dahulu.',
                    ]);
                }

                $watermark = $manager->read($watermarkPath);
                $image->place($watermark, 'bottom-right', 10, 10);

                $filename = time() . '.jpg';
                $image->toJpeg(quality: 75)
                    ->save($folder . '/' . $filename);
                break;

            case 'format':
                $filename = time() . '.png';
                $image->toPng()
                    ->save($folder . '/' . $filename);
                break;
        }

        return back()->with([
            'success' => 'Gambar berhasil dimanipulasi!',
            'filename' => $filename,
        ]);
    }

    public function tampilkan(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $image = $this->manager()->read($request->file('photo'));

        return response($image->toJpeg()->toString())
            ->header('Content-Type', 'image/jpeg');
    }
}
