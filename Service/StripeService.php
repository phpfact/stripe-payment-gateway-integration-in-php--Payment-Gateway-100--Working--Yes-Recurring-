<?php
namespace Phppot;

use Phppot\Config;
require_once __DIR__ . '/../lib/Config.php';

class StripeService
{
    function __construct()
    {
        require_once __DIR__ . "/../vendor/autoload.php";
        // Set your secret key. Remember to switch to your live secret key in production!
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        \Stripe\Stripe::setApiKey(Config::STRIPE_SECRET_KEY);
    }

    public function createProduct()
    {
        $product = \Stripe\Product::create([
            'name' => Config::PRODUCT_NAME,
            'type' => Config::PRODUCT_TYPE,
        ]);
        return $product;
    }

    public function createPlan()
    {
        $plan = \Stripe\Plan::create([
            'amount' => 2000,
            'currency' => 'usd',
            'interval' => 'month',
            'product' => ['name' => Config::PRODUCT_NAME],
        ]);
        return $plan;
    }

    public function createCheckoutSession($planId)
    {
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'subscription_data' => [
                'items' => [[
                    'plan' => $planId,
                ]],
            ],
            'success_url' => 'https://www.yourdomain.com/stripe-checkout/success.php?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'https://www.yourdomain.com/stripe-checkout/cancel.php',
        ]);
        return $session;
    }

    public function getStripeResponse()
    {
        $body = @file_get_contents('php://input');
        $event_json = json_decode($body);
        return $event_json;
    }
}
?>