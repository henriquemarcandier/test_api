<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use App\Services\ExchangeRateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentRequest::with('user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(15);
        $users = \App\Models\User::orderBy('name')->get();
        return view('payment', compact('payments', 'users'));
    }

    public function store(Request $request, ExchangeRateService $exchangeRateService): JsonResponse
    {
        $validated = $request->validate([
            'user_id'       => 'required|exists:users,id',
            'amount_local'  => 'required|numeric|min:0.01',
            'currency_code' => 'required|string|size:3',
            'description'   => 'nullable|string|max:1000',
        ]);

        $currency = strtoupper($validated['currency_code']);

        try {
            $conversion = $exchangeRateService->convertToEur($validated['amount_local'], $currency);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Exchange rate fetch failed: ' . $e->getMessage()], 422);
        }

        $payment = PaymentRequest::create([
            'user_id'                  => $validated['user_id'],
            'amount_local'             => $validated['amount_local'],
            'currency_code'            => $currency,
            'amount_eur'               => $conversion['amount_eur'],
            'exchange_rate'            => $conversion['exchange_rate'],
            'exchange_rate_source'     => $conversion['source'],
            'exchange_rate_fetched_at' => $conversion['fetched_at'],
            'status'                   => 'pending',
            'expires_at'               => now()->addHours(48),
        ]);

        return response()->json(['success' => true, 'payment' => $payment->load('user')]);
    }

    public function update(Request $request, PaymentRequest $payment): JsonResponse
    {
        if ($request->user()->role !== 'finance' && $request->user()->id !== $payment->user_id) {
            abort(403);
        }

        if ($payment->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Only pending requests can be edited.'], 422);
        }

        $validated = $request->validate([
            'amount_local'  => 'required|numeric|min:0.01',
            'currency_code' => 'required|string|size:3',
            'description'   => 'nullable|string|max:1000',
        ]);

        $payment->update($validated);

        return response()->json(['success' => true, 'payment' => $payment->load('user')]);
    }

    public function destroy(Request $request, PaymentRequest $payment): JsonResponse
    {
        if ($request->user()->role !== 'finance' && $request->user()->id !== $payment->user_id) {
            abort(403);
        }

        if ($payment->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Only pending requests can be deleted.'], 422);
        }

        $payment->delete();
        return response()->json(['success' => true]);
    }
}
