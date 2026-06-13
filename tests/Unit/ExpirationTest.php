<?php

namespace Tests\Unit;

use App\Console\Commands\ExpirePaymentRequests;
use App\Models\PaymentRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpirationTest extends TestCase
{
    use RefreshDatabase;

    public function test_old_pending_requests_are_expired(): void
    {
        $user = User::factory()->create();

        PaymentRequest::factory()->create([
            'user_id'    => $user->id,
            'status'     => 'pending',
            'created_at' => now()->subHours(72),
        ]);

        PaymentRequest::factory()->create([
            'user_id'    => $user->id,
            'status'     => 'pending',
            'created_at' => now()->subHours(24),
        ]);

        $this->artisan('payments:expire')
            ->assertSuccessful();

        $this->assertEquals(1, PaymentRequest::where('status', 'expired')->count());
        $this->assertEquals(1, PaymentRequest::where('status', 'pending')->count());
    }
}
