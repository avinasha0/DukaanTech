<?php

namespace Tests\Unit;

use App\Models\Account;
use Illuminate\Support\Facades\Crypt;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RazorpayAccountSettingsTest extends TestCase
{
    #[Test]
    public function razorpay_qr_payment_not_ready_without_all_credentials(): void
    {
        $onlyFlag = new Account([
            'settings' => [
                'payment_gateway_enabled' => true,
            ],
        ]);
        $this->assertFalse($onlyFlag->razorpayQrPaymentReady());

        $withKeyId = new Account([
            'settings' => [
                'payment_gateway_enabled' => true,
                'razorpay_key_id' => 'rzp_test_abc',
            ],
        ]);
        $this->assertFalse($withKeyId->razorpayQrPaymentReady());
    }

    #[Test]
    public function razorpay_qr_payment_ready_when_enabled_and_keys_present(): void
    {
        $account = new Account([
            'settings' => [
                'payment_gateway_enabled' => true,
                'razorpay_key_id' => 'rzp_test_abc',
                'razorpay_key_secret_encrypted' => Crypt::encryptString('secret_value'),
            ],
        ]);

        $this->assertTrue($account->razorpayQrPaymentReady());
        $this->assertSame('rzp_test_abc', $account->razorpayKeyId());
        $this->assertSame('secret_value', $account->razorpayKeySecret());
    }
}
