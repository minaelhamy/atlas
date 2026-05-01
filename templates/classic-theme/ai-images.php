<?php
overall_header(__("Create a Campaign"));
$profileReady = !empty($social_profile['company_name']) && !empty($social_profile['company_description']);
?>
<div class="dashboard-container">
    <?php include_once TEMPLATE_PATH . '/dashboard_sidebar.php'; ?>
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner atlas-workflow-shell">
            <?php print_adsense_code('header_bottom'); ?>
            <div class="dashboard-headline">
                <h3 class="d-flex align-items-center">
                    <?php _e("Campaign Studio") ?>
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
                        <li><a href="<?php url("AI_IMAGES") ?>"><?php _e("Campaign Studio") ?></a></li>
                        <li><?php _e("Shape a Campaign") ?></li>
                    </ul>
                </nav>
            </div>

            <div class="atlas-wizard-card margin-bottom-24">
                <div class="atlas-wizard-header">
                    <div>
                        <span class="atlas-workflow-eyebrow"><?php _e("Step 1 of 2") ?></span>
                        <h2><?php _e("Generate business-matched posts") ?></h2>
                        <p><?php _e("Atlas uses your company profile, offer, audience, and positioning to generate 9 posts that stay tied to the actual business instead of drifting into generic content.") ?></p>
                    </div>
                    <div class="atlas-stepper">
                        <span class="active"><?php _e("Direction") ?></span>
                        <span><?php _e("Output") ?></span>
                    </div>
                </div>
            </div>

            <?php if (!$profileReady) { ?>
                <div class="notification warning">
                    <?php _e("Complete your company profile in Account Settings before generating posts."); ?>
                    <a href="<?php url("ACCOUNT_SETTING") ?>"><strong><?php _e("Open Account Settings") ?></strong></a>
                </div>
            <?php } else { ?>
                <div class="notification notice">
                    <?php _e("Each run generates 9 business-specific posts using your company intelligence, offer, audience, and positioning. Atlas should anchor both the messaging and imagery to what the company actually sells or provides."); ?>
                </div>
            <?php } ?>

            <?php if (!empty($selected_campaign)) { ?>
                <div class="notification success">
                    <?php _e("Loaded saved campaign brief:") ?>
                    <strong><?php _esc($selected_campaign['title']) ?></strong>
                    <?php if (!empty($selected_campaign['updated_at'])) { ?>
                        <span>· <?php _e("Updated") ?> <?php _esc(timeAgo($selected_campaign['updated_at'])) ?></span>
                    <?php } ?>
                    <span>· <?php _e("Atlas will remember your strategy selections each time you generate from this campaign.") ?></span>
                </div>
            <?php } ?>

