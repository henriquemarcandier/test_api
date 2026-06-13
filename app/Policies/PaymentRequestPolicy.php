<?php

namespace App\Policies;

use App\Models\PaymentRequest;
use App\Models\User;

class PaymentRequestPolicy
{
    public function approve(User $user, PaymentRequest $paymentRequest): bool
    {
        return $user->isFinance();
    }

    public function reject(User $user, PaymentRequest $paymentRequest): bool
    {
        return $user->isFinance();
    }
}
