<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'amount_local'    => (float) $this->amount_local,
            'currency_code'   => $this->currency_code,
            'amount_eur'      => (float) $this->amount_eur,
            'exchange_rate'   => (float) $this->exchange_rate,
            'status'          => $this->status,
            'reason'          => $this->reason,
            'expires_at'      => $this->expires_at,
            'created_at'      => $this->created_at,
            'user'            => $this->when($request->user()?->isFinance(), [
                'id'   => $this->user?->id,
                'name' => $this->user?->name,
            ]),
            'approved_by'     => $this->when($this->approved_by, $this->approver?->name),
            'approved_at'     => $this->approved_at,
        ];
    }
}
