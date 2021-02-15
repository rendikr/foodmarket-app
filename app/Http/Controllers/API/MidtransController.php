<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        // Midtrans Configuration
        Config::$serverKey    = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized  = config('services.midtrans.isSanitized');
        Config::$is3ds        = config('services.midtrans.is3ds');

        // Midtrans Notification
        $notification = new Notification();

        $status  = $notification->transaction_status;
        $type    = $notification->payment_type;
        $fraud   = $notification->fraud_status;
        $orderID = $notification->order_id;

        // Get Transaction
        $transaction = Transaction::findOrFail($orderID);

        if ($status == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $transaction->status = 'PENDING';
                } else {
                    $transaction->status = 'SUCCESS';
                }
            }

        } else if ($status == 'settlement') {
            $transaction->status = 'SUCCESS';

        } else if ($status == 'pending') {
            $transaction->status = 'PENDING';

        } else if ($status == 'deny') {
            $transaction->status = 'CANCELLED';

        } else if ($status == 'expire') {
            $transaction->status = 'CANCELLED';

        } else if ($status == 'cancel') {
            $transaction->status = 'CANCELLED';

        }

        $transaction->save();
    }

    public function success()
    {
        return view('midtrans.success');
    }

    public function unfinished()
    {
        return view('midtrans.unfinished');
    }

    public function error()
    {
        return view('midtrans.error');
    }
}
