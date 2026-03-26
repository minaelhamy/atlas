<?php
overall_header(__("Dashboard"));
?>
<div class="dashboard-container">
    <?php include_once TEMPLATE_PATH . '/dashboard_sidebar.php'; ?>
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner">

            <?php print_adsense_code('header_bottom'); ?>

            <div class="dashboard-headline">
                <h3><?php _e("Dashboard") ?></h3>
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Dashboard") ?></li>
                    </ul>
                </nav>
            </div>

            <div class="fun-facts-container atlas-dashboard-stats">
                <div class="fun-fact" data-fun-fact-color="#b81b7f">
                    <div class="fun-fact-text">
                        <span><?php _e("Words Used"); ?></span>
                        <h4>
                            <?php _esc(number_format($total_words_used)); ?>
                            <small>/ <?php _esc(
                                    $membership_settings['ai_words_limit'] == -1
                                        ? __('Unlimited')
                                        : number_format($membership_settings['ai_words_limit'] + get_user_option($_SESSION['user']['id'], 'total_words_available', 0))
                                ); ?></small>
                        </h4>
                    </div>
                    <div class="fun-fact-icon"><i class="icon-feather-type"></i></div>
                </div>
                <?php if ($config['enable_ai_images']) { ?>
                    <div class="fun-fact" data-fun-fact-color="#36bd78">
                        <div class="fun-fact-text">
                            <span><?php _e("Posts Used"); ?></span>
                            <h4>
                                <?php _esc(number_format($total_images_used)); ?>
                                <small>/ <?php _esc(
                                        $membership_settings['ai_images_limit'] == -1
                                            ? __('Unlimited')
                                            : number_format($membership_settings['ai_images_limit'] + get_user_option($_SESSION['user']['id'], 'total_images_available', 0))
                                    ); ?></small>
                            </h4>
                        </div>
                        <div class="fun-fact-icon"><i class="icon-feather-image"></i></div>
                    </div>
                <?php } else { ?>
                    <div class="fun-fact" data-fun-fact-color="#36bd78">
                        <div class="fun-fact-text">
                            <span><?php _e("Documents"); ?></span>
                            <h4><?php _esc(number_format($total_documents_created)); ?></h4>
                        </div>
                        <div class="fun-fact-icon"><i class="icon-feather-file-text"></i></div>
                    </div>
                <?php } ?>
                <div class="fun-fact" data-fun-fact-color="#efa80f">
                    <div class="fun-fact-text">
                        <span><?php _e("Membership"); ?></span>
                        <h4><small><?php _esc($membership_name); ?></small></h4>
                    </div>
                    <div class="fun-fact-icon"><i class="icon-feather-award"></i></div>
                </div>
            </div>

            <div class="dashboard-box intelligence-panel-box atlas-dashboard-intelligence margin-top-0 margin-bottom-24">
                <div class="headline">
                    <h3><i class="icon-feather-activity"></i> <?php _e("Company Intelligence") ?></h3>
                </div>
                <div class="content with-padding">
                    <div class="atlas-dashboard-intelligence-top">
                        <p class="margin-bottom-0 intelligence-refreshed-at">
                            <strong><?php _e("Last Refreshed") ?>:</strong>
                            <span><?php _esc(!empty($company_intelligence['refreshed_at']) ? $company_intelligence['refreshed_at'] : __('Not generated yet')) ?></span>
                        </p>
                        <a href="#" class="button ripple-effect intelligence-refresh-btn"><?php _e("Refresh Now") ?></a>
                    </div>
                    <div class="atlas-dashboard-intelligence-grid">
                        <div class="atlas-dashboard-intelligence-card">
                            <span class="atlas-dashboard-label"><?php _e("Company Summary") ?></span>
                            <p class="intelligence-company-summary"><?php _esc(!empty($company_intelligence['company_summary']) ? $company_intelligence['company_summary'] : __('No summary yet. Save your company profile and refresh intelligence.')) ?></p>
                        </div>
                        <div class="atlas-dashboard-intelligence-card">
                            <span class="atlas-dashboard-label"><?php _e("Market Research") ?></span>
                            <p class="intelligence-market-research"><?php _esc(!empty($company_intelligence['market_research']) ? $company_intelligence['market_research'] : __('No market research yet.')) ?></p>
                        </div>
                        <div class="atlas-dashboard-intelligence-card">
                            <span class="atlas-dashboard-label"><?php _e("Competitive Edges") ?></span>
                            <p class="intelligence-competitive-edges"><?php _esc(!empty($company_intelligence['competitive_edges']) ? $company_intelligence['competitive_edges'] : __('No competitive edge summary yet.')) ?></p>
                        </div>
                        <div class="atlas-dashboard-intelligence-card">
                            <span class="atlas-dashboard-label"><?php _e("Strategic Guidance") ?></span>
                            <p class="intelligence-strategic-guidance"><?php _esc(!empty($company_intelligence['strategic_guidance']) ? $company_intelligence['strategic_guidance'] : __('No strategic guidance yet.')) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row atlas-dashboard-secondary-row">
                <div class="col-lg-6">
                    <div class="dashboard-box margin-top-0 margin-bottom-24 atlas-dashboard-list-box">
                        <div class="headline">
                            <h3><i class="icon-feather-message-circle"></i> <?php _e("Your Most Used AI Agents") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <?php if (!empty($top_agents)) { ?>
                                <div class="atlas-dashboard-quick-list">
                                    <?php foreach ($top_agents as $agent) { ?>
                                        <a href="<?php _esc($agent['link']) ?>" class="atlas-dashboard-quick-item">
                                            <span class="atlas-dashboard-quick-avatar">
                                                <img src="<?php _esc($agent['image']) ?>" alt="<?php _esc($agent['name']) ?>">
                                            </span>
                                            <span class="atlas-dashboard-quick-copy">
                                                <strong><?php _esc($agent['name']) ?></strong>
                                                <small><?php _esc(!empty($agent['role']) ? $agent['role'] : __('AI Agent')) ?></small>
                                            </span>
                                            <span class="atlas-dashboard-quick-meta"><?php echo sprintf(__('%d chats'), (int) $agent['usage_count']); ?></span>
                                        </a>
                                    <?php } ?>
                                </div>
                            <?php } else { ?>
                                <p class="margin-bottom-0"><?php _e("Start chatting with your agents and your most used ones will appear here.") ?></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="dashboard-box margin-top-0 margin-bottom-24 atlas-dashboard-list-box">
                        <div class="headline">
                            <h3><i class="icon-feather-image"></i> <?php _e("Last Generated Posts") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <?php if (!empty($recent_social_posts)) { ?>
                                <div class="atlas-dashboard-post-list">
                                    <?php foreach ($recent_social_posts as $post) { ?>
                                        <a href="<?php _esc($post['link']) ?>" class="atlas-dashboard-post-item">
                                            <?php if (!empty($post['preview_url'])) { ?>
                                                <span class="atlas-dashboard-post-thumb">
                                                    <img src="<?php _esc($post['preview_url']) ?>" alt="<?php _esc($post['title']) ?>">
                                                </span>
                                            <?php } ?>
                                            <span class="atlas-dashboard-post-copy">
                                                <strong><?php _esc($post['title']) ?></strong>
                                                <small><?php _esc(!empty($post['overlay_text']) ? $post['overlay_text'] : __('Open in Social Media Generator')) ?></small>
                                            </span>
                                            <?php if (!empty($post['created_at'])) { ?>
                                                <span class="atlas-dashboard-quick-meta"><?php _esc($post['created_at']) ?></span>
                                            <?php } ?>
                                        </a>
                                    <?php } ?>
                                </div>
                            <?php } else { ?>
                                <p class="margin-bottom-0"><?php _e("Generate your first social posts and they will appear here for quick access.") ?></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php print_adsense_code('footer_top'); ?>
            <div class="dashboard-footer-spacer"></div>
            <div class="small-footer margin-top-15">
                <div class="footer-copyright"><?php _esc($config['copyright_text']); ?></div>
                <ul class="footer-social-links">
                    <?php
                    if ($config['facebook_link'] != "")
                        echo '<li><a href="' . _esc($config['facebook_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a></li>';
                    if ($config['twitter_link'] != "")
                        echo '<li><a href="' . _esc($config['twitter_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a></li>';
                    if ($config['instagram_link'] != "")
                        echo '<li><a href="' . _esc($config['instagram_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-instagram"></i></a></li>';
                    if ($config['linkedin_link'] != "")
                        echo '<li><a href="' . _esc($config['linkedin_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-linkedin"></i></a></li>';
                    if ($config['pinterest_link'] != "")
                        echo '<li><a href="' . _esc($config['pinterest_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-pinterest"></i></a></li>';
                    if ($config['youtube_link'] != "")
                        echo '<li><a href="' . _esc($config['youtube_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-youtube"></i></a></li>';
                    ?>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<?php ob_start() ?>
<script>
    (function ($) {
        $('.intelligence-refresh-btn').on('click', function (e) {
            e.preventDefault();
            var $btn = $(this);
            $btn.addClass('button-progress').prop('disabled', true);

            $.ajax({
                type: "POST",
                url: ajaxurl + '?action=refresh_company_intelligence',
                dataType: 'json',
                success: function (response) {
                    $btn.removeClass('button-progress').prop('disabled', false);
                    if (response.success && response.intelligence) {
                        $('.intelligence-refreshed-at span').text(response.intelligence.refreshed_at || '');
                        $('.intelligence-company-summary').text(response.intelligence.company_summary || '');
                        $('.intelligence-market-research').text(response.intelligence.market_research || '');
                        $('.intelligence-competitive-edges').text(response.intelligence.competitive_edges || '');
                        $('.intelligence-strategic-guidance').text(response.intelligence.strategic_guidance || '');
                        Snackbar.show({
                            text: response.message,
                            pos: 'bottom-center',
                            showAction: false,
                            actionText: "Dismiss",
                            duration: 3000,
                            textColor: '#fff',
                            backgroundColor: '#383838'
                        });
                    } else {
                        Snackbar.show({
                            text: response.error || '<?php echo escape(__('Unable to refresh company intelligence right now.')); ?>',
                            pos: 'bottom-center',
                            showAction: false,
                            actionText: "Dismiss",
                            duration: 3000,
                            textColor: '#fff',
                            backgroundColor: '#d32f2f'
                        });
                    }
                },
                error: function () {
                    $btn.removeClass('button-progress').prop('disabled', false);
                    Snackbar.show({
                        text: '<?php echo escape(__('Unable to refresh company intelligence right now.')); ?>',
                        pos: 'bottom-center',
                        showAction: false,
                        actionText: "Dismiss",
                        duration: 3000,
                        textColor: '#fff',
                        backgroundColor: '#d32f2f'
                    });
                }
            });
        });
    })(jQuery);
</script>
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH . '/overall_footer_dashboard.php';
