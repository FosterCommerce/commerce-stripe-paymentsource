<?php

namespace fostercommerce\commercestripepaymentsource\variables;

use fostercommerce\commercestripepaymentsource\Plugin;
use Craft;
use craft\commerce\Plugin as Commerce;
use craft\commerce\stripe\Plugin as CommerceStripe;
use craft\commerce\stripe\gateways\Gateway;
use Stripe\Customer as StripeCustomer;
use Stripe\Source as PaymentSource;
use Stripe\Stripe;

class Variable
{
    public function paymentSource($gateway, $user = null)
    {
        if ($user === null) {
            $user = Craft::$app->getUser()->getIdentity();
        }

        if (!is_numeric($gateway)) {
            $gateway = Commerce::getInstance()->gateways->getGatewayByHandle($gateway)->id;
        }

        $customer = CommerceStripe::getInstance()->customers->getCustomer($gateway, $user);

        Stripe::setApiKey(Commerce::getInstance()->gateways->getGatewayById($gateway)->apiKey);
        Stripe::setAppInfo(CommerceStripe::getInstance()->name, CommerceStripe::getInstance()->version, CommerceStripe::getInstance()->documentationUrl);
        Stripe::setApiVersion(Gateway::STRIPE_API_VERSION);

        $stripeCustomer = StripeCustomer::retrieve($customer->reference);

        if ($stripeCustomer->default_source !== null) {
            $paymentSource = PaymentSource::retrieve($stripeCustomer->default_source);
            return $paymentSource;
        }

        return null;
    }
}
