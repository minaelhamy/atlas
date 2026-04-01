<?php
overall_header(__("Company Intelligence"));
$companyName = !empty($social_profile['company_name']) ? $social_profile['company_name'] : 'Atlas';
$websiteSnapshot = !empty($social_profile['website_snapshot']) && is_array($social_profile['website_snapshot']) ? $social_profile['website_snapshot'] : [];
$moodboardImages = !empty($social_profile['moodboard_images']) && is_array($social_profile['moodboard_images']) ? $social_profile['moodboard_images'] : [];
$brandColors = !empty($social_profile['brand_colors']) ? implode("\n", $social_profile['brand_colors']) : '';
$topProblemsSolved = !empty($social_profile['top_problems_solved']) ? implode("\n", $social_profile['top_problems_solved']) : '';
$uniqueSellingPoints = !empty($social_profile['unique_selling_points']) ? implode("\n", $social_profile['unique_selling_points']) : '';
$referenceBrands = !empty($social_profile['reference_brands']) ? implode("\n", $social_profile['reference_brands']) : '';
$competitors = !empty($social_profile['competitors']) ? implode("\n", $social_profile['competitors']) : '';
?>
<div class="dashboard-container">
    <?php include_once TEMPLATE_PATH . '/dashboard_sidebar.php'; ?>
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner atlas-workflow-shell">
            <?php print_adsense_code('header_bottom'); ?>

            <div class="dashboard-headline">
                <h3><?php _e("Company Intelligence") ?></h3>
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Company Intelligence") ?></li>
                    </ul>
                </nav>
            </div>

            <div class="atlas-wizard-card margin-bottom-24">
                <div class="atlas-wizard-header">
                    <div>
                        <span class="atlas-workflow-eyebrow"><?php _e("Tell us about your business") ?></span>
                        <h2><?php _e("Build the company profile Atlas will use everywhere") ?></h2>
                        <p><?php _e("Atlas uses this profile for AI agents, campaign generation, Instagram grids, and brand-aware content decisions.") ?></p>
                    </div>
                    <div class="atlas-stepper atlas-stepper-compact atlas-company-stepper">
                        <span class="active" data-step-target="1"><?php _e("Your website") ?></span>
                        <span data-step-target="2"><?php _e("Business info") ?></span>
                        <span data-step-target="3"><?php _e("Inspiration") ?></span>
                        <span data-step-target="4"><?php _e("Review") ?></span>
                    </div>
                </div>
            </div>

            <form method="post" enctype="multipart/form-data" id="company-intelligence-form">
                <input type="hidden" name="website_snapshot_json" id="website_snapshot_json" value="<?php echo !empty($websiteSnapshot) ? _esc(json_encode($websiteSnapshot), 0) : ''; ?>">
                <input type="hidden" name="website_extracted_at" id="website_extracted_at" value="<?php _esc(!empty($social_profile['website_extracted_at']) ? $social_profile['website_extracted_at'] : '') ?>">
                <input type="hidden" name="existing_moodboard_images" id="existing_moodboard_images" value="<?php _esc(implode("\n", $moodboardImages)) ?>">

                <div class="atlas-company-step-panel active" data-step-panel="1">
                    <div class="dashboard-box atlas-wizard-form-card margin-top-0">
                        <div class="headline">
                            <h3><?php _e("Your Website") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <div class="atlas-wizard-inline-note margin-bottom-25">
                                <strong><?php _e("AI extract") ?>:</strong>
                                <?php _e("Paste your website and Atlas will pull positioning, ICP, key problems, and USPs you can edit before saving.") ?>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="submit-field">
                                        <h5><?php _e("Company Website") ?></h5>
                                        <input type="text" class="with-border" name="company_website" id="company_website" value="<?php _esc($social_profile['company_website']) ?>" placeholder="https://yourwebsite.com">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="submit-field">
                                        <h5>&nbsp;</h5>
                                        <a href="#" class="button atlas-primary-action atlas-extract-button"><?php _e("Extract with AI") ?></a>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="submit-field">
                                        <h5><?php _e("Company Name") ?></h5>
                                        <input type="text" class="with-border" name="company_name" id="company_name" value="<?php _esc($social_profile['company_name']) ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="submit-field">
                                        <h5><?php _e("Industry") ?></h5>
                                        <input type="text" class="with-border" name="company_industry" id="company_industry" value="<?php _esc($social_profile['company_industry']) ?>" placeholder="<?php _e('eCommerce, SaaS, agency, fintech...') ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="submit-field">
                                <h5><?php _e("Company Description") ?></h5>
                                <textarea class="with-border" rows="4" name="company_description" id="company_description"><?php _esc($social_profile['company_description']) ?></textarea>
                            </div>

                            <div class="submit-field">
                                <h5><?php _e("Ideal Customer Profile (ICP)") ?></h5>
                                <textarea class="with-border" rows="3" name="ideal_customer_profile" id="ideal_customer_profile"><?php _esc($social_profile['ideal_customer_profile']) ?></textarea>
                            </div>

                            <div class="submit-field atlas-chip-editor" data-label="<?php _e("Add a problem") ?>">
                                <h5><?php _e("Top Problems You Solve") ?></h5>
                                <textarea class="with-border atlas-chip-source" rows="2" name="top_problems_solved" id="top_problems_solved"><?php _esc($topProblemsSolved) ?></textarea>
                            </div>

                            <div class="submit-field atlas-chip-editor" data-label="<?php _e("Add a USP") ?>">
                                <h5><?php _e("Unique Selling Points") ?></h5>
                                <textarea class="with-border atlas-chip-source" rows="2" name="unique_selling_points" id="unique_selling_points"><?php _esc($uniqueSellingPoints) ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="atlas-company-step-panel" data-step-panel="2">
                    <div class="dashboard-box atlas-wizard-form-card margin-top-0">
                        <div class="headline">
                            <h3><?php _e("Business Info") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="submit-field">
                                        <h5><?php _e("Founder Name") ?></h5>
                                        <input type="text" class="with-border" name="founder_name" value="<?php _esc($social_profile['founder_name']) ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="submit-field">
                                        <h5><?php _e("Founder Title") ?></h5>
                                        <input type="text" class="with-border" name="founder_title" value="<?php _esc($social_profile['founder_title']) ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="submit-field">
                                        <h5><?php _e("Instagram Handle") ?></h5>
                                        <input type="text" class="with-border" name="instagram_handle" value="<?php _esc($social_profile['instagram_handle']) ?>" placeholder="@yourbrand">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="submit-field">
                                        <h5><?php _e("Company Logo") ?></h5>
                                        <input type="file" class="with-border" name="company_logo" accept="image/*">
                                    </div>
                                </div>
                            </div>

                            <div class="submit-field atlas-chip-editor" data-label="<?php _e("Add a color") ?>">
                                <h5><?php _e("Brand Colors") ?></h5>
                                <textarea class="with-border atlas-chip-source" rows="2" name="brand_colors" id="brand_colors" placeholder="#111111&#10;#EDE8DE"><?php _esc($brandColors) ?></textarea>
                            </div>

                            <div class="submit-field">
                                <h5><?php _e("Visual Mood") ?></h5>
                                <div class="atlas-pill-selector">
                                    <?php foreach (['Dark editorial', 'Warm & minimal', 'Documentary grid', 'Soft lifestyle', 'Bold & direct'] as $mood) { ?>
                                        <label class="atlas-pill-check">
                                            <input type="checkbox" name="visual_mood[]" value="<?php _esc($mood) ?>" <?php echo in_array($mood, $social_profile['visual_mood'], true) ? 'checked' : ''; ?>>
                                            <span><?php _esc($mood) ?></span>
                                        </label>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="submit-field">
                                <h5><?php _e("Tone of Voice") ?></h5>
                                <div class="atlas-pill-selector">
                                    <?php foreach (['Bold & direct', 'Educational', 'Playful', 'Inspirational', 'Grounded / real', 'Professional', 'Conversational', 'Minimal / quiet'] as $tone) { ?>
                                        <label class="atlas-pill-check">
                                            <input type="checkbox" name="tone_attributes[]" value="<?php _esc($tone) ?>" <?php echo in_array($tone, $social_profile['tone_attributes'], true) ? 'checked' : ''; ?>>
                                            <span><?php _esc($tone) ?></span>
                                        </label>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="submit-field">
                                <h5><?php _e("Brand Voice Notes") ?></h5>
                                <textarea class="with-border" rows="3" name="brand_voice"><?php _esc($social_profile['brand_voice']) ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="submit-field atlas-chip-editor" data-label="<?php _e("Add a reference") ?>">
                                        <h5><?php _e("Reference Brands") ?></h5>
                                        <textarea class="with-border atlas-chip-source" rows="3" name="reference_brands" id="reference_brands" placeholder="https://brand.com&#10;@brandhandle"><?php _esc($referenceBrands) ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="submit-field atlas-chip-editor" data-label="<?php _e("Add a competitor") ?>">
                                        <h5><?php _e("Competitors") ?></h5>
                                        <textarea class="with-border atlas-chip-source" rows="3" name="competitors" id="competitors"><?php _esc($competitors) ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="submit-field">
                                <h5><?php _e("Competitor Notes") ?></h5>
                                <textarea class="with-border" rows="3" name="competitor_notes"><?php _esc($social_profile['competitor_notes']) ?></textarea>
                            </div>

                            <div class="submit-field">
                                <h5><?php _e("Moodboard / Inspiration") ?></h5>
                                <input type="file" class="with-border" name="moodboard_images[]" multiple accept="image/*">
                                <?php if (!empty($moodboardImages)) { ?>
                                    <div class="atlas-moodboard-preview">
                                        <?php foreach ($moodboardImages as $moodboardImage) { ?>
                                            <span><img src="<?php echo _esc($config['site_url'], 0) . 'storage/company/' . $moodboardImage; ?>" alt=""></span>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="atlas-company-step-panel" data-step-panel="3">
                    <div class="dashboard-box atlas-wizard-form-card margin-top-0">
                        <div class="headline">
                            <h3><?php _e("Inspiration") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <div class="atlas-dashboard-intelligence-grid">
                                <div class="atlas-dashboard-intelligence-card">
                                    <span class="atlas-dashboard-label"><?php _e("Website signals") ?></span>
                                    <p id="ci-website-signals"><?php _esc(!empty($websiteSnapshot['description']) ? $websiteSnapshot['description'] : __('No website extraction yet. Use Extract with AI in step 1.')) ?></p>
                                </div>
                                <div class="atlas-dashboard-intelligence-card">
                                    <span class="atlas-dashboard-label"><?php _e("Stored intelligence") ?></span>
                                    <p><?php _esc(!empty($company_intelligence['company_summary']) ? $company_intelligence['company_summary'] : __('Save and refresh to build your company intelligence summary.')) ?></p>
                                </div>
                                <div class="atlas-dashboard-intelligence-card">
                                    <span class="atlas-dashboard-label"><?php _e("Reference brands") ?></span>
                                    <p><?php _esc(!empty($referenceBrands) ? str_replace("\n", ' | ', $referenceBrands) : __('Add a few brands you want Atlas to learn from.')) ?></p>
                                </div>
                                <div class="atlas-dashboard-intelligence-card">
                                    <span class="atlas-dashboard-label"><?php _e("Visual direction") ?></span>
                                    <p><?php _esc(!empty($social_profile['visual_mood']) ? implode(' | ', $social_profile['visual_mood']) : __('Choose the visual mood, colors, and moodboard inputs in Business Info.')) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="atlas-company-step-panel" data-step-panel="4">
                    <div class="dashboard-box atlas-wizard-form-card margin-top-0">
                        <div class="headline">
                            <h3><?php _e("Review") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <div class="notification notice margin-bottom-20"><?php _e("Review the profile Atlas will use for AI agents, campaign generation, and Instagram grids.") ?></div>
                            <div class="atlas-dashboard-intelligence-grid">
                                <div class="atlas-dashboard-intelligence-card">
                                    <span class="atlas-dashboard-label"><?php _e("Website") ?></span>
                                    <p><?php _esc(!empty($social_profile['company_website']) ? $social_profile['company_website'] : __('No website added yet.')) ?></p>
                                </div>
                                <div class="atlas-dashboard-intelligence-card">
                                    <span class="atlas-dashboard-label"><?php _e("ICP") ?></span>
                                    <p><?php _esc(!empty($social_profile['ideal_customer_profile']) ? $social_profile['ideal_customer_profile'] : __('No ICP added yet.')) ?></p>
                                </div>
                                <div class="atlas-dashboard-intelligence-card">
                                    <span class="atlas-dashboard-label"><?php _e("Problems Solved") ?></span>
                                    <p><?php _esc(!empty($social_profile['top_problems_solved']) ? implode(' | ', $social_profile['top_problems_solved']) : __('No problem statements yet.')) ?></p>
                                </div>
                                <div class="atlas-dashboard-intelligence-card">
                                    <span class="atlas-dashboard-label"><?php _e("USPs") ?></span>
                                    <p><?php _esc(!empty($social_profile['unique_selling_points']) ? implode(' | ', $social_profile['unique_selling_points']) : __('No USPs yet.')) ?></p>
                                </div>
                                <div class="atlas-dashboard-intelligence-card">
                                    <span class="atlas-dashboard-label"><?php _e("Tone & Mood") ?></span>
                                    <p><?php _esc(implode(' | ', array_filter([implode(', ', $social_profile['tone_attributes']), implode(', ', $social_profile['visual_mood'])]))) ?></p>
                                </div>
                                <div class="atlas-dashboard-intelligence-card">
                                    <span class="atlas-dashboard-label"><?php _e("Brand Colors") ?></span>
                                    <p><?php _esc(!empty($social_profile['brand_colors']) ? implode(' | ', $social_profile['brand_colors']) : __('No brand colors chosen yet.')) ?></p>
                                </div>
                            </div>

                            <div class="dashboard-box intelligence-panel-box margin-top-20 margin-bottom-0">
                                <div class="headline">
                                    <h3><i class="icon-feather-activity"></i> <?php _e("Company Intelligence") ?></h3>
                                </div>
                                <div class="content with-padding">
                                    <p class="margin-bottom-10 intelligence-refreshed-at">
                                        <strong><?php _e("Last Refreshed") ?>:</strong>
                                        <span><?php _esc(!empty($company_intelligence['refreshed_at']) ? $company_intelligence['refreshed_at'] : __('Not generated yet')) ?></span>
                                    </p>
                                    <div class="company-intelligence-body">
                                        <div class="margin-bottom-15">
                                            <strong><?php _e("Company Summary") ?></strong>
                                            <p class="margin-top-5 intelligence-company-summary"><?php _esc(!empty($company_intelligence['company_summary']) ? $company_intelligence['company_summary'] : __('No summary yet.')) ?></p>
                                        </div>
                                        <div class="margin-bottom-15">
                                            <strong><?php _e("Market Research") ?></strong>
                                            <p class="margin-top-5 intelligence-market-research"><?php _esc(!empty($company_intelligence['market_research']) ? $company_intelligence['market_research'] : __('No market research yet.')) ?></p>
                                        </div>
                                        <div class="margin-bottom-15">
                                            <strong><?php _e("Competitive Edges") ?></strong>
                                            <p class="margin-top-5 intelligence-competitive-edges"><?php _esc(!empty($company_intelligence['competitive_edges']) ? $company_intelligence['competitive_edges'] : __('No competitive edge summary yet.')) ?></p>
                                        </div>
                                        <div class="margin-bottom-0">
                                            <strong><?php _e("Strategic Guidance") ?></strong>
                                            <p class="margin-top-5 intelligence-strategic-guidance"><?php _esc(!empty($company_intelligence['strategic_guidance']) ? $company_intelligence['strategic_guidance'] : __('No strategic guidance yet.')) ?></p>
                                        </div>
                                    </div>
                                    <div class="margin-top-20">
                                        <a href="#" class="button ripple-effect intelligence-refresh-btn"><?php _e("Refresh Now") ?></a>
                                    </div>
                                </div>
                            </div>

                            <?php if(!empty($social_error)){ _esc($social_error); } ?>
                        </div>
                    </div>
                </div>

                <div class="atlas-step-actions">
                    <a href="#" class="button gray atlas-step-back"><?php _e("Back") ?></a>
                    <a href="#" class="button atlas-step-next"><?php _e("Save & continue") ?></a>
                    <button type="submit" name="company-intelligence-submit" class="button atlas-primary-action atlas-step-submit"><?php _e("Save profile") ?></button>
                </div>
            </form>

            <?php print_adsense_code('footer_top'); ?>
            <div class="dashboard-footer-spacer"></div>
            <div class="small-footer margin-top-15">
                <div class="footer-copyright"><?php _esc($config['copyright_text']); ?></div>
                <ul class="footer-social-links">
                    <?php
                    if($config['facebook_link'] != "") echo '<li><a href="'._esc($config['facebook_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a></li>';
                    if($config['twitter_link'] != "") echo '<li><a href="'._esc($config['twitter_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a></li>';
                    if($config['instagram_link'] != "") echo '<li><a href="'._esc($config['instagram_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-instagram"></i></a></li>';
                    if($config['youtube_link'] != "") echo '<li><a href="'._esc($config['youtube_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-youtube"></i></a></li>';
                    ?>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<?php ob_start(); ?>
<style>
    .atlas-company-step-panel { display:none; }
    .atlas-company-step-panel.active { display:block; }
    .atlas-chip-list, .atlas-moodboard-preview { display:flex; flex-wrap:wrap; gap:10px; margin-top:12px; }
    .atlas-chip { display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; background:#eef7ee; border:1px solid #d8ead8; font-size:13px; color:#4f5c4f; }
    .atlas-chip button { border:0; background:transparent; color:#7d857d; padding:0; line-height:1; }
    .atlas-pill-selector { display:flex; flex-wrap:wrap; gap:10px; }
    .atlas-pill-check input { display:none; }
    .atlas-pill-check span { display:inline-flex; align-items:center; min-height:40px; padding:10px 14px; border-radius:999px; border:1px solid #e7e1d6; background:#fff; color:#5d584e; font-size:14px; }
    .atlas-pill-check input:checked + span { background:#f4eefb; border-color:#d8c6f2; color:#693d93; }
    .atlas-step-actions { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-top:22px; }
    .atlas-step-submit { display:none; }
    .atlas-moodboard-preview span { width:72px; height:72px; border-radius:16px; overflow:hidden; background:#f5f1e7; }
    .atlas-moodboard-preview img { width:100%; height:100%; object-fit:cover; display:block; }
    @media (max-width: 767px) { .atlas-step-actions { flex-direction:column; align-items:stretch; } .atlas-step-actions .button { width:100%; } }
</style>
<script>
    (function ($) {
        var currentStep = 1;
        var totalSteps = 4;

        function showStep(step) {
            currentStep = Math.max(1, Math.min(totalSteps, step));
            $('.atlas-company-step-panel').removeClass('active');
            $('.atlas-company-step-panel[data-step-panel="' + currentStep + '"]').addClass('active');
            $('.atlas-company-stepper span').each(function () {
                var target = parseInt($(this).attr('data-step-target'), 10);
                $(this).toggleClass('active', target <= currentStep);
            });
            $('.atlas-step-back').toggle(currentStep > 1);
            $('.atlas-step-next').toggle(currentStep < totalSteps);
            $('.atlas-step-submit').toggle(currentStep === totalSteps);
        }

        function renderChipEditors() {
            $('.atlas-chip-editor').each(function () {
                var $editor = $(this);
                var $source = $editor.find('.atlas-chip-source');
                var values = $source.val().split(/\n+/).map(function (item) { return $.trim(item); }).filter(Boolean);
                $editor.find('.atlas-chip-list').remove();
                var $list = $('<div class="atlas-chip-list"></div>');
                values.forEach(function (value) {
                    $list.append('<span class="atlas-chip">' + $('<div>').text(value).html() + '<button type="button" data-chip-remove="' + $('<div>').text(value).html() + '">&times;</button></span>');
                });
                $source.after($list);
            });
        }

        $(document).on('click', '[data-chip-remove]', function () {
            var value = $(this).attr('data-chip-remove');
            var $editor = $(this).closest('.atlas-chip-editor');
            var $source = $editor.find('.atlas-chip-source');
            var values = $source.val().split(/\n+/).map(function (item) { return $.trim(item); }).filter(Boolean);
            values = values.filter(function (item) { return item !== value; });
            $source.val(values.join("\n"));
            renderChipEditors();
        });

        $('.atlas-step-back').on('click', function (e) { e.preventDefault(); showStep(currentStep - 1); });
        $('.atlas-step-next').on('click', function (e) { e.preventDefault(); showStep(currentStep + 1); });
        $('.atlas-company-stepper span').on('click', function () {
            showStep(parseInt($(this).attr('data-step-target'), 10));
        });

        $('.atlas-extract-button').on('click', function (e) {
            e.preventDefault();
            var website = $.trim($('#company_website').val());
            if (!website) {
                Snackbar.show({text: 'Add your website first.', pos: 'bottom-center', showAction: false, duration: 2500, textColor: '#fff', backgroundColor: '#d32f2f'});
                return;
            }
            var $btn = $(this).addClass('button-progress').prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: ajaxurl + '?action=extract_company_profile',
                data: {website: website},
                dataType: 'json',
                success: function (response) {
                    $btn.removeClass('button-progress').prop('disabled', false);
                    if (!response.success || !response.profile) {
                        Snackbar.show({text: response.error || 'Unable to extract website details right now.', pos: 'bottom-center', showAction: false, duration: 3000, textColor: '#fff', backgroundColor: '#d32f2f'});
                        return;
                    }
                    var profile = response.profile;
                    $('#company_name').val(profile.company_name || '');
                    $('#company_industry').val(profile.company_industry || '');
                    $('#company_description').val(profile.company_description || '');
                    $('#ideal_customer_profile').val(profile.ideal_customer_profile || '');
                    $('#top_problems_solved').val((profile.top_problems_solved || []).join("\n"));
                    $('#unique_selling_points').val((profile.unique_selling_points || []).join("\n"));
                    $('#website_snapshot_json').val(JSON.stringify(profile.website_snapshot || {}));
                    $('#website_extracted_at').val(profile.website_extracted_at || '');
                    $('#ci-website-signals').text((profile.website_snapshot && profile.website_snapshot.description) ? profile.website_snapshot.description : 'Website extracted successfully.');
                    renderChipEditors();
                    Snackbar.show({text: response.message || 'Website extracted successfully.', pos: 'bottom-center', showAction: false, duration: 2500, textColor: '#fff', backgroundColor: '#383838'});
                },
                error: function () {
                    $btn.removeClass('button-progress').prop('disabled', false);
                    Snackbar.show({text: 'Unable to extract website details right now.', pos: 'bottom-center', showAction: false, duration: 3000, textColor: '#fff', backgroundColor: '#d32f2f'});
                }
            });
        });

        $('.intelligence-refresh-btn').on('click', function (e) {
            e.preventDefault();
            var $btn = $(this).addClass('button-progress').prop('disabled', true);
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
                        Snackbar.show({text: response.message, pos: 'bottom-center', showAction: false, duration: 3000, textColor: '#fff', backgroundColor: '#383838'});
                    } else {
                        Snackbar.show({text: response.error || 'Unable to refresh company intelligence right now.', pos: 'bottom-center', showAction: false, duration: 3000, textColor: '#fff', backgroundColor: '#d32f2f'});
                    }
                },
                error: function () {
                    $btn.removeClass('button-progress').prop('disabled', false);
                    Snackbar.show({text: 'Unable to refresh company intelligence right now.', pos: 'bottom-center', showAction: false, duration: 3000, textColor: '#fff', backgroundColor: '#d32f2f'});
                }
            });
        });

        renderChipEditors();
        showStep(1);
    })(jQuery);
</script>
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH.'/overall_footer_dashboard.php';
