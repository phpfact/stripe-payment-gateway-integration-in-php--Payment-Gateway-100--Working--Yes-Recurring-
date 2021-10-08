<?php
namespace Phppot;

use Phppot\Service;

require_once __DIR__ . '/Service/StripeService.php';
$stripeService = new StripeService();

$product = $stripeService->createProduct();
$planId = $stripeService->createPlan($product->id);

if(!empty($planId))
{
    echo "Product pricing plan is created and the plan id id is: ";
    print_r($planId);
}
?>