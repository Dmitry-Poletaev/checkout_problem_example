<?php
require_once "vendor/autoload.php";
use Checkout\CheckoutApi;
use Checkout\Models\Payments\TokenSource;
use Checkout\Models\Payments\Payment;
use Checkout\Models\Payments\Shipping;
use Checkout\Models\Payments\Risk;
use Checkout\Models\Payments\Metadata;
use Checkout\Models\Payments\ThreeDs;
use Checkout\Models\Payments\BillingDescriptor;
use Checkout\Models\Address;
use Checkout\Models\Customer;
use Checkout\Library\Exceptions\CheckoutModelException;
use Checkout\Library\Exceptions\CheckoutHttpException;
use Checkout\Models\Payments\PaypalSource;

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Checkout Frames v2: Single Frame</title>
    <link rel="stylesheet" href="normalize.css" />
    <link rel="stylesheet" href="style.css" />
  </head>

  <body>
    <form
      id="payment-form"
      method="POST"
      action="https://merchant.com/charge-card"
          >
      <div class="one-liner">
        <div class="card-frame"></div>
        <button id="pay-button" disabled>
PAY GBP 24.99
</button>
      </div>
      <p class="error-message"></p>
      <p class="success-payment-message"></p>
    </form>

    <script src="https://cdn.checkout.com/js/framesv2.min.js"></script>
    <script src="app.js"></script>
  </body>
</html>
<?php
if (isset($_POST['token'])) {

    $checkout = new CheckoutApi('secret key here');
    $method = new TokenSource($_POST['token']);
    $payment = new Payment($method, 'EUR');

    $customer = new Customer();
    $customer->email = "some@gmail.com";

    $customer->name = "John Ivanov";

    $address = new Address();
    $address->address_line1 = "CheckoutSdk.com";
    $address->address_line2 =  "90 Tottenham Court Road";
    $address->city = "London";
    $address->state = "London";
    $address->zip = "W1T 4TJ";
    $address->country = 'GB';

    $metadata = new Metadata();
    $metadata->cart_id = 'some data';

    $payment->customer = $customer;
    $payment->shipping = new Shipping($address);
    $payment->billing_descriptor = new BillingDescriptor('Dynamic desc charge', 'City charge');
    $payment->amount = 2499;
    $payment->reference = '239';
    $payment->capture = true;
    $payment->risk = new Risk(true);
    $payment->threeDs = new ThreeDs(true);

    $payment->setIdempotencyKey('238490e234eru89');

    try {
        $details = $checkout->payments()->request($payment);
        var_dump($details);

    } catch (CheckoutModelException $ex) {
        throw new \Exception(sprintf($ex->getBody()));
    } catch (CheckoutHttpException $ex) {
        throw new \Exception(sprintf($ex->getBody()));
    }

}



?>