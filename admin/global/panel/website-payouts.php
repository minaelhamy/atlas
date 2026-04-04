<?php
if (empty($_GET['id'])) {
    _e('Unexpected error, please try again.');
    die();
}

require_once '../../includes.php';

$payout = ORM::for_table($config['db']['pre'] . 'website_payouts')->find_one(validate_input($_GET['id']));
if (!$payout) {
    _e('Payout request not found.');
    die();
}

$site = ORM::for_table($config['db']['pre'] . 'website_sites')->find_one((int) $payout['site_id']);
$owner = ORM::for_table($config['db']['pre'] . 'user')->find_one((int) $payout['user_id']);
?>

<div class="slidePanel-content">
    <header class="slidePanel-header">
        <div class="slidePanel-overlay-panel">
            <div class="slidePanel-heading">
                <h2><?php _e('Website payout'); ?></h2>
            </div>
            <div class="slidePanel-actions">
                <button id="post_sidePanel_data" class="btn-icon btn-primary" title="<?php _e('Save') ?>">
                    <i class="icon-feather-check"></i>
                </button>
                <button class="btn-icon slidePanel-close" title="<?php _e('Close') ?>">
                    <i class="icon-feather-x"></i>
                </button>
            </div>
        </div>
    </header>

    <div class="slidePanel-inner">
        <div id="post_error"></div>
        <form name="form2" class="form form-horizontal" method="post" id="sidePanel_form" data-ajax-action="editWebsitePayout">
            <div class="form-body">
                <input type="hidden" name="id" value="<?php _esc($_GET['id']) ?>">
                <div class="form-group">
                    <label><?php _e('Website') ?></label>
                    <input type="text" class="form-control" value="<?php _esc($site ? $site['site_name'] : __('Unknown website')) ?>" readonly>
                </div>
                <div class="form-group">
                    <label><?php _e('Owner') ?></label>
                    <input type="text" class="form-control" value="<?php _esc($owner ? $owner['username'] . ' (' . $owner['email'] . ')' : __('Unknown owner')) ?>" readonly>
                </div>
                <div class="form-group">
                    <label><?php _e('Amount') ?> (<?php _esc($config['currency_sign']) ?>)</label>
                    <input type="text" class="form-control" value="<?php _esc($payout['amount']) ?>" readonly>
                </div>
                <div class="form-group">
                    <label><?php _e('Payment Method') ?></label>
                    <input type="text" class="form-control" value="<?php _esc($payout['payment_method']) ?>" readonly>
                </div>
                <div class="form-group">
                    <label><?php _e('Account Details') ?></label>
                    <textarea class="form-control" readonly rows="4"><?php _esc($payout['account_details']) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="status"><?php _e('Status') ?></label>
                    <select name="status" id="status" class="form-control website-payout-status">
                        <option value="paid" <?php echo 'paid' == $payout['status'] ? 'selected' : '' ?>><?php _e('Paid'); ?></option>
                        <option value="pending" <?php echo 'pending' == $payout['status'] ? 'selected' : '' ?>><?php _e('Pending'); ?></option>
                        <option value="rejected" <?php echo 'rejected' == $payout['status'] ? 'selected' : '' ?>><?php _e('Rejected'); ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="notes"><?php _e('Admin Notes') ?></label>
                    <textarea name="notes" id="notes" class="form-control" rows="4"><?php _esc($payout['notes']) ?></textarea>
                    <span class="form-text text-muted"><?php _e('These notes are shared with the site owner when the payout is updated.') ?></span>
                </div>
            </div>
        </form>
    </div>
</div>
