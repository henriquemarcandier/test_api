<?php

namespace App\Models;

use Database\Factories\PaymentRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentRequest extends Model
{
    /** @use HasFactory<PaymentRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount_local',
        'currency_code',
        'amount_eur',
        'exchange_rate',
        'exchange_rate_source',
        'exchange_rate_fetched_at',
        'status',
        'expires_at',
        'approved_by',
        'approved_at',
        'reason',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
