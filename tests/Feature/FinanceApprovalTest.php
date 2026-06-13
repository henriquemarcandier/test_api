<?php

namespace Tests\Feature;

use App\Models\PaymentRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceApprovalTest extends TestCase
{
    use RefreshDatabase;

    private User $employee;
    private User $finance;

    protected function setUp(): void
    {
        parent::setUp();

        $this->employee = User::factory()->create(['role' => 'employee']);
        $this->finance  = User::factory()->create(['role' => 'finance']);
    }

    public function test_finance_can_approve(): void
    {
        $paymentRequest = PaymentRequest::factory()->create([
            'user_id' => $this->employee->id,
            'status'  => 'pending',
        ]);

        $token = $this->finance->createToken('auth-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson("/api/payment-requests/{$paymentRequest->id}/approve");

        $response->assertOk()
            ->assertJsonPath('status', 'approved');
    }

    public function test_finance_can_reject(): void
    {
        $paymentRequest = PaymentRequest::factory()->create([
            'user_id' => $this->employee->id,
            'status'  => 'pending',
        ]);

        $token = $this->finance->createToken('auth-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson("/api/payment-requests/{$paymentRequest->id}/reject", [
            'reason' => 'Invalid invoice',
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'rejected');
    }

    public function test_employee_cannot_approve(): void
    {
        $paymentRequest = PaymentRequest::factory()->create([
            'user_id' => $this->employee->id,
            'status'  => 'pending',
        ]);

        $token = $this->employee->createToken('auth-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->patchJson("/api/payment-requests/{$paymentRequest->id}/approve");

        $response->assertStatus(403);
    }
}
