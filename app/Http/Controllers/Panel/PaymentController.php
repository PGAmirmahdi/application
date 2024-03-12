<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::latest()->paginate(30);
        return view('panel.payments.index', compact('payments'));
    }

    public function search(Request $request)
    {
        $status = $request->status == 'all' ? array_keys(Payment::STATUS) : [$request->status];
        $users_id = User::where('name','like',"$request->name%")->where('family','like',"$request->family%")->pluck('id');
        $orders_id = Order::whereIn('user_id', $users_id)->pluck('id');

        $payments = Payment::whereIn('order_id', $orders_id)
            ->whereIn('status', $status)
            ->latest()->paginate(30);

        return view('panel.payments.index', compact('payments'));
    }
}
