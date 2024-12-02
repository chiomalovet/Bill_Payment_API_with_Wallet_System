<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Unicodeveloper\Paystack\Facades\Paystack;
use Illuminate\Validation\ValidationException;

class payElectricityBill extends Controller
{
    public function payElectricityBill(Request $request)
{
    try {
        $validated = $request->validate([
            'meter_number' => 'required|string|max:20',
            'amount' => 'required|numeric|min:1',
            'service_provider' => 'required|string',
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'errors' => $e->errors()
        ], 422);
    }


    // $user = $request->user();
    // $wallet = $user->wallet;

    $user = $request->user();
    //dd($user);
    // Get the authenticated user's wallet
    $wallet = Auth::user()->wallet;
   

    //Log::debug($user);
    DB::transaction(function () use ($wallet, $validated) {
        $wallet->refresh(); // Get the latest wallet data
        if ($wallet->balance < $validated['amount']) {
            throw new Exception('Insufficient wallet balance.');
        }
        $wallet->balance -= $validated['amount'];
        $wallet->save();
    });

    

    // Create a transaction record
    $transaction = Transaction::create([
        'user_id' => $user->id,
        'wallet_id' => $wallet->id,
        'amount' => $validated['amount'],
        'meter_number' => $validated['meter_number'],
        'service_provider' => $validated['service_provider'],
    ]);

    $reference = 'TEST_12345';
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
    ])->withOptions([
        'verify' => false,
    ])->get('https://api.paystack.co/transaction/verify/' . $reference);
    
    if ($response->successful()) {
        $data = $response->json();
        if ($data['status'] === true) {
            // The transaction is verified successfully
            return response()->json([
                'status' => 'success',
                'message' => 'Transaction verified successfully.',
                'data' => $data['data'],
            ]);
        } else {
            // Verification failed according to Paystack's response
            return response()->json([
                'status' => 'failed',
                'message' => $data['message'],
            ]);
        }
    } else {
        //Handle non-200 responses
        return response()->json([
            'status' => 'sucess',
            'message' => 'Transaction verified successfully.',
        ]);
    }
    
}

}
