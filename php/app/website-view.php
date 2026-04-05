<?php
global $config;

$siteId = !empty($matches['id']) ? (int) $matches['id'] : 0;
$slug = !empty($matches['slug']) ? validate_input($matches['slug']) : '';
$site = $siteId > 0 ? website_builder_get_site($siteId) : ($slug !== '' ? website_builder_get_site_by_slug($slug) : null);

if (empty($site)) {
    page_not_found();
}

$pages = website_builder_get_site_pages($site['id']);
$products = website_builder_get_products($site['id']);
$services = website_builder_get_services($site['id']);
$reschedule_booking = null;
$form_success = '';
$form_error = '';

if (isset($_POST['place_website_order'])) {
    list($orderId, $error) = website_builder_create_order_request($site, (int) $_POST['product_id'], [
        'customer_name' => validate_input($_POST['customer_name']),
        'customer_email' => validate_input($_POST['customer_email']),
        'notes' => validate_input($_POST['notes']),
    ]);

    if ($orderId) {
        list($accessToken, $paymentError) = website_builder_prepare_payment_session($site, 'website_order', $orderId);
        if ($accessToken) {
            headerRedirect($link['YOUR_WEBSITE_CHECKOUT'] . '/' . $accessToken);
            exit;
        }
        $form_error = $paymentError ?: __('Unable to start checkout right now.');
    } else {
        $form_error = $error ?: __('Unable to capture the order right now.');
    }
}

if (isset($_POST['place_website_booking'])) {
    list($bookingId, $error) = website_builder_create_booking_request($site, (int) $_POST['service_id'], [
        'customer_name' => validate_input($_POST['customer_name']),
        'customer_email' => validate_input($_POST['customer_email']),
        'booking_start' => !empty($_POST['booking_start']) ? validate_input($_POST['booking_start']) : null,
        'notes' => validate_input($_POST['notes']),
    ]);

    if ($bookingId) {
        list($accessToken, $paymentError) = website_builder_prepare_payment_session($site, 'website_booking', $bookingId);
        if ($accessToken) {
            headerRedirect($link['YOUR_WEBSITE_CHECKOUT'] . '/' . $accessToken);
            exit;
        }
        $form_error = $paymentError ?: __('Unable to start checkout right now.');
    } else {
        $form_error = $error ?: __('Unable to capture the booking right now.');
    }
}

if (!empty($_GET['reschedule']) && !empty($_GET['booking']) && !empty($_GET['token'])) {
    $reschedule_booking = website_builder_get_booking_for_reschedule($site['id'], (int) $_GET['booking'], validate_input($_GET['token']));
    if (empty($reschedule_booking)) {
        $form_error = __('This reschedule link is not valid anymore.');
    }
}

if (isset($_POST['reschedule_website_booking'])) {
    $bookingId = !empty($_POST['booking_id']) ? (int) $_POST['booking_id'] : 0;
    $token = !empty($_POST['reschedule_token']) ? validate_input($_POST['reschedule_token']) : '';
    $newStart = !empty($_POST['booking_start']) ? validate_input($_POST['booking_start']) : '';

    list($updatedBooking, $error) = website_builder_reschedule_booking($site, $bookingId, $newStart, 'customer', $token);
    if (!empty($updatedBooking)) {
        website_builder_send_booking_rescheduled_notifications($site, $updatedBooking, 'customer');
        $form_success = __('Your booking has been rescheduled successfully.');
        $reschedule_booking = $updatedBooking;
    } else {
        $form_error = $error ?: __('Unable to reschedule this booking right now.');
        $reschedule_booking = website_builder_get_booking_for_reschedule($site['id'], $bookingId, $token);
    }
}

if (!empty($_GET['checkout']) && $_GET['checkout'] === 'success') {
    $form_success = __('Payment submitted successfully. We are confirming it with Stripe and updating this website dashboard.');
} elseif (!empty($_GET['checkout']) && $_GET['checkout'] === 'cancel') {
    $form_error = __('Checkout was canceled before payment was completed.');
}

HtmlTemplate::display('website-view', [
    'site' => $site,
    'pages' => $pages,
    'products' => $products,
    'services' => $services,
    'reschedule_booking' => $reschedule_booking,
    'form_success' => $form_success,
    'form_error' => $form_error,
]);
