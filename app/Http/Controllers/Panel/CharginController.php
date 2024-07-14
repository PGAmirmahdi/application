<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Charging;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class CharginController extends Controller
{
    public function index()
    {
        $wallets = Charging::latest()->paginate(30);
        return view('panel.charging.index', compact('wallets'));
    }

    public function create()
    {
        return view('panel.charging.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'type' => 'required',
            'user_id' => 'required|exists:users,id',
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric',
            'description' => 'nullable'
        ]);

        // Get the wallet for the user
        $wallet = Wallet::where('user_id', $request->user_id)->where('id', $request->wallet_id)->first();

        if (!$wallet) {
            return response()->json([
                'message' => 'کیف پول یافت نشد!'
            ], 404);
        }

        // Determine the new balance based on the type of transaction
        if ($request->type == 'settle') {
            $newBalance = $wallet->balance + $request->amount;
        } else if ($request->type == 'harvest') {
            $newBalance = $wallet->balance - $request->amount;
        } else {
            return response()->json([
                'message' => 'خطایی  غیر منتظره!'
            ], 400);
        }

        // Update the wallet balance
        $wallet->balance = $newBalance;
        $wallet->save();

        // Create a new charging record for the user
        $charging = Charging::create([
            'type' => $request->type,
            'user_id' => $request->user_id,
            'wallet_id' => $request->wallet_id,
            'amount' => $request->amount,
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'کیف پول با موفقیت به روز شد!',
            'wallet' => $wallet,
            'charging' => $charging
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
