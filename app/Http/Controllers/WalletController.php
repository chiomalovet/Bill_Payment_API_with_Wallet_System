<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;

class WalletController extends Controller
{
    public function balance(Request $request)
    {
        
        // Get the currently authenticated user
        $user = $request->user();
    
        // Ensure the user has a wallet
        if (!$user->wallet) {
            return response()->json(['message' => 'Wallet not found.'], 404);
        }

        // Return the wallet balance
        return response()->json(['balance' => $user->wallet->balance], 200);
    
    }

    public function fund(Request $request)
    {
       // Validate the request to ensure a valid amount is provided
       $validated = $request->validate([
        'amount' => 'required|numeric|min:1', // Amount must be a positive number
    ]);

    

    // Get the currently authenticated user
    $user = $request->user();
    $wallet = Auth::user()->wallet;
    //dd($wallet);
    
    // check if user has a wallet or it automatically creates a wallet
    if (!$user->wallet) {
        $user->wallet()->create([
            'balance' => 0,
        ]);
    }
    // Get the authenticated user's wallet
    
    // Ensure the user has a wallet
    if (!$user->wallet) {
        return response()->json(['message' => 'Wallet not found.'], 404);
    }

        

    // Add the amount to the wallet balance
    $user->wallet->balance += $validated['amount'];
    $user->wallet->save();

    Transaction::create([
        'wallet_id' => $wallet->id,
        'amount' => $request->amount,
        'type' => 'credit',
        'description' => $request->description ?? 'Wallet funded',
        'user_id' => $user->id,
    ]);


    // Return the updated balance
    return response()->json([
        'message' => 'Wallet funded successfully.',
        'balance' => $user->wallet->balance,
    ], 200);

    }

    public function deduct(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01', // Ensure a positive amount
        ]);
    
        $user = $request->user();
        
        // Get the authenticated user's wallet
        $wallet = Auth::user()->wallet;
        
    
        if (!$user->wallet) {
            return response()->json(['message' => 'Wallet not found.'], 404);
        }

        // Check if the user has sufficient balance
        if ($user->wallet->balance < $validated['amount']) {
            return response()->json(['message' => 'Insufficient balance.'], 400);
        }
    
        // Deduct the amount from the wallet balance
        $user->wallet->decrement('balance', $validated['amount']);

        Transaction::create([
            'wallet_id' => $wallet->id,
            'amount' => $request->amount,
            'type' => 'debit',
            'description' => $request->description ?? 'Wallet deduction',
            'user_id' => $user->id,
        ]);
        
        // Return the updated balance
        return response()->json([
            'message' => 'Amount deducted successfully.',
            'balance' => $user->wallet->balance,
        ], 200);

    }

    public function transactions(Request $request)
    {
    
    $wallet = Auth::user()->wallet;
    $transactions = $wallet->transactions;  // Get all transactions related to this wallet

    return response()->json(['transactions' => $transactions]);
    }
}
