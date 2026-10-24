<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function index()
    {
        return view('uji-email');
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
        ]);

        $user = (object) [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        Mail::to($user->email)->send(new WelcomeMail($user));

        return back()->with(
            'success',
            'Email berhasil diproses oleh Laravel!'
        );
    }
}
