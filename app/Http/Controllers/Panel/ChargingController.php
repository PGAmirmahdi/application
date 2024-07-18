<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Charging;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChargingController extends Controller
{
    public function index()
    {
        $chargings = Charging::latest()->paginate(30);
        return view('panel.charging.index', compact('chargings'));
    }

    public function create()
    {
        $users = User::all();
        return view('panel.charging.create', compact('users'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:deposit,withdrawal',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Fetch the user and their wallet
        $user = User::findOrFail($request->user_id);
        $wallet = $user->wallets;

        // Perform the transaction based on type (deposit or withdrawal)
        $amount = $request->amount;
        $type = $request->type;

        if ($type === 'deposit') {
            $wallet->balance += $amount;
        } elseif ($type === 'withdrawal') {
            if ($wallet->balance < $amount) {
                return response()->json(['error' => 'موجودی کافی نیست.'], 422);
            }
            $wallet->balance -= $amount;
        }

        // Save the wallet after the transaction
        $wallet->save();

        // Create a charging record
        Charging::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => $type,
            'description' => $request->description,
            'tracking_code' => (string) random_int(1000000000, 9999999999),
            'wallet_id'=>$wallet->id
        ]);

        // Redirect to index page on success
        return redirect()->route('charging.index')->with('success', 'تراکنش با موفقیت ثبت شد');

    }

    public function search(Request $request)
    {
        $query = Charging::query();

        if ($request->filled('tracking_code')) {
            $query->where('tracking_code', 'like', '%' . $request->tracking_code . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('title')) {
            $query->whereHas('users', function ($q) use ($request) {
                $q->where('family', 'like', '%' . $request->title . '%');
            });
        }

        $chargings = $query->paginate(10);

        return view('panel.charging.index', compact('chargings'));
    }
    public function getUserBalance(User $user_id)
    {
        try {
            $balance = $user_id->wallets->balance;
            return response()->json(['data' => $balance]);
        } catch (\Exception $e) {
            return response()->json(['data' => 0], 404);
        }

    }
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
