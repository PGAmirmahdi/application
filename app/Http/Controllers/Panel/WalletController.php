<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\GuideVideos;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $wallets = Wallet::latest()->paginate(30);
        return view('panel.wallet.index', compact('wallets'));
    }

    public function create()
    {
        return view('panel.wallet.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'balance' => 'required',
            'user_id' => 'required|exists:users,id'
        ]);

        // Find the user
        $user = User::find($request->user_id);

        // Check if the user already has a wallet
        if ($user->wallet) {
            return response()->json([
                'message' => 'User already has a wallet.'
            ], 400); // Bad Request
        }

        // Create a new wallet for the user
        $wallet = Wallet::create([
            'balance' => $request->balance,
            'user_id' => $request->user_id
        ]);

        // Update the user's wallet_id
        $user->wallet_id = $wallet->id;
        $user->save();

        return response()->json([
            'message' => 'Wallet created successfully!',
            'wallet' => $wallet
        ], 201); // Created
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

    public function destroy(Wallet $wallet,$id)
    {
        $wallet::query()->where('id', $id)->delete();
        return back();
    }
    public function search(Request $request)
    {
        $query = Wallet::query();

        if ($request->has('user_name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user_name . '%');
            });
        }

        if ($request->has('balance')) {
            $query->where('balance', 'like', '%' . $request->balance . '%');
        }

        if ($request->has('user_phone')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('phone', 'like', '%' . $request->user_phone . '%');
            });
        }

        $wallets = $query->latest()->paginate(30);

        return view('panel.GuideVideos.index', compact('wallets'));
    }

}