<?php if (!empty($selected_campaign)) { ?>
                <div class="dashboard-box margin-top-0 margin-bottom-24 atlas-campaign-detail-box">
                    <div class="headline">
                        <h3><i class="icon-feather-layers"></i> <?php _e("Loaded Studio Context") ?></h3>
                    </div>
                    <div class="content with-padding">
                        <div class="atlas-campaign-setup-hero">
                            <div class="atlas-campaign-setup-main">
                                <h4 class="margin-bottom-10"><?php _esc($selected_campaign['title']) ?></h4>
                                <?php if (!empty($selected_campaign['description'])) { ?>
                                    <p class="margin-bottom-15"><?php _esc($selected_campaign['description']) ?></p>
                                <?php } ?>
                            </div>
                            <div class="atlas-campaign-setup-side">
                                <div class="atlas-campaign-setup-card">
                                    <strong><?php _e("Why this studio context matters") ?></strong>
                                    <span><?php _e("Atlas keeps the campaign brief together so future generations stay consistent with the business and offer instead of drifting into generic stock messaging.") ?></span>
                                </div>
                                <div class="atlas-campaign-stats">
                                    <div class="atlas-campaign-stat">
                                        <strong><?php _esc((int) ($selected_campaign['generated_posts_count'] ?? count($campaign_posts ?? []))) ?></strong>
                                        <span><?php _e("Output linked") ?></span>
                                    </div>
                                    <div class="atlas-campaign-stat">
                                        <strong><?php _esc(!empty($selected_campaign['recent_generations']) ? count($selected_campaign['recent_generations']) : 0) ?></strong>
                                        <span><?php _e("Generations") ?></span>
                                    </div>
                                    <div class="atlas-campaign-stat">
                                        <strong><?php _esc(!empty($selected_campaign['last_generated_at']) ? timeAgo($selected_campaign['last_generated_at']) : __('Not yet')) ?></strong>
                                        <span><?php _e("Last generated") ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <form id="ai_images" name="ai_images" method="post" action="#">
                <?php if (!empty($selected_campaign['id'])) { ?>
                    <input type="hidden" name="campaign_id" value="<?php _esc($selected_campaign['id']) ?>">
                <?php } ?>
                <div class="dashboard-box margin-top-0 atlas-wizard-form-card">
                    <div class="headline">
                        <h3><i class="icon-feather-target"></i> <?php _e("Post Generator") ?></h3>
                    </div>
                    <div class="content with-padding">
                        <div class="atlas-wizard-inline-note margin-bottom-25">
                            <strong><?php _e("How Atlas uses this") ?>:</strong>
                            <?php _e("Atlas is generating directly from company intelligence now. It should use the actual business offer, audience, problem, USP, and outcome instead of extra campaign selectors.") ?>
                        </div>
                        <div class="social-campaign-summary atlas-strategy-summary margin-bottom-20">
                            <strong><?php _e("Atlas Readback") ?>:</strong>
                            <p class="margin-top-10 margin-bottom-0 social-campaign-summary-text"><?php _e("Atlas will use your company profile, company description, audience, problems solved, differentiators, key products or services, and recent company context to generate business-matched posts.") ?></p>
                        </div>
                        <small class="form-error"></small>
                        <button type="submit" name="submit" class="button ripple-effect atlas-primary-action" <?php echo $profileReady ? '' : 'disabled'; ?>>
                            <?php _e("Generate 9 Campaign Posts") ?> <i class="icon-feather-arrow-right"></i>
                        </button>
                        <div class="social-generator-progress margin-top-15" style="display:none;">
                            <div class="social-generator-progress-bar-wrap" style="height:10px;background:#ece7dc;border-radius:999px;overflow:hidden;">
                                <div class="social-generator-progress-bar" style="width:0%;height:100%;background:#8a7a63;transition:width .25s ease;"></div>
                            </div>
                            <div class="social-generator-progress-text margin-top-10" style="font-size:14px;font-weight:600;color:#6b5d4a;">0%</div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="dashboard-box">
                <div class="headline">
                    <h3><i class="icon-feather-grid"></i> <?php echo !empty($selected_campaign) ? __('Studio Output for This Campaign') : __('Latest Campaign Output'); ?></h3>
                </div>
                <div class="content with-padding">
                    <div class="row" id="generated_images_wrapper">
                        <?php
                        $posts_to_render = !empty($selected_campaign) ? $campaign_posts : $social_posts;
                        foreach ($posts_to_render as $post) {
                            $meta = !empty($post['metadata']) && is_array($post['metadata']) ? $post['metadata'] : [];
                            $hashtags = !empty($meta['hashtags']) && is_array($meta['hashtags']) ? implode(' ', $meta['hashtags']) : '';
                            $design = !empty($meta['design']) && is_array($meta['design']) ? $meta['design'] : [];
                            $videoUrl = !empty($meta['rendered_video'])
                                ? _esc($config['site_url'], 0) . 'storage/social_posts/videos/' . $meta['rendered_video']
                                : (!empty($meta['source_video']) ? _esc($config['site_url'], 0) . 'storage/social_assets/' . $meta['source_video'] : '');
                            $previewUrl = !empty($post['preview_image']) ? $post['preview_image'] : (_esc($config['site_url'], 0) . 'storage/social_posts/' . $post['preview_image_file']);
                            ?>
                            <div class="col-xl-4 col-md-6 margin-bottom-30 social-post-card-slot" data-post-id="<?php _esc($post['id']) ?>" data-social-context="campaign">
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
                                            <div class="social-overlay-editor margin-bottom-15">
                                                <label class="social-overlay-label" for="social-overlay-<?php _esc($post['id']) ?>"><strong><?php _e('Overlay') ?>:</strong></label>
                                                <textarea id="social-overlay-<?php _esc($post['id']) ?>" class="with-border social-overlay-input" rows="2" data-id="<?php _esc($post['id']) ?>"><?php _esc($post['overlay_text']) ?></textarea>
                                                <div class="social-overlay-actions">
                                                    <button type="button" class="button small ripple-effect social-overlay-save-btn" data-id="<?php _esc($post['id']) ?>"><?php _e('Save overlay') ?></button>
                                                </div>
                                            </div>
                                            <p class="margin-bottom-10"><strong><?php _e('Caption') ?>:</strong> <?php _esc($post['caption']) ?></p>
                                            <?php if (!empty($meta['cta'])) { ?>
                                                <p class="margin-bottom-10"><strong><?php _e('CTA') ?>:</strong> <?php _esc($meta['cta']) ?></p>
                                            <?php } ?>
                                            <?php if (!empty($hashtags)) { ?>
                                                <p class="margin-bottom-10"><strong><?php _e('Hashtags') ?>:</strong> <?php _esc($hashtags) ?></p>
                                            <?php } ?>
                                            <?php if (!empty($meta['reel_script'])) { ?>
                                                <p class="margin-bottom-10"><strong><?php _e('Reel Script') ?>:</strong> <?php _esc(implode(' | ', $meta['reel_script'])) ?></p>
                                            <?php } ?>
                                            <?php if (!empty($meta['asset']['title'])) { ?>
                                                <p class="margin-bottom-0"><strong><?php _e('Asset') ?>:</strong> <?php _esc($meta['asset']['title']) ?></p>
                                            <?php } ?>
                                            <?php if (!empty($meta['asset']['remote_provider'])) { ?>
                                                <p class="margin-bottom-0"><strong><?php _e('Asset Source') ?>:</strong> <?php _esc(ucfirst($meta['asset']['remote_provider'])) ?></p>
                                            <?php } ?>
                                            <?php if (!empty($meta['asset']['remote_provider']) && $meta['asset']['remote_provider'] === 'unsplash' && !empty($meta['asset']['remote_author'])) { ?>
                                                <p class="margin-bottom-10">
                                                    <small>
                                                        <?php _e('Photo by') ?>
                                                        <?php if (!empty($meta['asset']['remote_author_url'])) { ?>
                                                            <a href="<?php echo _esc($meta['asset']['remote_author_url'], 0); ?>" target="_blank" rel="nofollow noopener"><?php _esc($meta['asset']['remote_author']) ?></a>
                                                        <?php } else { ?>
                                                            <?php _esc($meta['asset']['remote_author']) ?>
                                                        <?php } ?>
                                                        <?php _e('on') ?>
                                                        <?php if (!empty($meta['asset']['remote_page_url'])) { ?>
                                                            <a href="<?php echo _esc($meta['asset']['remote_page_url'], 0); ?>" target="_blank" rel="nofollow noopener">Unsplash</a>
                                                        <?php } else { ?>
                                                            Unsplash
                                                        <?php } ?>
                                                    </small>
                                                </p>
                                            <?php } ?>
                                            <?php if (!empty($design['headline_font_key']) || !empty($design['background_tone'])) { ?>
                                                <p class="margin-bottom-10"><strong><?php _e('Design') ?>:</strong>
                                                    <?php _esc(!empty($design['headline_font_key']) ? $design['headline_font_key'] : ''); ?>
                                                    <?php if (!empty($design['body_font_key'])) { ?> / <?php _esc($design['body_font_key']) ?><?php } ?>
                                                    <?php if (!empty($design['headline_size'])) { ?>, <?php _esc($design['headline_size']) ?>px<?php } ?>
                                                    <?php if (!empty($design['background_tone'])) { ?>, <?php _esc($design['background_tone']) ?><?php } ?>
                                                </p>
                                            <?php } ?>
                                            <?php
                                            $captionExport = $post['caption'];
                                            if (!empty($hashtags)) {
                                                $captionExport .= "\n\n" . $hashtags;
                                            }
                                            $downloadUrl = (!empty($videoUrl) && $post['post_type'] === 'reel') ? $videoUrl : $previewUrl;
                                            $downloadLabel = (!empty($videoUrl) && $post['post_type'] === 'reel') ? __('Download Reel') : __('Download Post');
                                            ?>
                                            <div class="social-post-actions margin-top-15">
                                                <button type="button" class="social-action-btn social-vote-btn<?php echo (int) $post['vote_value'] === 1 ? ' is-active' : ''; ?>" data-id="<?php _esc($post['id']) ?>" data-vote="1" title="<?php _e('Thumbs up') ?>" aria-label="<?php _e('Thumbs up') ?>"><i class="fa fa-thumbs-up"></i></button>
                                                <button type="button" class="social-action-btn social-vote-btn<?php echo (int) $post['vote_value'] === -1 ? ' is-active' : ''; ?>" data-id="<?php _esc($post['id']) ?>" data-vote="-1" title="<?php _e('Thumbs down and regenerate') ?>" aria-label="<?php _e('Thumbs down and regenerate') ?>"><i class="fa fa-thumbs-down"></i></button>
                                                <button type="button" class="social-action-btn social-regenerate-btn" data-id="<?php _esc($post['id']) ?>" title="<?php _e('Regenerate image') ?>" aria-label="<?php _e('Regenerate image') ?>"><i class="fa fa-refresh"></i></button>
                                                <a href="<?php echo $downloadUrl; ?>" class="social-action-btn" download title="<?php _esc($downloadLabel) ?>" aria-label="<?php _esc($downloadLabel) ?>"><i class="fa fa-download"></i></a>
                                                <a href="#" class="social-action-btn download-caption" data-title="<?php _esc($post['title']) ?>" data-caption="<?php _esc($captionExport) ?>" title="<?php _e('Download Caption') ?>" aria-label="<?php _e('Download Caption') ?>"><i class="fa fa-file-text-o"></i></a>
                                                <?php if (!empty($videoUrl) && $post['post_type'] === 'reel') { ?>
                                                    <a href="<?php echo $videoUrl; ?>" class="social-action-btn" target="_blank" title="<?php _e('Open Reel Video') ?>" aria-label="<?php _e('Open Reel Video') ?>"><i class="fa fa-play"></i></a>
                                                    <a href="<?php echo $videoUrl; ?>" class="social-action-btn" download title="<?php _e('Download Reel Video') ?>" aria-label="<?php _e('Download Reel Video') ?>"><i class="fa fa-film"></i></a>
                                                    <a href="<?php echo $previewUrl; ?>" class="social-action-btn" download title="<?php _e('Download Cover') ?>" aria-label="<?php _e('Download Cover') ?>"><i class="fa fa-image"></i></a>
                                                <?php } ?>
                                                <a href="#" class="social-action-btn social-share-btn" title="<?php _e('Share') ?>" aria-label="<?php _e('Share') ?>"><i class="fa fa-share-alt"></i></a>
                                                <a href="#" class="social-action-btn social-action-danger quick-delete" data-id="<?php _esc($post['id']) ?>" data-action="delete_image" title="<?php _e('Delete') ?>" aria-label="<?php _e('Delete') ?>"><i class="fa fa-trash-o"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (empty($posts_to_render)) { ?>
                            <div class="col-12">
                                <div class="notification notice margin-bottom-0">
                                    <?php echo !empty($selected_campaign)
                                        ? __('Generate from this saved campaign and Atlas will keep the resulting posts grouped here.')
                                        : __('Generate your first campaign and Atlas will show the resulting posts here.'); ?>
                                </div>
                            </div>
                        <?php } ?>
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
    .social-post-card .social-post-preview img {
        width: 100%;
        border-radius: 12px 12px 0 0;
        display: block;
    }
    .social-post-card .social-post-preview video {
        width: 100%;
        border-radius: 12px 12px 0 0;
        display: block;
        background: #000;
    }
    .social-post-card .social-post-body {
        background: #fff;
    }
    .social-campaign-summary {
        background: #f6f2e8;
        border: 1px solid #e7dcc8;
        border-radius: 12px;
        padding: 16px 18px;
        color: #514735;
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
    .social-action-btn.is-active {
        background: #111;
        color: #fff;
        border-color: #111;
    }
    .social-overlay-input {
        min-height: 78px;
        resize: vertical;
    }
    .social-overlay-actions {
        margin-top: 10px;
    }
    .atlas-campaign-detail-box {
        border: 1px solid #e9dfcf;
    }
    .atlas-campaign-setup-hero {
        display: grid;
        grid-template-columns: minmax(0, 1.35fr) 290px;
        gap: 20px;
        align-items: start;
    }
    .atlas-campaign-setup-card {
        border-radius: 18px;
        padding: 16px;
        margin-bottom: 14px;
        border: 1px solid rgba(37, 31, 22, 0.08);
        background: linear-gradient(180deg, #201d1a 0%, #31271d 100%);
        box-shadow: 0 18px 42px rgba(26, 21, 15, 0.16);
    }
    .atlas-campaign-setup-card strong {
        display: block;
        color: #fff;
        font-size: 15px;
        margin-bottom: 6px;
        letter-spacing: -0.02em;
    }
    .atlas-campaign-setup-card span {
        display: block;
        color: rgba(255,255,255,.75);
        font-size: 12.5px;
        line-height: 1.7;
    }
    .atlas-campaign-pill-row {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .atlas-campaign-pill {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 999px;
        background: #f6f2e8;
        border: 1px solid #e8dcc8;
        color: #5f523f;
        font-size: 13px;
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
    @media (max-width: 991px) {
        .atlas-campaign-setup-hero {
            grid-template-columns: 1fr;
        }
    }
</style>
<script>
    (function () {
        var campaignCatalog = <?php echo json_encode($campaign_catalog); ?>;
        var select = document.querySelector('select[name="campaign_type"]');
        var summary = document.querySelector('.social-campaign-summary-text');

        if (!select || !summary) {
            return;
        }

        function renderCampaignSummary() {
            var key = select.value;
            if (!key || !campaignCatalog[key]) {
                summary.textContent = 'Choose a campaign type to see its goal, focus, content style, and best-use guidance.';
                return;
            }

            var item = campaignCatalog[key];
            summary.innerHTML =
                '<strong>Goal:</strong> ' + item.goal +
                '<br><strong>Focus:</strong> ' + item.focus.join(' | ') +
                '<br><strong>Content:</strong> ' + item.content_examples.join(' | ') +
                '<br><strong>When to use:</strong> ' + item.when_to_use.join(' | ');
        }

        select.addEventListener('change', renderCampaignSummary);
        renderCampaignSummary();
    })();
</script>
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH . '/overall_footer_dashboard.php';
