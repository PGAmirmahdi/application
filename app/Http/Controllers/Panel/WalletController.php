<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\GuideVideos;
use App\Models\User;
use App\Models\Wallet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * @return Response
     */
    public function show($id)
    {
        //
    }

    public function edit(Wallet $wallet)
    {
        $usersWithoutWallets = User::doesntHave('wallet')->get();
        return view('panel.wallet.edit', compact('wallet', 'usersWithoutWallets'));
    }
    public function update(Request $request)
    {
        // Validate the request data
        $request->validate([
            'balance' => 'required|numeric', // Ensure balance is a number
            'user_id' => 'required|exists:users,id' // Ensure user_id exists in users table
        ]);

        // Find the wallet associated with the user
        $wallet = Wallet::where('user_id', $request->user_id)->firstOrFail();

        // Update the wallet balance
        $wallet->balance = $request->balance;
        $wallet->save();

        // Return a successful response
        return response()->json([
            'success' => true,
            'message' => 'Wallet balance updated successfully',
            'wallet' => $wallet
        ], 200);
    }

    public function destroy(Wallet $wallet)
    {
        // Update the users associated with this wallet, setting their wallet_id to null
        User::where('wallet_id', $wallet->id)->update(['wallet_id' => null]);

        // Delete the wallet
        $wallet->delete();

        // Return back with success message
        return back()->with('success', 'کیف پول با موفقیت حذف شد');
    }


    public function search(Request $request)
    {
        $query = Wallet::query();

        if ($request->has('user_name')) {
            $query->whereHas('users', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user_name . '%');
            });
        }

        if ($request->has('balance')) {
            $query->where('balance', 'like', '%' . $request->balance . '%');
        }

        if ($request->has('user_phone')) {
            $query->whereHas('users', function ($q) use ($request) {
                $q->where('phone', 'like', '%' . $request->user_phone . '%');
            });
        }

        $wallets = $query->latest()->paginate(30);

        return view('panel.wallet.index', compact('wallets'));
    }

}
