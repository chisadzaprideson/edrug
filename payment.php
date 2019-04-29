<?php
require_once('Paynow\autoloader.php');

# $paynow->setResultUrl('');
# $paynow->setReturnUrl('');
$paynow = new Paynow\Payments\Paynow(
    '7387',
    '463a1a38-4803-4e9a-8b8c-7e4168e8305f',
    'http://example.com/gateways/paynow/update',

    // The return url can be set at later stages. You might want to do this if you want to pass data to the return url (like the reference of the transaction)
    'http://example.com/return?gateway=paynow'
);



$payment = $paynow->createPayment('Invoice 35', 'melmups@outlook.com');

$payment->add('Sadza and Beans', 1.25);

$response = $paynow->send($payment);


if($response->success()) {
    // Redirect the user to Paynow
    $response->redirect();

    // Or if you prefer more control, get the link to redirect the user to, then use it as you see fit
    $link = $response->redirectLink();

    $pollUrl = $response->pollUrl();


    // Check the status of the transaction
   // $status = $paynow->pollTransaction($pollUrl);

}

?>