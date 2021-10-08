var createCheckoutSession = function(planId) {
	var plan = {
		    plan_id: planId
		};

		var data = new FormData();
		data.append( "plan", JSON.stringify( plan ) );
  return fetch("ajax-endpoint/create-checkout-session.php", {
    method: "POST",
    body: data
  }).then(function(result) {
	  console.log(result);
    return result.json();
  });
};

// Handle any errors returned from Checkout
var handleResult = function(result) {
  if (result.error) {
    var displayError = document.getElementById("error-message");
    displayError.textContent = result.error.message;
  }
};