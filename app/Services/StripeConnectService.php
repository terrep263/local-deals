<?php

namespace App\Services;

use App\Models\VendorProfile;
use Stripe\StripeClient;

class StripeConnectService
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function generateConnectUrl(VendorProfile $vendor): string
    {
        $params = [
            'client_id' => config('services.stripe.connect_client_id'),
            'state' => encrypt(['vendor_id' => $vendor->id]),
            'response_type' => 'code',
            'redirect_uri' => route('vendor.onboarding.stripe.callback'),
            'scope' => 'read_write',
        ];

        return 'https://connect.stripe.com/oauth/authorize?' . http_build_query($params);
    }

    public function handleCallback(string $code): array
    {
        $response = $this->stripe->oauth->token([
            'grant_type' => 'authorization_code',
            'code' => $code,
        ]);

        return [
            'stripe_user_id' => $response->stripe_user_id,
            'access_token' => $response->access_token,
        ];
    }
}

