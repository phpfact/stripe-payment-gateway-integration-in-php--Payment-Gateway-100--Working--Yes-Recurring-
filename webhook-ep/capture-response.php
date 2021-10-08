<?php
namespace Phppot;

use Phppot\StripePayment;
use Phppot\StriService;

require_once __DIR__ . "/../lib/StripePayment.php";
require_once __DIR__ . "/../Service/StripeService.php";

$stripeService = new StripeService();

$response = $stripeService->getStripeResponse();

$stripePayment = new StripePayment();

if(!empty($response))
{
    switch($response->type) {
        case "invoice.payment_succeeded":
            $param["id"] = $response->data->object->id;
            $param["invoice_status"] = $response->data->object->status;
            $stripePayment->updateInvoiceStatus($param);
            break;

        case "invoice.payment_failed":
            $param["id"] = $response->data->object->id;
            $param["invoice_status"] = $response->data->object->status;
            $stripePayment->updateInvoiceStatus($param);
            break;

        case "customer.created":
            $param = array();
            $param["customer_id"] = $response->data->object->id;
            $param["customer_email"] = $response->data->object->email;
            $stripePayment->insertCustomer($param);
            break;

        case "customer.subscription.created":
            $param = array();
            $param["id"] = $response->data->object->id;
            $param["customer_id"] = $response->data->object->customer;
            $param["subscription_plan"] = $response->data->object->plan->id;
            $param["subscription_interval"] = $response->data->object->plan->interval_count . " " .$response->data->object->plan->interval;
            $param["subscription_status"] = $response->data->object->status;
            $param["current_period_start"] = date("Y-m-d H:i:s", $response->data->object->current_period_start);
            $param["current_period_end"] = date("Y-m-d H:i:s", $response->data->object->current_period_end);
            $param["subscription_created_date"] = date("Y-m-d H:i:s", $response->data->object->created);
            $stripePayment->insertSubscription($param);
            break;

        case "customer.subscription.updated":
            $param = array();
            $param["id"] = $response->data->object->id;
            $param["subscription_status"] = $response->data->object->status;
            $stripePayment->updateSubscription($param);
            break;

        case "invoice.created":
            $param = array();
            $param["id"] = $response->data->object->id;
            $param["subscription_id"] = $response->data->object->subscription;
            $param["invoice_number"] = $response->data->object->number;
            $param["customer_id"] = $response->data->object->customer;
            $param["billing_email"] = $response->data->object->customer_email;
            $param["currency"] = $response->data->object->currency;
            $param["invoice_status"] = $response->data->object->status;
            $param["invoice_created_date"] = date("Y-m-d H:i:s", $response->data->object->created);

            $i = 0;
            foreach($response->data->object->lines->data as $data)
            {
                $param["invoice_items"][$i]["amount"] = $data->amount;
                $param["invoice_items"][$i]["currency"] = $data->currency;
                $param["invoice_items"][$i]["quantity"] = $data->quantity;
                $param["invoice_items"][$i]["description"] = $data->description;
                $i++;
            }

            $stripePayment->insertInvoice($param);
            break;

        case "invoice.finalized":
            $param["id"] = $response->data->object->id;
            $param["invoice_finalized_date"] = date("Y-m-d H:i:s", $response->data->object->finalized_at);
            $param["invoice_status"] = $response->data->object->status;
            $stripePayment->updateInvoice($param);
            break;
    }
}
?>