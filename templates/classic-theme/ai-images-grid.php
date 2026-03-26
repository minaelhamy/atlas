<?php
overall_header(__("Create an Instagram Grid"));
$profileReady = !empty($social_profile['company_name']) && !empty($social_profile['company_description']);
$companyName = !empty($social_profile['company_name']) ? $social_profile['company_name'] : 'Atlas';
$instagramHandle = !empty($social_profile['instagram_handle'])
    ? ltrim($social_profile['instagram_handle'], '@')
    : preg_replace('/[^a-z0-9]+/i', '', strtolower($companyName));
$companyBio = !empty($social_profile['company_description']) ? $social_profile['company_description'] : __('Your company description will appear here.');
$companyWebsite = !empty($social_profile['company_website']) ? $social_profile['company_website'] : '';
$companyLogo = !empty($social_profile['company_logo']) ? $config['site_url'] . 'storage/company/' . $social_profile['company_logo'] : '';
?>
<div class="dashboard-container">
    <?php include_once TEMPLATE_PATH . '/dashboard_sidebar.php'; ?>
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner">
            <?php print_adsense_code('header_bottom'); ?>

            <div class="dashboard-headline">
                <h3 class="d-flex align-items-center">
                    <?php _e("Create an Instagram Grid") ?>
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
                        <li><?php _e("Create an Instagram Grid") ?></li>
                    </ul>
                </nav>
            </div>

            <?php if (!$profileReady) { ?>
                <div class="notification warning">
                    <?php _e("Complete your company profile in Account Settings before generating an Instagram grid."); ?>
                    <a href="<?php url("ACCOUNT_SETTING") ?>"><strong><?php _e("Open Account Settings") ?></strong></a>
                </div>
            <?php } else { ?>
                <div class="notification notice">
                    <?php _e("Atlas generates 9 coordinated posts designed to work as one Instagram grid. It uses your company context, campaign strategy, and brand direction to build a sequence that should be posted in order.") ?>
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-xl-5 col-lg-6">
                    <form id="instagram_grid_form" method="post" action="#">
                        <div class="dashboard-box margin-top-0">
                            <div class="headline">
                                <h3><i class="icon-feather-grid"></i> <?php _e("Plan Your Grid") ?></h3>
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
                                    <h5><?php _e("Grid Style") ?></h5>
                                    <select name="grid_style" class="selectpicker with-border" data-size="10" <?php echo $profileReady ? '' : 'disabled'; ?>>
                                        <option value="auto"><?php _e("Auto select the best grid") ?></option>
                                        <?php foreach ($grid_catalog as $gridKey => $gridMeta) { ?>
                                            <option value="<?php _esc($gridKey) ?>"><?php _esc($gridMeta['label']) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="submit-field">
                                    <h5><?php _e("Extra Notes") ?></h5>
                                    <textarea name="description" class="with-border" rows="3" placeholder="<?php _e('Optional: add launch context, product focus, seasonal message, or any visual direction Atlas should follow.') ?>" <?php echo $profileReady ? '' : 'disabled'; ?>></textarea>
                                </div>

                                <div class="social-campaign-summary margin-bottom-15">
                                    <strong><?php _e("Campaign Strategy") ?>:</strong>
                                    <p class="margin-top-10 margin-bottom-0 social-campaign-summary-text"><?php _e("Choose a campaign type to see its goal, focus, content style, and best-use guidance.") ?></p>
                                </div>

                                <div class="social-campaign-summary social-grid-summary margin-bottom-20">
                                    <strong><?php _e("Grid Direction") ?>:</strong>
                                    <p class="margin-top-10 margin-bottom-0 social-grid-summary-text"><?php _e("Atlas can auto-pick the strongest grid system for your campaign, or you can select one manually.") ?></p>
                                </div>

                                <small class="form-error"></small>
                                <button type="submit" class="button ripple-effect" <?php echo $profileReady ? '' : 'disabled'; ?>>
                                    <?php _e("Generate 9 Grid Posts") ?> <i class="icon-feather-arrow-right"></i>
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
                </div>

                <div class="col-xl-7 col-lg-6">
                    <div class="dashboard-box margin-top-0 atlas-instagram-preview-box">
                        <div class="headline">
                            <h3><i class="icon-feather-smartphone"></i> <?php _e("Instagram Preview") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <div class="atlas-instagram-phone">
                                <div class="atlas-instagram-notch"></div>
                                <div class="atlas-instagram-status">
                                    <span>09:42</span>
                                    <span><i class="fa fa-signal"></i> <i class="fa fa-wifi"></i> <i class="fa fa-battery-full"></i></span>
                                </div>
                                <div class="atlas-instagram-profile">
                                    <div class="atlas-instagram-profile-top">
                                        <div class="atlas-instagram-avatar" id="instagram-profile-avatar">
                                            <?php if (!empty($companyLogo)) { ?>
                                                <img src="<?php echo $companyLogo; ?>" alt="<?php _esc($companyName) ?>">
                                            <?php } else { ?>
                                                <span><?php echo strtoupper(substr($companyName, 0, 1)); ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="atlas-instagram-stats">
                                            <div><strong id="instagram-post-count"><?php echo !empty($recent_grid_posts) ? count($recent_grid_posts) : 9; ?></strong><span><?php _e("Posts") ?></span></div>
                                            <div><strong>--</strong><span><?php _e("Followers") ?></span></div>
                                            <div><strong>--</strong><span><?php _e("Following") ?></span></div>
                                        </div>
                                    </div>
                                    <div class="atlas-instagram-meta">
                                        <h4 id="instagram-display-name"><?php _esc($companyName) ?></h4>
                                        <p class="atlas-instagram-handle" id="instagram-handle">@<?php _esc($instagramHandle) ?></p>
                                        <p id="instagram-bio"><?php _esc($companyBio) ?></p>
                                        <?php if (!empty($companyWebsite)) { ?>
                                            <a href="<?php echo _esc($companyWebsite, 0); ?>" target="_blank" rel="nofollow noopener" id="instagram-website"><?php _esc($companyWebsite) ?></a>
                                        <?php } else { ?>
                                            <span class="atlas-instagram-website-placeholder" id="instagram-website"><?php _e("Add your website in Account Settings") ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="atlas-instagram-actions">
                                        <span class="atlas-instagram-action primary"><?php _e("Follow") ?></span>
                                        <span class="atlas-instagram-action"><?php _e("Message") ?></span>
                                    </div>
                                </div>
                                <div class="atlas-instagram-tabs">
                                    <i class="fa fa-th"></i>
                                    <i class="fa fa-video-camera"></i>
                                    <i class="fa fa-user-o"></i>
                                </div>
                                <div class="atlas-instagram-grid" id="instagram-grid-preview">
                                    <?php if (!empty($recent_grid_posts)) { ?>
                                        <?php foreach ($recent_grid_posts as $gridPost) {
                                            $previewUrl = $config['site_url'] . 'storage/social_posts/' . $gridPost['preview_image']; ?>
                                            <div class="atlas-instagram-tile">
                                                <img src="<?php echo $previewUrl; ?>" alt="<?php _esc($gridPost['title']) ?>">
                                                <a href="<?php echo $previewUrl; ?>" class="atlas-instagram-tile-download" download aria-label="<?php echo sprintf(__('Download tile %d'), !empty($gridPost['metadata']['grid']['position']) ? (int)$gridPost['metadata']['grid']['position'] : 0); ?>">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <?php for ($tile = 0; $tile < 9; $tile++) { ?>
                                            <div class="atlas-instagram-tile atlas-instagram-tile-placeholder"></div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
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
                    if($config['facebook_link'] != "")
                        echo '<li><a href="'._esc($config['facebook_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a></li>';
                    if($config['twitter_link'] != "")
                        echo '<li><a href="'._esc($config['twitter_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a></li>';
                    if($config['instagram_link'] != "")
                        echo '<li><a href="'._esc($config['instagram_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-instagram"></i></a></li>';
                    if($config['linkedin_link'] != "")
                        echo '<li><a href="'._esc($config['linkedin_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-linkedin"></i></a></li>';
                    if($config['pinterest_link'] != "")
                        echo '<li><a href="'._esc($config['pinterest_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-pinterest"></i></a></li>';
                    if($config['youtube_link'] != "")
                        echo '<li><a href="'._esc($config['youtube_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-youtube"></i></a></li>';
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
        var campaignCatalog = <?php echo json_encode($campaign_catalog); ?>;
        var gridCatalog = <?php echo json_encode($grid_catalog); ?>;
        var $campaignSelect = $('select[name="campaign_type"]');
        var $gridSelect = $('select[name="grid_style"]');
        var $campaignSummary = $('.social-campaign-summary-text');
        var $gridSummary = $('.social-grid-summary-text');
        var $form = $('#instagram_grid_form');

        function escapeHtml(text) {
            return $('<div>').text(text || '').html();
        }

        function setProgress(value) {
            var safeValue = Math.max(0, Math.min(100, Math.round(value || 0)));
            $form.find('.social-generator-progress').stop(true, true).fadeIn(120);
            $form.find('.social-generator-progress-bar').css('width', safeValue + '%');
            $form.find('.social-generator-progress-text').text(safeValue + '%');
        }

        function resetProgress() {
            $form.find('.social-generator-progress').stop(true, true).fadeOut(180, function () {
                $form.find('.social-generator-progress-bar').css('width', '0%');
                $form.find('.social-generator-progress-text').text('0%');
            });
        }

        function animateValue(id, start, end, duration) {
            start = parseInt(start, 10);
            end = parseInt(end, 10);
            if (start === end) return;
            var range = end - start;
            var current = start;
            var increment = end > start ? 1 : -1;
            var stepTime = Math.max(10, Math.abs(Math.floor(duration / range)));
            var obj = document.getElementById(id);
            if (!obj) return;
            var timer = setInterval(function () {
                current += increment;
                obj.innerHTML = current;
                if (current === end) {
                    clearInterval(timer);
                }
            }, stepTime);
        }

        function renderCampaignSummary() {
            var key = $campaignSelect.val();
            if (!key || !campaignCatalog[key]) {
                $campaignSummary.text('Choose a campaign type to see its goal, focus, content style, and best-use guidance.');
                return;
            }
            var item = campaignCatalog[key];
            $campaignSummary.html(
                '<strong>Goal:</strong> ' + escapeHtml(item.goal) +
                '<br><strong>Focus:</strong> ' + escapeHtml(item.focus.join(' | ')) +
                '<br><strong>Content:</strong> ' + escapeHtml(item.content_examples.join(' | ')) +
                '<br><strong>When to use:</strong> ' + escapeHtml(item.when_to_use.join(' | '))
            );
        }

        function renderGridSummary() {
            var key = $gridSelect.val();
            if (!key || key === 'auto' || !gridCatalog[key]) {
                $gridSummary.text('Atlas can auto-pick the strongest grid system for your campaign, or you can select one manually.');
                return;
            }
            var item = gridCatalog[key];
            $gridSummary.html(
                '<strong>' + escapeHtml(item.label) + ':</strong> ' + escapeHtml(item.description)
            );
        }

        function renderProfile(profile, posts) {
            if (profile && profile.company_name) {
                $('#instagram-display-name').text(profile.company_name);
            }
            if (profile && profile.instagram_handle) {
                $('#instagram-handle').text('@' + String(profile.instagram_handle).replace(/^@/, ''));
            }
            if (profile && profile.company_description) {
                $('#instagram-bio').text(profile.company_description);
            }
            if (profile && typeof profile.company_website !== 'undefined') {
                if (profile.company_website) {
                    $('#instagram-website').replaceWith('<a href="' + escapeHtml(profile.company_website) + '" target="_blank" rel="nofollow noopener" id="instagram-website">' + escapeHtml(profile.company_website) + '</a>');
                } else {
                    $('#instagram-website').replaceWith('<span class="atlas-instagram-website-placeholder" id="instagram-website">Add your website in Account Settings</span>');
                }
            }
            if (profile && profile.company_logo) {
                $('#instagram-profile-avatar').html('<img src="' + escapeHtml(profile.company_logo) + '" alt="' + escapeHtml(profile.company_name || 'Company') + '">');
            }
            if (posts && posts.length) {
                $('#instagram-post-count').text(posts.length);
            }
        }

        function renderGrid(posts) {
            var gridHtml = '';

            $.each(posts, function (index, post) {
                gridHtml += '<div class="atlas-instagram-tile">' +
                    '<img src="' + escapeHtml(post.preview_image) + '" alt="' + escapeHtml(post.title) + '">' +
                    '<a href="' + escapeHtml(post.preview_image) + '" class="atlas-instagram-tile-download" download aria-label="Download tile ' + (index + 1) + '">' +
                        '<i class="fa fa-download"></i>' +
                    '</a>' +
                '</div>';
            });

            $('#instagram-grid-preview').html(gridHtml);
        }

        $campaignSelect.on('change', renderCampaignSummary);
        $gridSelect.on('change', renderGridSummary);
        renderCampaignSummary();
        renderGridSummary();

        $form.on('submit', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var data = new FormData(this);
            var $btn = $form.find('.button');
            var $error = $form.find('.form-error');
            var progressValue = 4;

            $btn.addClass('button-progress').prop('disabled', true);
            $error.slideUp();
            setProgress(progressValue);

            var progressTimer = window.setInterval(function () {
                if (progressValue < 40) {
                    progressValue += 2;
                } else if (progressValue < 68) {
                    progressValue += 1;
                } else if (progressValue < 84) {
                    progressValue += 1;
                }
                setProgress(progressValue);
            }, 520);

            $.ajax({
                type: 'POST',
                url: ajaxurl + '?action=generate_instagram_grid',
                data: data,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    window.clearInterval(progressTimer);
                    setProgress(100);
                    $btn.removeClass('button-progress').prop('disabled', false);

                    if (response.success) {
                        renderProfile(response.company_profile || {}, response.posts || []);
                        renderGrid(response.posts || []);
                        animateValue('quick-images-left', response.old_used_images, response.current_used_images, 1000);
                        Snackbar.show({
                            text: 'Instagram grid generated successfully.',
                            pos: 'bottom-center',
                            showAction: false,
                            actionText: 'Dismiss',
                            duration: 3000,
                            textColor: '#fff',
                            backgroundColor: '#383838'
                        });
                        window.setTimeout(function () {
                            resetProgress();
                        }, 500);
                    } else {
                        resetProgress();
                        $error.html(response.error).slideDown().focus();
                    }
                },
                error: function () {
                    window.clearInterval(progressTimer);
                    $btn.removeClass('button-progress').prop('disabled', false);
                    resetProgress();
                    $error.html('Unable to generate the Instagram grid right now. Please try again.').slideDown().focus();
                }
            });
        });
    })(jQuery);
</script>
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH . '/overall_footer_dashboard.php';
