<?php
global $link;

if (!isset($current_user['id'])) {
    headerRedirect($link['LOGIN']);
}

website_builder_ensure_tables();

$siteId = !empty($matches['id']) ? (int) $matches['id'] : 0;
$site = $siteId ? website_builder_get_site($siteId, $_SESSION['user']['id']) : website_builder_get_primary_site($_SESSION['user']['id']);

if (empty($site)) {
    transfer($link['YOUR_WEBSITE'], __('Create your first website draft to continue.'), __('Website draft required'));
    exit;
}

$payment_methods = get_option("affiliate_payout_methods", "Paypal, Bank Deposit");
$payment_methods = explode(',', $payment_methods);
if ($payment_methods === false) {
    $payment_methods = [];
}
$payment_methods = array_map('trim', $payment_methods);
$payout_error = '';

if (isset($_POST['update_website_order_status']) && !empty($_POST['order_id'])) {
    website_builder_update_order_status($site['id'], (int) $_POST['order_id'], validate_input($_POST['status']));
    transfer(website_builder_get_dashboard_url($site['id']), __('Order status updated successfully.'), __('Order updated'));
    exit;
}

if (isset($_POST['update_website_booking_status']) && !empty($_POST['booking_id'])) {
    website_builder_update_booking_status($site['id'], (int) $_POST['booking_id'], validate_input($_POST['status']));
    transfer(website_builder_get_dashboard_url($site['id']), __('Booking status updated successfully.'), __('Booking updated'));
    exit;
}

if (isset($_POST['reschedule_website_booking']) && !empty($_POST['booking_id']) && !empty($_POST['booking_start'])) {
    $ownerBookingStart = str_replace('T', ' ', validate_input($_POST['booking_start']));
    if (strlen($ownerBookingStart) === 16) {
        $ownerBookingStart .= ':00';
    }
    list($updatedBooking, $rescheduleError) = website_builder_reschedule_booking($site, (int) $_POST['booking_id'], $ownerBookingStart, 'owner');
    if (!empty($updatedBooking)) {
        website_builder_send_booking_rescheduled_notifications($site, $updatedBooking, 'owner');
        transfer(website_builder_get_dashboard_url($site['id']), __('Booking rescheduled successfully.'), __('Booking updated'));
        exit;
    }
    $payout_error = $rescheduleError;
}

if (isset($_POST['add_website_blackout'])) {
    list($blackoutOk, $blackoutError) = website_builder_add_blackout_period(
        $site['id'],
        validate_input($_POST['blackout_start_date']),
        validate_input($_POST['blackout_end_date']),
        validate_input($_POST['blackout_label'])
    );
    if ($blackoutOk) {
        transfer(website_builder_get_dashboard_url($site['id']), __('Blackout period added successfully.'), __('Calendar updated'));
        exit;
    }
    $payout_error = $blackoutError;
}

if (isset($_POST['remove_website_blackout']) && !empty($_POST['blackout_id'])) {
    website_builder_remove_blackout_period($site['id'], validate_input($_POST['blackout_id']));
    transfer(website_builder_get_dashboard_url($site['id']), __('Blackout period removed successfully.'), __('Calendar updated'));
    exit;
}

if (isset($_POST['request_website_payout'])) {
    list($payoutId, $payoutError) = website_builder_request_payout($site, [
        'amount' => validate_input($_POST['amount']),
        'payment_method' => validate_input($_POST['payment_method']),
        'account_details' => validate_input($_POST['account_details']),
        'notes' => validate_input($_POST['notes']),
    ]);

    if ($payoutId) {
        transfer(website_builder_get_dashboard_url($site['id']), __('Payout request submitted successfully.'), __('Payout requested'));
        exit;
    }
    $payout_error = $payoutError;
}

$orders = website_builder_get_orders($site['id']);
$bookings = website_builder_get_bookings($site['id']);
$upcoming_bookings = website_builder_get_upcoming_bookings($site['id']);
$wallet_summary = website_builder_get_wallet_summary($site['id']);
$payouts = website_builder_get_payouts($site['id']);
$calendar_month = !empty($_GET['month']) ? validate_input($_GET['month']) : date('Y-m');
$booking_calendar = website_builder_build_booking_calendar($site['id'], $calendar_month);
$booking_week = website_builder_build_booking_week($site['id'], !empty($_GET['week']) ? validate_input($_GET['week']) : date('Y-m-d'));
$site = website_builder_get_site($site['id'], $_SESSION['user']['id']);
$blackouts = website_builder_get_site_blackouts($site);

HtmlTemplate::display('website-dashboard', [
    'site' => $site,
    'orders' => $orders,
    'bookings' => $bookings,
    'upcoming_bookings' => $upcoming_bookings,
    'booking_calendar' => $booking_calendar,
    'booking_week' => $booking_week,
    'blackouts' => $blackouts,
    'wallet_summary' => $wallet_summary,
    'payouts' => $payouts,
    'payment_methods' => $payment_methods,
    'payout_error' => $payout_error,
]);
