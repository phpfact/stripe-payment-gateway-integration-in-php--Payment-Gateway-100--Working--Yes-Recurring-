<?php
use Phppot\Config;

require_once __DIR__ . "/lib/Config.php";
?>
<html>
<head>
<title>Payment Response</title>
<link href="./assets/css/style.css" type="text/css" rel="stylesheet" />
</head>
<body>
    <div class="subscription-response">
    <h1>Thank you for subscribing with us.</h1>
    <p>You have subscribed for our "<?php echo Config::PRODUCT_NAME; ?>" service's monthly plan.</p>
    <p>You have been notified about the status of your subscription shortly.</p>
    </div>
</body>
</html>