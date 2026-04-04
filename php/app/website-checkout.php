<?php
global $link;

$access_token = !empty($matches['token']) ? validate_input($matches['token']) : '';

if ($access_token === '' || empty($_SESSION['quickad'][$access_token])) {
    error_content(__("Checkout session expired"), __("Please return to the website and try again."));
    exit;
}

require_once ROOTPATH . '/includes/payments/stripe/pay.php';
