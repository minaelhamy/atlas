<?php
overall_header(__("Create a Campaign"));
$profileReady = !empty($social_profile['company_name']) && !empty($social_profile['company_description']);
?>
<div class="dashboard-container">
    <?php include_once TEMPLATE_PATH . '/dashboard_sidebar.php'; ?>
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner">
            <?php print_adsense_code('header_bottom'); ?>
            <div class="dashboard-headline">
                <h3 class="d-flex align-items-center">
                    <?php _e("Create a Campaign") ?>
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
                        <li><a href="<?php url("AI_IMAGES") ?>"><?php _e("Social Media Automation") ?></a></li>
                        <li><?php _e("Create a Campaign") ?></li>
                    </ul>
                </nav>
            </div>

            <?php if (!$profileReady) { ?>
                <div class="notification warning">
                    <?php _e("Complete your company profile in Account Settings before generating posts."); ?>
                    <a href="<?php url("ACCOUNT_SETTING") ?>"><strong><?php _e("Open Account Settings") ?></strong></a>
                </div>
            <?php } else { ?>
                <div class="notification notice">
                    <?php _e("Each run generates 9 strategic posts for one campaign goal. Atlas uses your company intelligence, market context, and selected funnel inputs to create a complete social funnel for publishing."); ?>
                </div>
            <?php } ?>

            <form id="ai_images" name="ai_images" method="post" action="#">
                <div class="dashboard-box margin-top-0">
                    <div class="headline">
                        <h3><i class="icon-feather-target"></i> <?php _e("Plan a Campaign") ?></h3>
                    </div>
                    <div class="content with-padding">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="submit-field">
                                    <h5><?php _e("Campaign Type") ?></h5>
                                    <select name="campaign_type" class="selectpicker with-border" data-size="10" <?php echo $profileReady ? '' : 'disabled'; ?> required>
                                        <option value=""><?php _e("Select campaign type") ?></option>
                                        <?php foreach ($campaign_catalog as $campaignKey => $campaignMeta) { ?>
                                            <option value="<?php _esc($campaignKey) ?>"><?php _esc($campaignMeta['label']) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="submit-field">
                                    <h5><?php _e("Funnel Stage") ?></h5>
                                    <select name="funnel_stage" class="selectpicker with-border" data-size="10" <?php echo $profileReady ? '' : 'disabled'; ?> required>
                                        <option value=""><?php _e("Select funnel stage") ?></option>
                                        <?php foreach ($funnel_stage_catalog as $stageKey => $stageLabel) { ?>
                                            <option value="<?php _esc($stageKey) ?>"><?php _esc($stageLabel) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="submit-field">
                                    <h5><?php _e("Primary Focus") ?></h5>
                                    <select name="focus_area" class="selectpicker with-border" data-size="10" <?php echo $profileReady ? '' : 'disabled'; ?> required>
                                        <option value=""><?php _e("Select focus area") ?></option>
                                        <?php foreach ($focus_options as $focusValue) { ?>
                                            <option value="<?php _esc($focusValue) ?>"><?php _esc($focusValue) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="submit-field">
                                    <h5><?php _e("Content Angle") ?></h5>
                                    <select name="content_angle" class="selectpicker with-border" data-size="10" <?php echo $profileReady ? '' : 'disabled'; ?> required>
                                        <option value=""><?php _e("Select content angle") ?></option>
                                        <?php foreach ($content_angle_options as $contentAngleValue) { ?>
                                            <option value="<?php _esc($contentAngleValue) ?>"><?php _esc($contentAngleValue) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="submit-field">
                                    <h5><?php _e("Use Case") ?></h5>
                                    <select name="use_case" class="selectpicker with-border" data-size="10" <?php echo $profileReady ? '' : 'disabled'; ?> required>
                                        <option value=""><?php _e("Select use case") ?></option>
                                        <?php foreach ($use_case_options as $useCaseValue) { ?>
                                            <option value="<?php _esc($useCaseValue) ?>"><?php _esc($useCaseValue) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="submit-field">
                            <h5><?php _e("Extra Notes") ?></h5>
                            <textarea name="description" class="with-border" rows="3" placeholder="<?php _e('Optional: add product, offer, launch context, audience nuance, or anything the generator should emphasize.') ?>" <?php echo $profileReady ? '' : 'disabled'; ?>></textarea>
                        </div>
                        <div class="social-campaign-summary margin-bottom-20">
                            <strong><?php _e("Campaign Strategy") ?>:</strong>
                            <p class="margin-top-10 margin-bottom-0 social-campaign-summary-text"><?php _e("Choose a campaign type to see its goal, focus, content style, and best-use guidance.") ?></p>
                        </div>
                        <small class="form-error"></small>
                        <button type="submit" name="submit" class="button ripple-effect" <?php echo $profileReady ? '' : 'disabled'; ?>>
                            <?php _e("Generate 9 Posts") ?> <i class="icon-feather-arrow-right"></i>
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
                    <h3><i class="icon-feather-grid"></i> <?php _e("Latest Campaign Posts") ?></h3>
                </div>
                <div class="content with-padding">
                    <div class="row" id="generated_images_wrapper">
                        <?php foreach ($social_posts as $post) {
                            $meta = !empty($post['metadata']) && is_array($post['metadata']) ? $post['metadata'] : [];
                            $hashtags = !empty($meta['hashtags']) && is_array($meta['hashtags']) ? implode(' ', $meta['hashtags']) : '';
                            $design = !empty($meta['design']) && is_array($meta['design']) ? $meta['design'] : [];
                            $debug = !empty($meta['debug']) && is_array($meta['debug']) ? $meta['debug'] : [];
                            $videoUrl = !empty($meta['rendered_video'])
                                ? _esc($config['site_url'], 0) . 'storage/social_posts/videos/' . $meta['rendered_video']
                                : (!empty($meta['source_video']) ? _esc($config['site_url'], 0) . 'storage/social_assets/' . $meta['source_video'] : '');
                            $previewUrl = _esc($config['site_url'], 0) . 'storage/social_posts/' . $post['preview_image'];
                            ?>
                            <div class="col-xl-4 col-md-6 margin-bottom-30">
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
                                            <p class="margin-bottom-10"><strong><?php _e('Overlay') ?>:</strong> <?php _esc($post['overlay_text']) ?></p>
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
                                            <?php if (!empty($debug)) { ?>
                                                <details class="margin-bottom-10">
                                                    <summary><strong><?php _e('Debug') ?></strong></summary>
                                                    <div class="margin-top-10">
                                                        <?php if (!empty($debug['generation_source'])) { ?>
                                                            <p class="margin-bottom-5"><strong><?php _e('Copy Source') ?>:</strong> <?php _esc($debug['generation_source']) ?></p>
                                                        <?php } ?>
                                                        <?php if (!empty($debug['openai']['error'])) { ?>
                                                            <p class="margin-bottom-5"><strong><?php _e('OpenAI Debug') ?>:</strong> <?php _esc($debug['openai']['attempt'] . ' - ' . $debug['openai']['error']) ?></p>
                                                        <?php } ?>
                                                        <?php if (!empty($debug['render']['background']['used_source'])) { ?>
                                                            <p class="margin-bottom-5"><strong><?php _e('Background Source') ?>:</strong> <?php _e('Uploaded asset used') ?></p>
                                                        <?php } elseif (!empty($debug['render']['background']['fallback_gradient'])) { ?>
                                                            <p class="margin-bottom-5"><strong><?php _e('Background Source') ?>:</strong> <?php _e('Fallback gradient used') ?></p>
                                                        <?php } ?>
                                                        <?php if (!empty($debug['source_video_used'])) { ?>
                                                            <p class="margin-bottom-5"><strong><?php _e('Video Source') ?>:</strong> <?php _e('Using original uploaded MP4') ?></p>
                                                        <?php } ?>
                                                        <?php if (!empty($debug['render']['background']['used_path'])) { ?>
                                                            <p class="margin-bottom-5"><strong><?php _e('Used Path') ?>:</strong> <code><?php _esc($debug['render']['background']['used_path']) ?></code></p>
                                                        <?php } ?>
                                                        <?php if (!empty($debug['render']['background']['remote_provider'])) { ?>
                                                            <p class="margin-bottom-5"><strong><?php _e('Remote Provider') ?>:</strong> <?php _esc($debug['render']['background']['remote_provider']) ?></p>
                                                        <?php } ?>
                                                        <?php if (!empty($debug['render']['background']['remote_url'])) { ?>
                                                            <p class="margin-bottom-5"><strong><?php _e('Remote URL') ?>:</strong> <code><?php _esc($debug['render']['background']['remote_url']) ?></code></p>
                                                        <?php } ?>
                                                        <?php if (isset($debug['render']['background']['remote_source_downloaded'])) { ?>
                                                            <p class="margin-bottom-5"><strong><?php _e('Remote Source Downloaded') ?>:</strong> <?php _esc($debug['render']['background']['remote_source_downloaded'] ? 'yes' : 'no') ?></p>
                                                        <?php } ?>
                                                        <?php if (!empty($debug['render']['background']['remote_source_path'])) { ?>
                                                            <p class="margin-bottom-5"><strong><?php _e('Remote Source Path') ?>:</strong> <code><?php _esc($debug['render']['background']['remote_source_path']) ?></code></p>
                                                        <?php } ?>
                                                        <?php if (!empty($debug['render']['background']['attempted_paths'])) { ?>
                                                            <p class="margin-bottom-5"><strong><?php _e('Attempted Paths') ?>:</strong> <code><?php _esc(implode(' | ', $debug['render']['background']['attempted_paths'])) ?></code></p>
                                                        <?php } ?>
                                                    </div>
                                                </details>
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
