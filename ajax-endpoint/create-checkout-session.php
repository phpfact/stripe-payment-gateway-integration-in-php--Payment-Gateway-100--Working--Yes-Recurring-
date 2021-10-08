<?php
namespace Phppot;



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



use Phppot\StripeService;

require_once __DIR__ . '/../Service/StripeService.php';
$stripeService = new StripeService();

$plan = json_decode($_POST["plan"]);
$planId = $plan->plan_id;

$session  = $stripeService->createCheckoutSession($planId);

echo json_encode($session);
?>