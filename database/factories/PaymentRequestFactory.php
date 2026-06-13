<?php

namespace Database\Factories;

use App\Models\PaymentRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentRequest>
 */
class PaymentRequestFactory extends Factory
{
    protected $model = PaymentRequest::class;

    public function definition(): array
    {
        return [
            'user_id'                 => User::factory(),
            'amount_local'            => fake()->randomFloat(2, 10, 10000),
            'currency_code'           => fake()->randomElement(['USD', 'EUR', 'GBP', 'JPY', 'BRL']),
            'amount_eur'              => fake()->randomFloat(2, 10, 10000),
            'exchange_rate'           => fake()->randomFloat(6, 0.5, 10),
            'exchange_rate_source'    => 'v6.exchangerate-api.com',
            'exchange_rate_fetched_at'=> now(),
            'status'                  => 'pending',
            'expires_at'              => now()->addHours(48),
        ];
    }
}
