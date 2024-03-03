<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Role;
use App\Models\User;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PanelController extends Controller
{
    public function index(Request $request)
    {
        return view('panel.index');
    }

    public function readNotification($notification = null)
    {
        if ($notification == null){
            auth()->user()->unreadNotifications->markAsRead();
            return back();
        }

        $notif = auth()->user()->unreadNotifications()->whereId($notification)->first();
        if (!$notif){
            return back();
        }

        $notif->markAsRead();
        return redirect()->to($notif->data['url']);
    }

    public function login(Request $request)
    {
        if ($request->method() == 'GET'){
            $users = User::where('id', '!=', auth()->id())->whereIn('id', [3, 4, 152])->get(['id','name','family']);

            return view('panel.login', compact('users'));
        }

        Auth::loginUsingId($request->user);
        return redirect()->route('panel');
    }

    public function sendSMS(Request $request)
    {
        $result = sendSMS($request->bodyId, $request->phone, $request->args, ['text' => $request->text]);
        return $result;
    }

    public function najva_token_store(Request $request)
    {
        \auth()->user()->update([
            'najva_token' => $request->najva_user_token
        ]);

        return response()->json(['data' => 'your token stored: '. $request->najva_user_token]);
    }

    public function saveFCMToken(Request $request)
    {
        auth()->user()->update(['fcm_token' => $request->token]);
        return response()->json(['token saved successfully.']);
    }

    private function getFactorsMonthly()
    {
        $factors = [
            'فروردین' => 0,
            'اردیبهشت' => 0,
            'خرداد' => 0,
            'تیر' => 0,
            'مرداد' => 0,
            'شهریور' => 0,
            'مهر' => 0,
            'آبان' => 0,
            'آذر' => 0,
            'دی' => 0,
            'بهمن' => 0,
            'اسفند' => 0,
        ];

        for ($i = 1; $i <= 12; $i++)
        {
            $from_date = \verta()->month($i)->startMonth()->toCarbon()->toDateTimeString();
            $to_date = \verta()->month($i)->endMonth()->toCarbon()->toDateTimeString();

            // factors
            $factors1 = Invoice::whereBetween('invoices.created_at', [$from_date, $to_date])->whereHas('products', function ($query) {
                $query->select('products.id', 'invoice_product.invoice_net');
            })->where('status','invoiced')
                ->join('invoice_product', 'invoices.id', '=', 'invoice_product.invoice_id')
                ->groupBy('province')
                ->select('province', DB::raw('SUM(invoice_product.invoice_net) as amount'))
                ->get(['amount']);

            // factors
            $factors2 = Invoice::whereBetween('invoices.created_at', [$from_date, $to_date])->whereHas('other_products', function ($query) {
                $query->select('other_products.invoice_net');
            })->where('status','invoiced')
                ->join('other_products', 'invoices.id', '=', 'other_products.invoice_id')
                ->groupBy('province')
                ->select('province', DB::raw('SUM(other_products.invoice_net) as amount'))
                ->get(['amount']);

            $month = \verta()->month($i)->format('%B');

            foreach ($factors1 as $item){
                $factors[$month] += $item->amount;
            }
            foreach ($factors2 as $item){
                $factors[$month] += $item->amount;
            }

            $factors_discounts_amount = Invoice::whereBetween('invoices.created_at', [$from_date, $to_date])->where('status','invoiced')->sum('discount');
            $factors[$month] -= $factors_discounts_amount;
        }

        return collect($factors);
    }
}
