<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveRejectPaymentRequestRequest;
use App\Http\Requests\StorePaymentRequestRequest;
use App\Http\Resources\PaymentRequestResource;
use App\Models\PaymentRequest;
use App\Services\ExchangeRateService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class PaymentRequestController extends Controller
{
    public function __construct(
        protected ExchangeRateService $exchangeRateService
    ) {}

    public function store(StorePaymentRequestRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $currency  = strtoupper($validated['currency_code']);

        $conversion = $this->exchangeRateService->convertToEur(
            $validated['amount_local'],
            $currency
        );

        $paymentRequest = DB::transaction(function () use ($request, $validated, $currency, $conversion) {
            return PaymentRequest::create([
                'user_id'                 => $request->user()->id,
                'amount_local'            => $validated['amount_local'],
                'currency_code'           => $currency,
                'amount_eur'              => $conversion['amount_eur'],
                'exchange_rate'           => $conversion['exchange_rate'],
                'exchange_rate_source'    => $conversion['source'],
                'exchange_rate_fetched_at'=> $conversion['fetched_at'],
                'status'                  => 'pending',
                'expires_at'              => now()->addHours(48),
            ]);
        });

        return response()->json(
            new PaymentRequestResource($paymentRequest),
            201
        );
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $query = PaymentRequest::with('user');

        if (!$request->user()->isFinance()) {
            $query->where('user_id', $request->user()->id);
        } elseif ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return PaymentRequestResource::collection(
            $query->latest()->paginate(15)
        );
    }

    public function show(Request $request, PaymentRequest $paymentRequest): JsonResponse
    {
        if (!$request->user()->isFinance() && $paymentRequest->user_id !== $request->user()->id) {
            throw new AuthorizationException();
        }

        return response()->json(new PaymentRequestResource($paymentRequest));
    }

    public function approve(ApproveRejectPaymentRequestRequest $request, PaymentRequest $paymentRequest): JsonResponse
    {
        if ($request->isMethod('get')) {
            return response()->json(new PaymentRequestResource($paymentRequest));
        }

        if ($paymentRequest->status !== 'pending') {
            return response()->json([
                'message' => 'Only pending requests can be approved or rejected.',
            ], 422);
        }

        $paymentRequest->update([
            'status'      => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'reason'      => $request->input('reason'),
        ]);

        return response()->json(new PaymentRequestResource($paymentRequest));
    }

    public function reject(ApproveRejectPaymentRequestRequest $request, PaymentRequest $paymentRequest): JsonResponse
    {
        if ($request->isMethod('get')) {
            return response()->json(new PaymentRequestResource($paymentRequest));
        }

        if ($paymentRequest->status !== 'pending') {
            return response()->json([
                'message' => 'Only pending requests can be approved or rejected.',
            ], 422);
        }

        $paymentRequest->update([
            'status'      => 'rejected',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'reason'      => $request->input('reason'),
        ]);

        return response()->json(new PaymentRequestResource($paymentRequest));
    }
}
