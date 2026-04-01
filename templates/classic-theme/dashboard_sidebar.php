<?php
global $current_user;
$plan_settings = $current_user['plan']['settings']; ?>
<!-- Dashboard Sidebar
    ================================================== -->
<div class="dashboard-sidebar">
    <div class="dashboard-sidebar-inner" data-simplebar>
        <div class="dashboard-nav-container">
            <div class="atlas-sidebar-brand">
                <a href="<?php url("DASHBOARD") ?>" class="atlas-sidebar-brand-link">
                    <span class="atlas-sidebar-brand-copy">
                        <strong><?php _esc($config['site_title']) ?></strong>
                        <small><?php _e("Your AI workspace") ?></small>
                    </span>
                </a>
            </div>

            <!-- Responsive Navigation Trigger -->
            <a href="#" class="dashboard-responsive-nav-trigger">
					<span class="hamburger hamburger--collapse">
						<span class="hamburger-box">
							<span class="hamburger-inner"></span>
						</span>
					</span>
                <span class="trigger-title"><?php _e("Dashboard Navigation") ?></span>
            </a>
            <!-- Navigation -->
            <div class="dashboard-nav">
                <div class="dashboard-nav-inner">
                    <ul data-submenu-title="<?php _e("My Account") ?>">
                        <li class="<?php echo CURRENT_PAGE == 'app/dashboard' ? 'active' : ''; ?>"><a
                                    href="<?php url("DASHBOARD") ?>"><i
                                        class="icon-feather-grid"></i> <?php _e("Dashboard") ?></a></li>
                    </ul>

                    <ul data-submenu-title="<?php _e("Organize and Manage") ?>">
                        <li class="<?php echo CURRENT_PAGE == 'global/company-intelligence' ? 'active' : ''; ?>"><a
                                    href="<?php url("COMPANY_INTELLIGENCE") ?>"><i
                                        class="icon-feather-activity"></i> <?php _e("Company Intelligence") ?></a></li>
                        <?php if ($config['enable_ai_images']) {
                            if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_images_limit'])) { ?>
                                <li class="<?php echo in_array(CURRENT_PAGE, ['app/ai-images', 'app/ai-images-campaign', 'app/ai-images-grid'], true) ? 'active' : ''; ?>"><a
                                            href="<?php url("AI_IMAGES") ?>"><i
                                                class="icon-feather-image"></i> <?php _e("Social Media Automation") ?></a></li>
                            <?php }
                        }

                        if ($config['enable_ai_chat']) {
                            if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_chat'])) { ?>
                                <li class="<?php echo CURRENT_PAGE == 'app/ai-chat' || CURRENT_PAGE == 'app/ai-chat-bots' ? 'active' : ''; ?>">
                                    <a href="<?php url("AI_CHAT_BOTS") ?>">
                                        <i class="icon-feather-message-circle"></i> <?php _e("Your Ai Agents") ?>
                                    </a></li>
                            <?php }
                        }

                        if ($config['enable_speech_to_text']) {
                            if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_speech_to_text_limit'])) {
                                ?>
                                <li class="<?php echo CURRENT_PAGE == 'app/ai-speech-text' ? 'active' : ''; ?>"><a
                                            href="<?php url("AI_SPEECH_TEXT") ?>"><i
                                                class="icon-feather-headphones"></i> <?php _e("Speech to Text") ?></a>
                                </li>
                            <?php }
                        }

                        if (get_option('enable_text_to_speech', 0)) {
                            if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_text_to_speech_limit'])) { ?>
                                <li class="<?php echo CURRENT_PAGE == 'app/ai-text-speech' ? 'active' : ''; ?>"><a
                                            href="<?php url("AI_TEXT_SPEECH") ?>"><i
                                                class="icon-feather-volume-2"></i> <?php _e("Text to Speech") ?></a>
                                </li>
                            <?php }
                        }

                        if ($config['enable_ai_code']) {
                            if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_code'])) { ?>
                                <li class="<?php echo CURRENT_PAGE == 'app/ai-code' ? 'active' : ''; ?>"><a
                                            href="<?php url("AI_CODE") ?>"><i
                                                class="icon-feather-code"></i> <?php _e("AI Code") ?></a></li>
                            <?php }
                        } ?>
                    </ul>

                    <ul data-submenu-title="<?php _e("Account") ?>">

                        <?php if ($config['enable_affiliate_program']) {
                            if (get_option('allow_affiliate_payouts', 1)) { ?>
                                <li class="<?= CURRENT_PAGE == 'global/affiliate-program' || CURRENT_PAGE == 'global/withdrawals' ? 'active-submenu' : ''; ?>">
                                    <a href="<?php url("AFFILIATE-PROGRAM") ?>"><i
                                                class="icon-feather-share-2"></i> <?php _e("Affiliate Program") ?></a>
                                    <ul>
                                        <li class="<?= CURRENT_PAGE == 'global/affiliate-program' ? 'active' : ''; ?>">
                                            <a
                                                    href="<?php url("AFFILIATE-PROGRAM") ?>"><?php _e("Affiliate Program") ?></a>
                                        </li>
                                        <li class="<?= CURRENT_PAGE == 'global/withdrawals' ? 'active' : ''; ?>"><a
                                                    href="<?php url("WITHDRAWALS") ?>"><?php _e("Withdrawals") ?></a>
                                        </li>
                                    </ul>
                                </li>
                            <?php } else { ?>
                                <li class="<?= CURRENT_PAGE == 'global/affiliate-program' ? 'active' : ''; ?>"><a
                                            href="<?php url("AFFILIATE-PROGRAM") ?>"><i
                                                class="icon-feather-share-2"></i> <?php _e("Affiliate Program") ?></a>
                                </li>
                            <?php }
                        } ?>
                        <li class="<?php echo CURRENT_PAGE == 'global/membership' ? 'active' : ''; ?>"><a
                                    href="<?php url("MEMBERSHIP") ?>"><i
                                        class="icon-feather-gift"></i> <?php _e("Membership") ?></a></li>
                        <li class="<?php echo CURRENT_PAGE == 'global/transaction' ? 'active' : ''; ?>"><a
                                    href="<?php url("TRANSACTION") ?>"><i
                                        class="icon-feather-file-text"></i> <?php _e("Transactions") ?></a></li>
                        <li class="<?php echo CURRENT_PAGE == 'global/account-setting' ? 'active' : ''; ?>"><a
                                    href="<?php url("ACCOUNT_SETTING") ?>"><i
                                        class="icon-feather-log-out"></i> <?php _e("Account Setting") ?></a></li>
                        <li><a href="<?php url("LOGOUT") ?>"><i
                                        class="icon-material-outline-power-settings-new"></i> <?php _e("Logout") ?></a>
                        </li>
                    </ul>

                </div>
            </div>
            <!-- Navigation / End -->
            <div class="atlas-sidebar-footer">
                <div class="atlas-sidebar-footer-card">
                    <strong><?php _e("Private Workspace") ?></strong>
                    <span><?php _e("Company-aware agents, social content, and business context in one place.") ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Dashboard Sidebar / End -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    var trigger = document.querySelector('.dashboard-responsive-nav-trigger');
    if (!trigger) {
        return;
    }

    var navContainer = trigger.closest('.dashboard-nav-container');
    var nav = navContainer ? navContainer.querySelector('.dashboard-nav') : null;
    var hamburger = trigger.querySelector('.hamburger');

    if (!nav) {
        return;
    }

    var toggleNav = function (event) {
        if (event) {
            event.preventDefault();
        }

        var isActive = trigger.classList.toggle('active');
        nav.classList.toggle('active', isActive);

        if (hamburger) {
            hamburger.classList.toggle('is-active', isActive);
        }

        trigger.setAttribute('aria-expanded', isActive ? 'true' : 'false');
    };

    trigger.setAttribute('aria-expanded', nav.classList.contains('active') ? 'true' : 'false');
    trigger.addEventListener('click', toggleNav);
    trigger.addEventListener('touchend', function (event) {
        if (event.cancelable) {
            event.preventDefault();
        }
        toggleNav();
    }, {passive: false});
});
</script>
