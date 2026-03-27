<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;


class PaymentController extends Controller
{
    public function handleNotification(Request $request)
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        $notif = new Notification();

        $order = Order::where('order_code', $notif->order_id)->firstOrFail();

        $paymentStatus = match ($notif->transaction_status) {
            'settlement', 'capture' => 'Paid',
            'pending'               => 'Unpaid',
            'deny', 'cancel'        => 'Failed',
            'expire'                => 'Expired',
            default                 => 'Unpaid',
        };

        $order->update([
            'payment_status' => $paymentStatus,
            'payment_method' => $notif->payment_type,
        ]);

        return response()->json(['status' => 'ok']);
    }
}
