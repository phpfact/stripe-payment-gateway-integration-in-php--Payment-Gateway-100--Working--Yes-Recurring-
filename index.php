<?php
use Phppot\Config;

require_once __DIR__ . "/lib/Config.php";
?>
<html>
<head>
<title>Checkout</title>
<link href="./assets/css/style.css" type="text/css" rel="stylesheet" />
<script src="https://js.stripe.com/v3/"></script>
<script src="./assets/js/stripe.js"></script>
<!-- <script src="https://js.stripe.com/v3/"></script> -->
</head>
<body>
    <div class="product-plan-tile">
    <h2><?php echo Config::PRODUCT_NAME; ?></h2>
    <p>Best reference suitable for beginners to experts.</p>
    <div class="plan-pricing">$20 / month</div>
    <input type="button" id="subscribe" value="Subscribe Now" />
    </div>
    <div id="error-message"></div>
<script>
var stripe = Stripe('<?php echo Config::STRIPE_PUBLISHIABLE_KEY; ?>');
//Setup event handler to create a Checkout Session when button is clicked
document.getElementById("subscribe").addEventListener("click", function(evt) {
    createCheckoutSession('<?php echo Config::SUBSCRIPTION_PLAN_ID; ?>').then(function(data) {
      // Call Stripe.js method to redirect to the new Checkout page
      stripe.redirectToCheckout({
          sessionId: data.id
      }).then(handleResult);
    });
  });
</script>
</body>
</html>