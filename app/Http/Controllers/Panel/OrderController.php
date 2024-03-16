<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(30);
        return view('panel.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('panel.orders.show', compact('order'));
    }

    public function search(Request $request)
    {
        $status = $request->status == 'all' ? array_keys(Order::STATUS) : [$request->status];

        $users_id = User::where('name','like',"$request->name%")
            ->where('family','like',"$request->family%")
            ->where('phone','like',"$request->phone%")
            ->pluck('id');

        $orders = Order::whereIn('user_id',$users_id)
            ->whereIn('status', $status)
            ->latest()->paginate(30);

        return view('panel.orders.index', compact('orders'));
    }

    public function changeStatus(Request $request)
    {
        Order::find($request->order_id)->update(['status' => $request->status]);

        return back();
    }

    public function cancel(Request $request)
    {
        Order::find($request->order_id)->update(['status' => 'canceled']);

        alert()->success('سفارش با موفقیت لغو شد','لغو سفارش');
        return back();
    }
}
