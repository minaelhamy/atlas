<?php
overall_header(__("Campaign Detail"));
$generationHistory = !empty($selected_campaign['recent_generations']) && is_array($selected_campaign['recent_generations'])
    ? array_reverse($selected_campaign['recent_generations'])
    : [];
$strategyRows = [
    __('Grid Style') => !empty($selected_campaign_form['grid_style']) ? $selected_campaign_form['grid_style'] : '',
];
$strategyRows = array_filter($strategyRows, function ($value) {
    return $value !== '';
});
$creativeDirectionRows = [
    [
        'label' => __('Business context'),
        'value' => __('Company intelligence'),
        'note' => __('Atlas uses the real business profile, audience, offer, pains, differentiators, and promise as the source of truth for posts and grids.')
    ],
];
if (!empty($selected_campaign_form['grid_style'])) {
    $creativeDirectionRows[] = [
        'label' => __('Grid style'),
        'value' => $selected_campaign_form['grid_style'],
        'note' => __('This only shapes sequencing and layout. The message and imagery still stay tied to the business.')
    ];
}
$campaignSummaryChips = [
    sprintf(__('%d linked posts'), (int) ($selected_campaign['generated_posts_count'] ?? count($campaign_posts))),
    sprintf(__('%d generation runs'), count($generationHistory)),
    !empty($selected_campaign['last_generated_at']) ? __('Updated ') . timeAgo($selected_campaign['last_generated_at']) : __('Not generated yet'),
];
?>
<div class="dashboard-container">
    <?php include_once TEMPLATE_PATH . '/dashboard_sidebar.php'; ?>
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner atlas-workflow-shell">
            <?php print_adsense_code('header_bottom'); ?>

            <div class="dashboard-headline">
                <h3 class="d-flex align-items-center">
                    <?php _e("Campaign Detail") ?>
                    <div class="word-used-wrapper margin-left-10">
                        <i class="icon-feather-bar-chart-2"></i>
                        <?php echo '<i id="quick-images-left">' .
                            _esc(number_format((float)$total_images_used), 0) . '</i> / ' .
                            ($images_limit == -1
                                ? __('Unlimited')
                                : _esc(number_format($images_limit + get_user_option($_SESSION['user']['id'], 'total_images_available', 0)), 0)); ?>
                        <strong><?php _e('Posts Used'); ?></strong>
                    </div>
                </h3>
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><a href="<?php url("DASHBOARD") ?>"><?php _e("Dashboard") ?></a></li>
                        <li><?php _e("Campaign Detail") ?></li>
                    </ul>
                </nav>
            </div>

            <?php if (!empty($campaign_notice)) { ?>
                <div class="notification success"><?php _esc($campaign_notice) ?></div>
            <?php } ?>

            <?php if (!empty($campaign_error)) { ?>
                <div class="notification error"><?php _esc($campaign_error) ?></div>
            <?php } ?>

            <?php if (($selected_campaign['status'] ?? '') === 'archived') { ?>
                <div class="notification warning">
                    <strong><?php _e("This campaign is archived.") ?></strong>
                    <?php _e("You can still review it and restore it whenever you want.") ?>
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-xl-8">
                    <div class="dashboard-box margin-top-0 margin-bottom-24 atlas-campaign-hero-box">
                        <div class="content with-padding">
                            <div class="atlas-campaign-studio">
                                <div class="atlas-campaign-studio-main">
                                    <span class="atlas-workflow-eyebrow"><?php _e("Campaign studio") ?></span>
                                    <h2 class="margin-top-10 margin-bottom-10"><?php _esc($selected_campaign['title']) ?></h2>
                                    <p class="margin-bottom-20">
                                        <?php echo !empty($selected_campaign['description'])
                                            ? _esc($selected_campaign['description'])
                                            : __('This campaign does not have a written brief yet.'); ?>
                                    </p>

                                    <div class="atlas-campaign-pill-row margin-bottom-20">
                                        <?php foreach ($strategyRows as $label => $value) { ?>
                                            <span class="atlas-campaign-pill"><strong><?php _esc($label) ?>:</strong>&nbsp;<?php _esc($value) ?></span>
                                        <?php } ?>
                                    </div>

                                    <div class="atlas-campaign-summary-row">
                                        <?php foreach ($campaignSummaryChips as $campaignSummaryChip) { ?>
                                            <span class="atlas-campaign-summary-chip"><?php _esc($campaignSummaryChip) ?></span>
                                        <?php } ?>
                                    </div>

                                    <div class="atlas-campaign-actions">
                                        <a href="<?php echo _esc(hatchers_campaign_edit_url() . '?campaign_id=' . rawurlencode($selected_campaign['id']), 0); ?>" class="button ripple-effect atlas-primary-action">
                                            <?php _e("Open Generator") ?>
                                        </a>
                                        <a href="<?php echo _esc(url("AI_IMAGES_GRID", 0) . '?campaign_id=' . rawurlencode($selected_campaign['id']), 0); ?>" class="button gray ripple-effect">
                                            <?php _e("Open Grid Builder") ?>
                                        </a>
                                        <a href="<?php echo _esc(url("ALL_IMAGES", 0) . '?campaign_id=' . rawurlencode($selected_campaign['id']), 0); ?>" class="button gray ripple-effect">
                                            <?php _e("Open Content Library") ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="atlas-campaign-studio-side">
                                    <div class="atlas-campaign-side-card">
                                        <span class="atlas-workflow-eyebrow"><?php _e("Creative direction") ?></span>
                                        <h4><?php _e("What Atlas should make next") ?></h4>
                                        <p><?php _e("Atlas now uses company intelligence as the source of truth, then applies the selected grid style as the visual system.") ?></p>
                                        <div class="atlas-direction-list">
                                            <?php foreach ($creativeDirectionRows as $directionRow) { ?>
                                                <div class="atlas-direction-item">
                                                    <strong><?php _esc($directionRow['label']) ?> · <?php _esc($directionRow['value']) ?></strong>
                                                    <span><?php _esc($directionRow['note']) ?></span>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-box margin-top-0 margin-bottom-24">
                        <div class="headline">
                            <h3><i class="icon-feather-grid"></i> <?php _e("Creative Output") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <div class="row">
                                <?php if (empty($campaign_posts)) { ?>
                                    <div class="col-12">
                                        <div class="notification notice margin-bottom-0">
                                            <?php _e("No posts are linked to this campaign yet. Use the generator or grid builder and Atlas will keep the outputs grouped here like a campaign studio.") ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php foreach ($campaign_posts as $post) {
                                    $meta = !empty($post['metadata']) && is_array($post['metadata']) ? $post['metadata'] : [];
                                    $hashtags = !empty($meta['hashtags']) && is_array($meta['hashtags']) ? implode(' ', $meta['hashtags']) : '';
                                    $videoUrl = !empty($meta['rendered_video'])
                                        ? _esc($config['site_url'], 0) . 'storage/social_posts/videos/' . $meta['rendered_video']
                                        : (!empty($meta['source_video']) ? _esc($config['site_url'], 0) . 'storage/social_assets/' . $meta['source_video'] : '');
                                    $previewUrl = !empty($post['preview_image']) ? $post['preview_image'] : (_esc($config['site_url'], 0) . 'storage/social_posts/' . $post['preview_image_file']);
                                    ?>
                                    <div class="col-xl-6 margin-bottom-30">
                                        <div class="dashboard-box social-post-card margin-top-0">
                                            <div class="content">
                                                <div class="social-post-preview">
                                                    <?php if (!empty($videoUrl) && $post['post_type'] === 'reel') { ?>
                                                        <video src="<?php echo $videoUrl; ?>" autoplay muted loop playsinline controls preload="metadata"></video>
                                                    <?php } else { ?>
                                                        <img src="<?php echo $previewUrl; ?>" alt="<?php _esc($post['title']) ?>">
                                                    <?php } ?>
                                                </div>
                                                <div class="social-post-body with-padding">
                                                    <span class="dashboard-status-button yellow"><?php _esc(ucfirst($post['post_type'])) ?></span>
                                                    <h4 class="margin-top-15"><?php _esc($post['title']) ?></h4>
                                                    <p class="margin-bottom-10"><?php _esc($post['caption']) ?></p>
                                                    <?php if (!empty($hashtags)) { ?>
                                                        <p class="margin-bottom-10"><strong><?php _e("Hashtags") ?>:</strong> <?php _esc($hashtags) ?></p>
                                                    <?php } ?>
                                                    <?php if (!empty($post['updated_at'])) { ?>
                                                        <p class="margin-bottom-15"><small><?php _e("Updated") ?> <?php _esc(timeAgo($post['updated_at'])) ?></small></p>
                                                    <?php } ?>
                                                    <?php
                                                    $downloadUrl = (!empty($videoUrl) && $post['post_type'] === 'reel') ? $videoUrl : $previewUrl;
                                                    $captionExport = $post['caption'];
                                                    if (!empty($hashtags)) {
                                                        $captionExport .= "\n\n" . $hashtags;
                                                    }
                                                    ?>
                                                    <div class="social-post-actions">
                                                        <a href="<?php echo $downloadUrl; ?>" class="social-action-btn" download title="<?php _e("Download Post") ?>" aria-label="<?php _e("Download Post") ?>"><i class="fa fa-download"></i></a>
                                                        <a href="#" class="social-action-btn download-caption" data-title="<?php _esc($post['title']) ?>" data-caption="<?php _esc($captionExport) ?>" title="<?php _e("Download Caption") ?>" aria-label="<?php _e("Download Caption") ?>"><i class="fa fa-file-text-o"></i></a>
                                                        <?php if (!empty($videoUrl) && $post['post_type'] === 'reel') { ?>
                                                            <a href="<?php echo $videoUrl; ?>" class="social-action-btn" target="_blank" title="<?php _e("Open Reel Video") ?>" aria-label="<?php _e("Open Reel Video") ?>"><i class="fa fa-play"></i></a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="dashboard-box margin-top-0 margin-bottom-24">
                        <div class="headline">
                            <h3><i class="icon-feather-edit-3"></i> <?php _e("Campaign Setup") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <form method="post" action="">
                                <input type="hidden" name="campaign_action" value="save_campaign_detail">
                                <div class="submit-field">
                                    <h5><?php _e("Campaign Title") ?></h5>
                                    <input type="text" class="with-border" name="title" value="<?php _esc($selected_campaign['title']) ?>" required>
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("Core Brief") ?></h5>
                                    <textarea name="description" class="with-border" rows="4" placeholder="<?php _e("Capture the offer, audience, painful problem, desired outcome, and believable promise Atlas should keep reinforcing.") ?>"><?php _esc(!empty($selected_campaign['description']) ? $selected_campaign['description'] : '') ?></textarea>
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e("Grid Style") ?></h5>
                                    <select name="grid_style" class="selectpicker with-border" data-size="10">
                                        <option value=""><?php _e("Use current default") ?></option>
                                        <option value="auto" <?php echo (!empty($selected_campaign_form['grid_style']) && $selected_campaign_form['grid_style'] === 'auto') ? 'selected' : ''; ?>><?php _e("Auto select the best grid") ?></option>
                                        <?php foreach ($grid_catalog as $gridKey => $gridMeta) { ?>
                                            <option value="<?php _esc($gridKey) ?>" <?php echo (!empty($selected_campaign_form['grid_style']) && $selected_campaign_form['grid_style'] === $gridKey) ? 'selected' : ''; ?>><?php _esc($gridMeta['label']) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <button type="submit" class="button ripple-effect atlas-primary-action"><?php _e("Save Campaign Detail") ?></button>
                            </form>
                            <div class="atlas-campaign-actions margin-top-15">
                                <form method="post" action="" class="atlas-inline-action-form">
                                    <input type="hidden" name="campaign_action" value="duplicate_campaign">
                                    <button type="submit" class="button gray ripple-effect"><?php _e("Duplicate Campaign") ?></button>
                                </form>
                                <?php if (($selected_campaign['status'] ?? '') === 'archived') { ?>
                                    <form method="post" action="" class="atlas-inline-action-form">
                                        <input type="hidden" name="campaign_action" value="restore_campaign">
                                        <button type="submit" class="button gray ripple-effect"><?php _e("Restore Campaign") ?></button>
                                    </form>
                                <?php } else { ?>
                                    <form method="post" action="" class="atlas-inline-action-form" onsubmit="return confirm('<?php _e("Archive this campaign? You can keep its linked posts, but it will be removed from active planning views.") ?>');">
                                        <input type="hidden" name="campaign_action" value="archive_campaign">
                                        <button type="submit" class="button gray ripple-effect"><?php _e("Archive Campaign") ?></button>
                                    </form>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-box margin-top-0 margin-bottom-24">
                        <div class="headline">
                            <h3><i class="icon-feather-bar-chart-2"></i> <?php _e("Studio Stats") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <div class="atlas-campaign-stats">
                                <div class="atlas-campaign-stat">
                                    <strong><?php _esc((int) ($selected_campaign['generated_posts_count'] ?? count($campaign_posts))) ?></strong>
                                    <span><?php _e("Linked posts") ?></span>
                                </div>
                                <div class="atlas-campaign-stat">
                                    <strong><?php _esc(count($generationHistory)) ?></strong>
                                    <span><?php _e("Generation runs") ?></span>
                                </div>
                                <div class="atlas-campaign-stat">
                                    <strong><?php _esc(!empty($selected_campaign['last_generated_at']) ? timeAgo($selected_campaign['last_generated_at']) : __('Not yet')) ?></strong>
                                    <span><?php _e("Last generated") ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-box margin-top-0 margin-bottom-24">
                        <div class="headline">
                            <h3><i class="icon-feather-refresh-cw"></i> <?php _e("Generation Timeline") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <?php if (!empty($generationHistory)) { ?>
                                <div class="atlas-dashboard-quick-list">
                                    <?php foreach ($generationHistory as $generation) { ?>
                                        <div class="atlas-dashboard-quick-item atlas-dashboard-quick-item-static">
                                            <span class="atlas-dashboard-quick-avatar"><i class="icon-feather-layers"></i></span>
                                            <span class="atlas-dashboard-quick-copy">
                                                <strong><?php _esc(!empty($generation['generator']) ? ucwords(str_replace('_', ' ', $generation['generator'])) : __('Generation')) ?></strong>
                                                <small><?php echo sprintf(__('%d posts in batch %s'), (int) ($generation['post_count'] ?? 0), !empty($generation['batch_key']) ? $generation['batch_key'] : ''); ?></small>
                                            </span>
                                            <span class="atlas-dashboard-quick-meta"><?php _esc(!empty($generation['created_at']) ? timeAgo($generation['created_at']) : __('Saved')) ?></span>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } else { ?>
                                <p class="margin-bottom-0"><?php _e("Once you generate from this campaign, Atlas will keep a generation history here.") ?></p>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="dashboard-box margin-top-0 margin-bottom-24">
                        <div class="headline">
                            <h3><i class="icon-feather-compass"></i> <?php _e("Switch Campaign") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <div class="atlas-dashboard-quick-list">
                                <?php foreach ($recent_campaigns as $campaign) { ?>
                                    <?php if (($campaign['id'] ?? '') === ($selected_campaign['id'] ?? '')) { continue; } ?>
                                    <a href="<?php _esc(hatchers_campaign_record_url($campaign['id'])) ?>" class="atlas-dashboard-quick-item">
                                        <span class="atlas-dashboard-quick-avatar"><i class="icon-feather-target"></i></span>
                                        <span class="atlas-dashboard-quick-copy">
                                            <strong><?php _esc($campaign['title']) ?></strong>
                                            <small><?php _esc(!empty($campaign['description']) ? strlimiter(strip_tags((string) $campaign['description']), 80) : __('Open saved campaign')) ?></small>
                                        </span>
                                        <span class="atlas-dashboard-quick-meta"><?php _esc(!empty($campaign['updated_at']) ? timeAgo($campaign['updated_at']) : __('Saved')) ?></span>
                                    </a>
                                <?php } ?>
                                <?php if (count($recent_campaigns) <= 1) { ?>
                                    <p class="margin-bottom-0"><?php _e("As you create more campaigns, Atlas will surface them here for quick switching.") ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard-box margin-top-0 margin-bottom-24">
                        <div class="headline">
                            <h3><i class="icon-feather-archive"></i> <?php _e("Archived Campaigns") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <div class="atlas-dashboard-quick-list">
                                <?php foreach ($archived_campaigns as $campaign) { ?>
                                    <?php if (($campaign['id'] ?? '') === ($selected_campaign['id'] ?? '')) { continue; } ?>
                                    <a href="<?php _esc(hatchers_campaign_record_url($campaign['id'])) ?>" class="atlas-dashboard-quick-item">
                                        <span class="atlas-dashboard-quick-avatar"><i class="icon-feather-archive"></i></span>
                                        <span class="atlas-dashboard-quick-copy">
                                            <strong><?php _esc($campaign['title']) ?></strong>
                                            <small><?php _esc(!empty($campaign['description']) ? strlimiter(strip_tags((string) $campaign['description']), 80) : __('Open archived campaign')) ?></small>
                                        </span>
                                        <span class="atlas-dashboard-quick-meta"><?php _esc(!empty($campaign['updated_at']) ? timeAgo($campaign['updated_at']) : __('Archived')) ?></span>
                                    </a>
                                <?php } ?>
                                <?php if (empty($archived_campaigns) || (count($archived_campaigns) === 1 && ($selected_campaign['status'] ?? '') === 'archived')) { ?>
                                    <p class="margin-bottom-0"><?php _e("Archived campaigns stay accessible here without cluttering the active planning flow.") ?></p>
                                <?php } ?>
                            </div>
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
<style>
    .atlas-campaign-hero-box {
        border: 1px solid #eadfce;
        background:
            radial-gradient(circle at top right, rgba(255, 221, 183, 0.36), transparent 28%),
            linear-gradient(180deg, #fffaf1 0%, #ffffff 100%);
    }
    .atlas-campaign-studio {
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) 320px;
        gap: 22px;
        align-items: start;
    }
    .atlas-campaign-studio-main {
        min-width: 0;
    }
    .atlas-campaign-studio-side {
        min-width: 0;
    }
    .atlas-campaign-side-card {
        border-radius: 22px;
        padding: 18px 18px 16px;
        border: 1px solid #ebdfcf;
        background: linear-gradient(180deg, rgba(32, 29, 24, 0.98) 0%, rgba(49, 40, 29, 0.98) 100%);
        color: #fff;
        box-shadow: 0 16px 38px rgba(26, 20, 11, 0.16);
    }
    .atlas-campaign-side-card h4 {
        margin: 0 0 8px;
        color: #fff;
        font-size: 20px;
        letter-spacing: -0.03em;
    }
    .atlas-campaign-side-card p {
        margin: 0;
        color: rgba(255,255,255,.74);
        line-height: 1.6;
        font-size: 13px;
    }
    .atlas-direction-list {
        display: grid;
        gap: 10px;
        margin-top: 16px;
    }
    .atlas-direction-item {
        border-radius: 16px;
        padding: 12px 13px;
        border: 1px solid rgba(255,255,255,.08);
        background: rgba(255,255,255,.06);
    }
    .atlas-direction-item strong {
        display: block;
        font-size: 12.5px;
        line-height: 1.4;
        color: #fff;
        margin-bottom: 4px;
    }
    .atlas-direction-item span {
        display: block;
        font-size: 11.5px;
        line-height: 1.5;
        color: rgba(255,255,255,.72);
    }
    .atlas-campaign-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 18px;
    }
    .atlas-inline-action-form {
        margin: 0;
    }
    .atlas-campaign-pill-row {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .atlas-campaign-pill {
        display: inline-flex;
        align-items: center;
        padding: 7px 11px;
        border-radius: 999px;
        background: #f6f1e6;
        border: 1px solid #e7dcc9;
        color: #5d503c;
        font-size: 13px;
        font-weight: 600;
    }
    .atlas-campaign-summary-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .atlas-campaign-summary-chip {
        display: inline-flex;
        align-items: center;
        padding: 6px 11px;
        border-radius: 999px;
        background: #fff;
        border: 1px solid #eadfce;
        color: #6c5d49;
        font-size: 12px;
        font-weight: 600;
    }
    .atlas-campaign-stats {
        display: grid;
        gap: 12px;
    }
    .atlas-campaign-stat {
        padding: 14px 16px;
        border-radius: 14px;
        background: #fbf8f2;
        border: 1px solid #efe4d3;
    }
    .atlas-campaign-stat strong {
        display: block;
        font-size: 20px;
        color: #332a1d;
        line-height: 1.2;
    }
    .atlas-campaign-stat span {
        display: block;
        font-size: 13px;
        color: #7a6b57;
        margin-top: 4px;
    }
    .atlas-dashboard-quick-item-static {
        cursor: default;
    }
    .social-post-card .social-post-preview img,
    .social-post-card .social-post-preview video {
        width: 100%;
        border-radius: 12px 12px 0 0;
        display: block;
        background: #000;
    }
    .social-post-card .social-post-body {
        background: #fff;
    }
    .social-post-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: 2px;
    }
    .social-action-btn {
        flex: 0 0 auto;
    }
    @media (max-width: 1199px) {
        .atlas-campaign-studio {
            grid-template-columns: 1fr;
        }
    }
</style>
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH . '/overall_footer_dashboard.php';
