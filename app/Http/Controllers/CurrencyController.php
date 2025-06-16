<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function switch(Request $request)
    {
        $currency = $request->input('currency', 'EUR');
        session(['currency' => $currency]);
        return back();
    }
}