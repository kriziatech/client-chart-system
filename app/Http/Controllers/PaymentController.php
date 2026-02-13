<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function receipt(\App\Models\Payment $payment)
    {
        // Access check
        if (auth()->user()->role === 'viewer' && $payment->client->user_id !== auth()->id()) {
            abort(403);
        }

        $payment->load('client');
        return view('payments.receipt', compact('payment'));
    }
}