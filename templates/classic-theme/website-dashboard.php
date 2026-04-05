<?php
overall_header(__("Website Dashboard"));
$websiteEditorUrl = $config['site_url'] . 'your-website/editor/' . $site['id'];
$websitePublicUrl = $config['site_url'] . 'site/' . $site['slug'];
$websiteDashboardBaseUrl = $config['site_url'] . 'your-website/dashboard/' . $site['id'];
?>
<div class="dashboard-container">
    <?php include_once TEMPLATE_PATH . '/dashboard_sidebar.php'; ?>
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner atlas-workflow-shell">
            <div class="dashboard-headline">
                <h3><?php _e("Website Dashboard") ?></h3>
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><a href="<?php url("YOUR_WEBSITE") ?>"><?php _e("Your Website") ?></a></li>
                        <li><?php _esc($site['site_name']) ?></li>
                    </ul>
                </nav>
            </div>

            <div class="atlas-workflow-hero margin-bottom-30">
                <span class="atlas-workflow-eyebrow"><?php _e("Operations") ?></span>
                <h2><?php _esc($site['site_name']) ?></h2>
                <p><?php _e("Track website-originated orders, bookings, and earnings as Atlas starts powering the transactional layer for this site."); ?></p>
                <div class="atlas-workflow-actions margin-top-20">
                    <a href="<?php echo $websiteEditorUrl; ?>" class="button"><?php _e("Open editor") ?></a>
                    <a href="<?php echo $websitePublicUrl; ?>" target="_blank" class="button"><?php _e("Open public site") ?></a>
                </div>
            </div>

            <div class="row margin-bottom-24">
                <div class="col-md-4 margin-bottom-20">
                    <div class="dashboard-box">
                        <div class="content with-padding">
                            <span class="atlas-dashboard-label"><?php _e("Pending earnings") ?></span>
                            <h3 class="margin-top-10 margin-bottom-0"><?php _esc(price_format($wallet_summary['pending'])) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 margin-bottom-20">
                    <div class="dashboard-box">
                        <div class="content with-padding">
                            <span class="atlas-dashboard-label"><?php _e("Available to withdraw") ?></span>
                            <h3 class="margin-top-10 margin-bottom-0"><?php _esc(price_format($wallet_summary['available'])) ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row margin-bottom-24">
                <div class="col-md-4 margin-bottom-20">
                    <div class="dashboard-box">
                        <div class="content with-padding">
                            <span class="atlas-dashboard-label"><?php _e("Posted earnings") ?></span>
                            <h3 class="margin-top-10 margin-bottom-0"><?php _esc(price_format($wallet_summary['posted'])) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 margin-bottom-20">
                    <div class="dashboard-box">
                        <div class="content with-padding">
                            <span class="atlas-dashboard-label"><?php _e("Requested payouts") ?></span>
                            <h3 class="margin-top-10 margin-bottom-0"><?php _esc(price_format($wallet_summary['requested'])) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 margin-bottom-20">
                    <div class="dashboard-box">
                        <div class="content with-padding">
                            <span class="atlas-dashboard-label"><?php _e("Captured requests") ?></span>
                            <h3 class="margin-top-10 margin-bottom-0"><?php _esc($wallet_summary['count']) ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 margin-bottom-30">
                    <div class="dashboard-box">
                        <div class="headline">
                            <h3><i class="icon-feather-clock"></i> <?php _e("Upcoming booking calendar") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <?php if (!empty($upcoming_bookings)) { ?>
                                <div class="atlas-booking-calendar-list">
                                    <?php foreach ($upcoming_bookings as $booking) { ?>
                                        <div class="atlas-booking-calendar-item">
                                            <div class="atlas-booking-calendar-date">
                                                <strong><?php _esc(date('d', strtotime($booking['booking_start']))) ?></strong>
                                                <span><?php _esc(date('M', strtotime($booking['booking_start']))) ?></span>
                                            </div>
                                            <div class="atlas-booking-calendar-copy">
                                                <strong><?php _esc(!empty($booking['metadata']['service_title']) ? $booking['metadata']['service_title'] : __('Website booking')) ?></strong>
                                                <span><?php _esc($booking['customer_name']) ?> · <?php _esc(date('h:i A', strtotime($booking['booking_start']))) ?></span>
                                            </div>
                                            <div class="atlas-booking-calendar-status"><?php _esc(ucfirst($booking['status'])) ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } else { ?>
                                <p class="margin-bottom-0"><?php _e("No upcoming bookings are scheduled yet."); ?></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 margin-bottom-30">
                    <div class="dashboard-box">
                        <div class="headline">
                            <h3><i class="icon-feather-calendar"></i> <?php _e("Month view") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <div class="atlas-booking-month-toolbar margin-bottom-15">
                                <a href="<?php echo $websiteDashboardBaseUrl . '?month=' . $booking_calendar['prev_month']; ?>" class="button gray"><?php _e("Previous") ?></a>
                                <strong><?php _esc($booking_calendar['label']) ?></strong>
                                <a href="<?php echo $websiteDashboardBaseUrl . '?month=' . $booking_calendar['next_month']; ?>" class="button gray"><?php _e("Next") ?></a>
                            </div>
                            <div class="atlas-booking-month-grid atlas-booking-month-weekdays">
                                <span><?php _e("Mon") ?></span>
                                <span><?php _e("Tue") ?></span>
                                <span><?php _e("Wed") ?></span>
                                <span><?php _e("Thu") ?></span>
                                <span><?php _e("Fri") ?></span>
                                <span><?php _e("Sat") ?></span>
                                <span><?php _e("Sun") ?></span>
                            </div>
                            <div class="atlas-booking-month-grid">
                                <?php foreach ($booking_calendar['days'] as $day) { ?>
                                    <div class="atlas-booking-month-day <?php echo !$day['in_month'] ? 'atlas-booking-month-day-muted' : ''; ?> <?php echo !empty($day['blackout']) ? 'atlas-booking-month-day-blackout' : ''; ?>">
                                        <div class="atlas-booking-month-day-top">
                                            <strong><?php _esc($day['day']) ?></strong>
                                            <?php if (!empty($day['booking_count'])) { ?>
                                                <span><?php echo (int) $day['booking_count']; ?></span>
                                            <?php } ?>
                                        </div>
                                        <?php if (!empty($day['blackout'])) { ?>
                                            <small><?php _esc(!empty($day['blackout']['label']) ? $day['blackout']['label'] : __('Blocked')); ?></small>
                                        <?php } elseif (!empty($day['bookings'][0])) { ?>
                                            <small><?php _esc(!empty($day['bookings'][0]['metadata']['service_title']) ? $day['bookings'][0]['metadata']['service_title'] : __('Booking')); ?></small>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 margin-bottom-30">
                    <div class="dashboard-box">
                        <div class="headline">
                            <h3><i class="icon-feather-list"></i> <?php _e("This week") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <div class="atlas-booking-week-list">
                                <?php foreach ($booking_week as $day) { ?>
                                    <div class="atlas-booking-week-day <?php echo !empty($day['blackout']) ? 'atlas-booking-week-day-blackout' : ''; ?>">
                                        <div class="atlas-booking-week-day-head">
                                            <strong><?php _esc($day['label']) ?></strong>
                                            <?php if (!empty($day['blackout'])) { ?>
                                                <span><?php _e("Blocked") ?></span>
                                            <?php } else { ?>
                                                <span><?php echo count($day['bookings']); ?> <?php _e("bookings") ?></span>
                                            <?php } ?>
                                        </div>
                                        <?php if (!empty($day['bookings'])) { ?>
                                            <?php foreach ($day['bookings'] as $weekBooking) { ?>
                                                <div class="atlas-booking-week-item">
                                                    <strong><?php _esc(date('h:i A', strtotime($weekBooking['booking_start']))) ?></strong>
                                                    <span><?php _esc($weekBooking['customer_name']) ?> · <?php _esc(!empty($weekBooking['metadata']['service_title']) ? $weekBooking['metadata']['service_title'] : __('Booking')); ?></span>
                                                </div>
                                            <?php } ?>
                                        <?php } elseif (empty($day['blackout'])) { ?>
                                            <small><?php _e("No bookings scheduled."); ?></small>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 margin-bottom-30">
                    <div class="dashboard-box">
                        <div class="headline">
                            <h3><i class="icon-feather-shopping-bag"></i> <?php _e("Orders") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <?php if (!empty($orders)) { ?>
                                <div class="dashboard-table-container">
                                    <table class="basic-table">
                                        <thead>
                                        <tr>
                                            <th><?php _e("Customer") ?></th>
                                            <th><?php _e("Product") ?></th>
                                            <th><?php _e("Amount") ?></th>
                                            <th><?php _e("Status") ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($orders as $order) { ?>
                                            <tr>
                                                <td>
                                                    <strong><?php _esc($order['customer_name']) ?></strong><br>
                                                    <small><?php _esc($order['customer_email']) ?></small>
                                                </td>
                                                <td><?php _esc(!empty($order['metadata']['product_title']) ? $order['metadata']['product_title'] : __('Website order')) ?></td>
                                                <td><?php _esc(price_format($order['amount'])) ?></td>
                                                <td>
                                                    <form method="post" class="atlas-website-status-form">
                                                        <input type="hidden" name="order_id" value="<?php _esc($order['id']) ?>">
                                                        <select name="status" class="with-border small-input">
                                                            <?php foreach (['pending', 'paid', 'processing', 'fulfilled', 'canceled'] as $status) { ?>
                                                                <option value="<?php _esc($status) ?>" <?php echo $order['status'] === $status ? 'selected' : ''; ?>><?php _esc(ucfirst($status)) ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <button type="submit" name="update_website_order_status" class="button gray"><?php _e("Update") ?></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } else { ?>
                                <p class="margin-bottom-0"><?php _e("No website orders yet."); ?></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 margin-bottom-30">
                    <div class="dashboard-box">
                        <div class="headline">
                            <h3><i class="icon-feather-calendar"></i> <?php _e("Bookings") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <?php if (!empty($bookings)) { ?>
                                <div class="dashboard-table-container">
                                    <table class="basic-table">
                                        <thead>
                                        <tr>
                                            <th><?php _e("Customer") ?></th>
                                            <th><?php _e("Service") ?></th>
                                            <th><?php _e("Requested") ?></th>
                                            <th><?php _e("Status") ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($bookings as $booking) { ?>
                                            <tr>
                                                <td>
                                                    <strong><?php _esc($booking['customer_name']) ?></strong><br>
                                                    <small><?php _esc($booking['customer_email']) ?></small>
                                                </td>
                                                <td><?php _esc(!empty($booking['metadata']['service_title']) ? $booking['metadata']['service_title'] : __('Website booking')) ?></td>
                                                <td><?php _esc(!empty($booking['booking_start']) ? date('d M Y h:i A', strtotime($booking['booking_start'])) : __('Pending')) ?></td>
                                                <td>
                                                    <form method="post" class="atlas-website-status-form">
                                                        <input type="hidden" name="booking_id" value="<?php _esc($booking['id']) ?>">
                                                        <select name="status" class="with-border small-input">
                                                            <?php foreach (['pending', 'paid', 'confirmed', 'completed', 'canceled'] as $status) { ?>
                                                                <option value="<?php _esc($status) ?>" <?php echo $booking['status'] === $status ? 'selected' : ''; ?>><?php _esc(ucfirst($status)) ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <button type="submit" name="update_website_booking_status" class="button gray"><?php _e("Update") ?></button>
                                                    </form>
                                                    <form method="post" class="atlas-website-reschedule-form margin-top-10">
                                                        <input type="hidden" name="booking_id" value="<?php _esc($booking['id']) ?>">
                                                        <input type="datetime-local" class="with-border small-input" name="booking_start" value="<?php echo !empty($booking['booking_start']) ? date('Y-m-d\TH:i', strtotime($booking['booking_start'])) : ''; ?>">
                                                        <button type="submit" name="reschedule_website_booking" class="button gray"><?php _e("Reschedule") ?></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } else { ?>
                                <p class="margin-bottom-0"><?php _e("No website bookings yet."); ?></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-5 margin-bottom-30">
                    <div class="dashboard-box">
                        <div class="headline">
                            <h3><i class="icon-feather-moon"></i> <?php _e("Blackout periods") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <form method="post" class="atlas-website-editor-form margin-bottom-20">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="submit-field">
                                            <h5><?php _e("Start date") ?></h5>
                                            <input type="date" class="with-border" name="blackout_start_date" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="submit-field">
                                            <h5><?php _e("End date") ?></h5>
                                            <input type="date" class="with-border" name="blackout_end_date" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="submit-field">
                                            <h5><?php _e("Label") ?></h5>
                                            <input type="text" class="with-border" name="blackout_label" placeholder="<?php _e("Vacation"); ?>">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" name="add_website_blackout" class="button ripple-effect"><?php _e("Add blackout") ?></button>
                            </form>
                            <?php if (!empty($blackouts)) { ?>
                                <div class="atlas-booking-blackout-list">
                                    <?php foreach ($blackouts as $blackout) { ?>
                                        <div class="atlas-booking-blackout-item">
                                            <div>
                                                <strong><?php _esc($blackout['start_date']) ?><?php echo $blackout['end_date'] !== $blackout['start_date'] ? ' → ' . escape($blackout['end_date']) : ''; ?></strong>
                                                <span><?php _esc(!empty($blackout['label']) ? $blackout['label'] : __('Blocked')); ?></span>
                                            </div>
                                            <form method="post">
                                                <input type="hidden" name="blackout_id" value="<?php _esc($blackout['id']) ?>">
                                                <button type="submit" name="remove_website_blackout" class="button gray"><?php _e("Remove") ?></button>
                                            </form>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } else { ?>
                                <p class="margin-bottom-0"><?php _e("No blackout periods set yet."); ?></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 margin-bottom-30">
                    <div class="dashboard-box">
                        <div class="headline">
                            <h3><i class="icon-feather-download-cloud"></i> <?php _e("Request payout") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <?php if (!empty($payout_error)) { ?>
                                <div class="notification error margin-bottom-15"><?php _esc($payout_error) ?></div>
                            <?php } ?>
                            <div class="notification notice margin-bottom-20">
                                <?php _e('Website payouts draw only from posted website earnings. Pending or already-requested amounts stay blocked until they are approved or rejected by the administrator.') ?>
                            </div>
                            <form method="post" class="atlas-website-editor-form">
                                <div class="submit-field">
                                    <h5><?php _e("Amount") ?></h5>
                                    <input type="number" step="0.01" class="with-border" name="amount" value="<?php _esc($wallet_summary['available']) ?>" min="0">
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("Payment Method") ?></h5>
                                    <select name="payment_method" class="with-border">
                                        <?php foreach ($payment_methods as $method) { ?>
                                            <option value="<?php _esc($method) ?>"><?php _esc($method) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("Account Details") ?></h5>
                                    <textarea name="account_details" class="with-border" rows="4" required></textarea>
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("Notes") ?></h5>
                                    <textarea name="notes" class="with-border" rows="3"></textarea>
                                </div>
                                <button type="submit" name="request_website_payout" class="button ripple-effect"><?php _e("Submit payout request") ?></button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 margin-bottom-30">
                    <div class="dashboard-box">
                        <div class="headline">
                            <h3><i class="icon-feather-credit-card"></i> <?php _e("Payout requests") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <?php if (!empty($payouts)) { ?>
                                <div class="dashboard-table-container">
                                    <table class="basic-table">
                                        <thead>
                                        <tr>
                                            <th><?php _e("Requested on") ?></th>
                                            <th><?php _e("Method") ?></th>
                                            <th><?php _e("Amount") ?></th>
                                            <th><?php _e("Status") ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($payouts as $payout) { ?>
                                            <tr>
                                                <td><?php _esc(date('d M Y h:i A', strtotime($payout['created_at']))) ?></td>
                                                <td>
                                                    <?php _esc($payout['payment_method']) ?>
                                                    <?php if (!empty($payout['account_details'])) { ?>
                                                        <i class="fa fa-info-circle" title="<?php _esc(nl2br(escape($payout['account_details']))) ?>" data-tippy-placement="top"></i>
                                                    <?php } ?>
                                                </td>
                                                <td><?php _esc(price_format($payout['amount'])) ?></td>
                                                <td><?php _esc(ucfirst($payout['status'])) ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } else { ?>
                                <p class="margin-bottom-0"><?php _e("No payout requests yet."); ?></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ob_start() ?>
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH . '/overall_footer_dashboard.php';
