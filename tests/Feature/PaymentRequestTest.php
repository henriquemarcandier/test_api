<?php

namespace Tests\Feature;

use App\Models\PaymentRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentRequestTest extends TestCase
{
    use RefreshDatabase;

    private User $employee;
    private User $finance;

    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();

        Http::fake([
            'v6.exchangerate-api.com/v6/*' => Http::response([
                'result'           => 'success',
                'base_code'        => 'EUR',
                'conversion_rates' => [
                    'USD' => 1.08,
                    'EUR' => 1,
                    'BRL' => 6.25,
                    'GBP' => 0.86,
                    'JPY' => 162.50,
                    'MXN' => 18.20,
                ],
                'time_last_update_utc' => '2024-01-01 00:00:00',
            ]),
        ]);

        $this->employee = User::factory()->create(['role' => 'employee', 'currency' => 'USD']);
        $this->finance  = User::factory()->create(['role' => 'finance', 'currency' => 'EUR']);
    }

    public function test_can_create_payment_request(): void
    {
        $token = $this->employee->createToken('auth-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/payment-requests', [
            'amount_local'  => 5000,
            'currency_code' => 'BRL',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id', 'amount_local', 'currency_code',
                'amount_eur', 'exchange_rate', 'status',
            ]);
    }

    public function test_can_list_requests(): void
    {
        PaymentRequest::factory()->count(3)->create([
            'user_id' => $this->employee->id,
        ]);

        $token = $this->employee->createToken('auth-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/payment-requests');

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_can_view_request(): void
    {
        $paymentRequest = PaymentRequest::factory()->create([
            'user_id' => $this->employee->id,
        ]);

        $token = $this->employee->createToken('auth-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson("/api/payment-requests/{$paymentRequest->id}");

        $response->assertOk()
            ->assertJsonPath('id', $paymentRequest->id);
    }

    public function test_employee_sees_only_own_requests(): void
    {
        $otherUser = User::factory()->create();
        PaymentRequest::factory()->create(['user_id' => $otherUser->id]);

        $token = $this->employee->createToken('auth-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/payment-requests');

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }
}
