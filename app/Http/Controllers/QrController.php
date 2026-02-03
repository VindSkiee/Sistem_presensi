<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrController extends Controller
{
    public function show()
    {
        $qr = QrCode::size(250)->generate('https://sistemabsenmarina.shop');
        return view('qr.show', compact('qr'));
    }
}
