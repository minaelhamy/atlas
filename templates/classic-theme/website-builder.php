<?php
overall_header(__("Your Website"));
$companyName = !empty($social_profile['company_name']) ? $social_profile['company_name'] : __('your business');
$selectedTemplateKey = !empty($selected_template['key']) ? $selected_template['key'] : '';
$businessTypeLabel = $selected_type === 'ecommerce' ? __('ecommerce') : __('service');
$websiteTemplateItems = !empty($website_templates) && is_array($website_templates) ? $website_templates : [];
?>
<div class="dashboard-container">
    <?php include_once TEMPLATE_PATH . '/dashboard_sidebar.php'; ?>
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner atlas-workflow-shell">
            <?php print_adsense_code('header_bottom'); ?>

            <div class="dashboard-headline">
                <h3><?php _e("Your Website") ?></h3>
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Your Website") ?></li>
                    </ul>
                </nav>
            </div>

            <div class="atlas-workflow-hero margin-bottom-30">
                <span class="atlas-workflow-eyebrow"><?php _e("Launch faster") ?></span>
                <h2><?php _e("Create a customer-ready website from your company intelligence") ?></h2>
                <p><?php echo sprintf(__('Atlas can turn %s into a polished %s website, styled to your brand and connected to Atlas checkout and wallet flows.'), _esc($companyName, 0), _esc($businessTypeLabel, 0)); ?></p>
            </div>

            <?php if (!$profile_ready) { ?>
                <div class="notification warning margin-bottom-25">
                    <p class="margin-bottom-10"><?php _e("Finish Company Intelligence first so Atlas has enough context to generate a strong website draft."); ?></p>
                    <?php if (!empty($profile_status['missing'])) { ?>
                        <p class="margin-bottom-10"><?php echo sprintf(__('Still missing: %s'), _esc(implode(', ', $profile_status['missing']), 0)); ?></p>
                    <?php } ?>
                    <a href="<?php url("COMPANY_INTELLIGENCE") ?>" class="button"><?php _e("Open Company Intelligence") ?></a>
                </div>
            <?php } ?>

            <?php if (!empty($website_error)) { ?>
                <div class="notification error margin-bottom-25"><?php _esc($website_error) ?></div>
            <?php } ?>

            <?php if (!empty($website_debug)) { ?>
                <div class="dashboard-box margin-bottom-25">
                    <div class="content with-padding">
                        <span class="atlas-workflow-eyebrow"><?php _e("Website builder debug") ?></span>
                        <div style="margin-top:12px;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px 18px;font-size:13px;color:#6f6658;">
                            <div><strong style="color:#1d1d1f;"><?php _e("Selected type") ?>:</strong> <?php _esc(!empty($website_debug['selected_type']) ? $website_debug['selected_type'] : ''); ?></div>
                            <div><strong style="color:#1d1d1f;"><?php _e("Template count") ?>:</strong> <?php _esc((string) (!empty($website_debug['template_count']) ? $website_debug['template_count'] : 0)); ?></div>
                            <div><strong style="color:#1d1d1f;"><?php _e("Selected template key") ?>:</strong> <?php _esc(!empty($website_debug['selected_template_key']) ? $website_debug['selected_template_key'] : ''); ?></div>
                            <div><strong style="color:#1d1d1f;"><?php _e("Profile ready") ?>:</strong> <?php _esc(!empty($website_debug['profile_ready']) ? 'yes' : 'no'); ?></div>
                        </div>
                        <?php if (!empty($website_debug['template_keys'])) { ?>
                            <div style="margin-top:14px;font-size:13px;color:#6f6658;">
                                <strong style="color:#1d1d1f;"><?php _e("Template keys") ?>:</strong>
                                <?php _esc(implode(', ', $website_debug['template_keys'])); ?>
                            </div>
                        <?php } ?>
                        <?php if (!empty($website_debug['missing_profile_fields'])) { ?>
                            <div style="margin-top:10px;font-size:13px;color:#6f6658;">
                                <strong style="color:#1d1d1f;"><?php _e("Missing profile fields") ?>:</strong>
                                <?php _esc(implode(', ', $website_debug['missing_profile_fields'])); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

            <?php if (!empty($existing_site)) { ?>
                <div class="dashboard-box margin-bottom-30 atlas-website-existing-site">
                    <div class="content with-padding">
                        <div class="atlas-website-existing-site-head">
                            <div>
                                <span class="atlas-workflow-eyebrow"><?php _e("Current draft") ?></span>
                                <h4 class="margin-bottom-5"><?php _esc($existing_site['site_name']) ?></h4>
                                <p class="margin-bottom-0"><?php echo sprintf(__('Template: %s. Site type: %s. Status: %s.'), _esc($existing_site['template']['title'], 0), _esc(ucfirst($existing_site['site_type']), 0), _esc(ucfirst($existing_site['status']), 0)); ?></p>
                            </div>
                            <div class="atlas-website-existing-site-actions">
                                <a href="<?php echo $link['YOUR_WEBSITE_EDITOR'] . '/' . $existing_site['id']; ?>" class="button ripple-effect"><?php _e("Open editor") ?></a>
                                <a href="<?php echo $link['YOUR_WEBSITE_DASHBOARD'] . '/' . $existing_site['id']; ?>" class="button gray"><?php _e("Open dashboard") ?></a>
                            </div>
                        </div>
                        <div class="atlas-website-existing-site-meta">
                            <span><strong><?php _e("Preview URL") ?>:</strong> <?php _esc($existing_site['subdomain']) ?></span>
                            <span><strong><?php _e("Payment mode") ?>:</strong> <?php _e("Atlas checkout + wallet") ?></span>
                            <span><strong><?php _e("Built from") ?>:</strong> <?php _e("Company Intelligence") ?></span>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="dashboard-box margin-bottom-30">
                <div class="content with-padding">
                    <div class="atlas-workflow-stepper">
                        <span class="atlas-workflow-step atlas-workflow-step-active"><?php _e("1. Template") ?></span>
                        <span class="atlas-workflow-step <?php echo !empty($selected_template) ? 'atlas-workflow-step-active' : ''; ?>"><?php _e("2. Launch details") ?></span>
                        <span class="atlas-workflow-step"><?php _e("3. Build & edit") ?></span>
                    </div>
                </div>
            </div>

            <div class="dashboard-box margin-bottom-30">
                <div class="content with-padding">
                    <div class="atlas-website-section-heading">
                        <div>
                            <span class="atlas-workflow-eyebrow"><?php _e("Choose a template") ?></span>
                            <h4 class="margin-bottom-5"><?php echo $selected_type === 'ecommerce' ? __('Pick one ecommerce template') : __('Pick one service template'); ?></h4>
                            <p class="margin-bottom-0"><?php echo sprintf(__('Based on your Company Intelligence, Atlas believes you need a %s website. Pick one of the two matching templates below and we will generate the first version for you.'), _esc($businessTypeLabel, 0)); ?></p>
                        </div>
                    </div>
                    <div style="margin-top:18px;margin-bottom:8px;color:#8a8275;font-size:13px;">
                        <?php echo sprintf(__('Templates loaded: %d'), count($websiteTemplateItems)); ?>
                    </div>
                    <?php if (!empty($websiteTemplateItems)) { ?>
                        <div style="display:flex;flex-direction:column;gap:16px;margin-top:16px;margin-bottom:24px;">
                            <?php foreach ($websiteTemplateItems as $template) { ?>
                                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:18px;padding:18px 20px;border:1px solid rgba(54,45,30,0.08);border-radius:22px;background:#fbf8f2;">
                                    <div style="min-width:0;">
                                        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:6px;">
                                            <strong style="font-size:18px;line-height:1.2;color:#1d1d1f;"><?php _esc($template['title']) ?></strong>
                                            <span style="display:inline-flex;align-items:center;justify-content:center;min-height:28px;padding:6px 10px;border-radius:999px;background:rgba(244,240,230,0.92);color:#9a9386;border:1px solid rgba(54,45,30,0.08);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;"><?php _esc($template['badge']) ?></span>
                                        </div>
                                        <p style="margin:0 0 10px;color:#6d665b;line-height:1.6;font-size:14px;"><?php _esc($template['description']) ?></p>
                                        <div style="color:#8a8275;font-size:12px;line-height:1.5;">
                                            <?php if (!empty($template['source'])) { ?>
                                                <div><strong style="color:#1d1d1f;"><?php _e("Source") ?>:</strong> <?php _esc($template['source']) ?></div>
                                            <?php } ?>
                                            <?php if (!empty($template['source_path'])) { ?>
                                                <div><strong style="color:#1d1d1f;"><?php _e("Primary view") ?>:</strong> <?php _esc($template['source_path']) ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div style="flex:0 0 auto;">
                                        <a href="<?php echo $link['YOUR_WEBSITE']; ?>?template=<?php _esc($template['key']); ?>" style="display:inline-flex;align-items:center;justify-content:center;min-height:44px;padding:0 18px;border-radius:14px;background:#1d1d1f;color:#fff;text-decoration:none;font-size:14px;font-weight:600;white-space:nowrap;"><?php _e("Choose template") ?></a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="margin-top-20" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:24px;align-items:stretch;">
                            <?php foreach ($websiteTemplateItems as $template) { ?>
                                <div>
                                    <a href="<?php echo $link['YOUR_WEBSITE']; ?>?template=<?php _esc($template['key']); ?>" style="display:block;height:100%;text-decoration:none;color:#1d1d1f;background:#fbf8f2;border:1px solid rgba(54,45,30,0.08);border-radius:28px;overflow:hidden;box-shadow:<?php echo $selectedTemplateKey === $template['key'] ? '0 18px 44px rgba(31,27,18,0.10)' : 'none'; ?>;">
                                        <div style="padding:26px;">
                                            <div style="border:1px solid rgba(54,45,30,0.08);border-radius:20px;background:rgba(255,255,255,0.92);overflow:hidden;">
                                                <div style="display:flex;align-items:center;gap:6px;padding:12px 14px;border-bottom:1px solid rgba(54,45,30,0.08);background:rgba(247,246,242,0.72);">
                                                    <span style="width:8px;height:8px;border-radius:50%;background:rgba(23,23,23,0.14);display:inline-block;"></span>
                                                    <span style="width:8px;height:8px;border-radius:50%;background:rgba(23,23,23,0.14);display:inline-block;"></span>
                                                    <span style="width:8px;height:8px;border-radius:50%;background:rgba(23,23,23,0.14);display:inline-block;"></span>
                                                </div>
                                                <div style="padding:16px;">
                                                    <?php
                                                    $heroBackground = 'linear-gradient(135deg, #171717 0%, #45413a 100%)';
                                                    if ($template['preview_theme'] === 'editorial') {
                                                        $heroBackground = 'linear-gradient(135deg, #54453d 0%, #b99d82 100%)';
                                                    } elseif ($template['preview_theme'] === 'studio') {
                                                        $heroBackground = 'linear-gradient(135deg, #8d7660 0%, #c4ae97 100%)';
                                                    } elseif ($template['preview_theme'] === 'local') {
                                                        $heroBackground = 'linear-gradient(135deg, #6f6558 0%, #c8b49f 100%)';
                                                    }
                                                    ?>
                                                    <div style="min-height:132px;border-radius:18px;padding:20px;display:flex;flex-direction:column;justify-content:flex-end;gap:6px;color:#fff;background:<?php echo $heroBackground; ?>;">
                                                        <strong style="font-size:22px;line-height:1;letter-spacing:-0.04em;"><?php _esc($template['title']) ?></strong>
                                                        <span style="font-size:13px;line-height:1.4;color:rgba(255,255,255,0.82);"><?php echo $template['type'] === 'ecommerce' ? __('Built for converting visitors into buyers') : __('Built for converting visitors into bookings'); ?></span>
                                                    </div>
                                                    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:12px;">
                                                        <?php foreach ($template['preview_sections'] as $section) { ?>
                                                            <span style="display:inline-flex;align-items:center;min-height:28px;padding:6px 10px;border-radius:999px;background:rgba(247,246,242,0.9);border:1px solid rgba(23,23,23,0.06);color:#6f6658;font-size:11px;font-weight:600;letter-spacing:0.01em;"><?php _esc($section) ?></span>
                                                        <?php } ?>
                                                    </div>
                                                    <div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;margin-top:12px;">
                                                        <span style="display:block;aspect-ratio:1/1;border-radius:14px;background:linear-gradient(180deg, rgba(247,246,242,0.95), rgba(236,229,216,0.9));border:1px solid rgba(23,23,23,0.05);"></span>
                                                        <span style="display:block;aspect-ratio:1/1;border-radius:14px;background:linear-gradient(180deg, rgba(247,246,242,0.95), rgba(236,229,216,0.9));border:1px solid rgba(23,23,23,0.05);"></span>
                                                        <span style="display:block;aspect-ratio:1/1;border-radius:14px;background:linear-gradient(180deg, rgba(247,246,242,0.95), rgba(236,229,216,0.9));border:1px solid rgba(23,23,23,0.05);"></span>
                                                        <span style="display:block;aspect-ratio:1/1;border-radius:14px;background:linear-gradient(180deg, rgba(247,246,242,0.95), rgba(236,229,216,0.9));border:1px solid rgba(23,23,23,0.05);"></span>
                                                        <span style="display:block;aspect-ratio:1/1;border-radius:14px;background:linear-gradient(180deg, rgba(247,246,242,0.95), rgba(236,229,216,0.9));border:1px solid rgba(23,23,23,0.05);"></span>
                                                        <span style="display:block;aspect-ratio:1/1;border-radius:14px;background:linear-gradient(180deg, rgba(247,246,242,0.95), rgba(236,229,216,0.9));border:1px solid rgba(23,23,23,0.05);"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:18px;margin-bottom:10px;">
                                                <span style="width:52px;height:52px;border-radius:18px;display:inline-flex;align-items:center;justify-content:center;background:rgba(244,240,230,0.95);color:#1d1d1f;font-size:21px;"><i class="<?php echo $template['type'] === 'ecommerce' ? 'icon-feather-shopping-bag' : 'icon-feather-calendar'; ?>"></i></span>
                                                <span style="display:inline-flex;align-items:center;justify-content:center;min-height:30px;padding:7px 12px;border-radius:999px;background:rgba(244,240,230,0.92);color:#9a9386;border:1px solid rgba(54,45,30,0.08);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;"><?php _esc($template['badge']) ?></span>
                                            </div>
                                            <h4 style="margin-bottom:10px;font-size:24px;line-height:1.15;letter-spacing:-0.03em;"><?php _esc($template['title']) ?></h4>
                                            <p style="margin:0;color:#746c5f;line-height:1.65;font-size:15px;"><?php _esc($template['description']) ?></p>
                                            <ul style="list-style:none;padding:0;margin:20px 0 0;display:flex;flex-direction:column;gap:10px;">
                                                <?php foreach ($template['features'] as $feature) { ?>
                                                    <li style="position:relative;padding-left:18px;color:#5f594f;line-height:1.5;font-size:14px;"><?php _esc($feature) ?></li>
                                                <?php } ?>
                                            </ul>
                                            <?php if (!empty($template['source'])) { ?>
                                                <div style="display:flex;flex-direction:column;gap:8px;margin-top:18px;color:#746c5f;font-size:13px;">
                                                    <span><strong style="color:#1d1d1f;"><?php _e("Source") ?>:</strong> <?php _esc($template['source']) ?></span>
                                                    <?php if (!empty($template['source_path'])) { ?>
                                                        <span><strong style="color:#1d1d1f;"><?php _e("Primary view") ?>:</strong> <?php _esc($template['source_path']) ?></span>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <div class="atlas-website-empty-state margin-top-20">
                            <h5><?php _e("No matching templates are available yet") ?></h5>
                            <p class="margin-bottom-0"><?php _e("Atlas could not load the matching template set for this business type. Refresh the page or return to Company Intelligence and try again."); ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <?php if (!empty($selected_template)) { ?>
                <div class="dashboard-box margin-bottom-30">
                    <div class="content with-padding">
                        <div class="atlas-website-section-heading">
                            <div>
                                <span class="atlas-workflow-eyebrow"><?php _e("Launch details") ?></span>
                                <h4 class="margin-bottom-5"><?php _e("Fill the missing information for your first build") ?></h4>
                                <p class="margin-bottom-0"><?php _e("Every field can be generated by Atlas using the company intelligence you already completed. Review the suggestions, tweak anything you want, then build the website."); ?></p>
                            </div>
                        </div>

                        <form id="atlas-website-build-form" class="atlas-website-editor-form margin-top-24">
                            <input type="hidden" name="template_key" value="<?php _esc($selected_template['key']) ?>">
                            <input type="hidden" name="site_type" value="<?php _esc($selected_template['type']) ?>">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="submit-field atlas-website-field-group">
                                        <div class="atlas-website-field-head">
                                            <h5><?php _e("Website name") ?></h5>
                                            <button type="button" class="button small gray atlas-generate-field" data-field="website_title"><?php _e("Generate by Atlas") ?></button>
                                        </div>
                                        <input type="text" class="with-border" name="website_title" value="<?php _esc(!empty($setup_defaults['website_title']) ? $setup_defaults['website_title'] : '') ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="submit-field atlas-website-field-group">
                                        <div class="atlas-website-field-head">
                                            <h5><?php _e("Your .hatchers.ai domain") ?></h5>
                                            <button type="button" class="button small gray atlas-generate-field" data-field="subdomain"><?php _e("Generate by Atlas") ?></button>
                                        </div>
                                        <div class="atlas-inline-suffix-field">
                                            <input type="text" class="with-border" name="subdomain" value="<?php _esc(!empty($setup_defaults['subdomain']) ? $setup_defaults['subdomain'] : '') ?>" required>
                                            <span>.hatchers.ai</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-7">
                                    <div class="submit-field atlas-website-field-group">
                                        <div class="atlas-website-field-head">
                                            <h5><?php echo $selected_template['type'] === 'ecommerce' ? __('First product name') : __('First service name'); ?></h5>
                                            <button type="button" class="button small gray atlas-generate-field" data-field="first_item_title"><?php _e("Generate by Atlas") ?></button>
                                        </div>
                                        <input type="text" class="with-border" name="first_item_title" value="<?php _esc(!empty($setup_defaults['first_item_title']) ? $setup_defaults['first_item_title'] : '') ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="submit-field atlas-website-field-group">
                                        <div class="atlas-website-field-head">
                                            <h5><?php _e("Starting price") ?></h5>
                                            <button type="button" class="button small gray atlas-generate-field" data-field="first_item_price"><?php _e("Generate by Atlas") ?></button>
                                        </div>
                                        <input type="number" step="0.01" class="with-border" name="first_item_price" value="<?php _esc(!empty($setup_defaults['first_item_price']) ? $setup_defaults['first_item_price'] : '') ?>" required>
                                    </div>
                                </div>
                            </div>

                            <?php if ($selected_template['type'] === 'service') { ?>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="submit-field atlas-website-field-group">
                                            <div class="atlas-website-field-head">
                                                <h5><?php _e("Duration in minutes") ?></h5>
                                                <button type="button" class="button small gray atlas-generate-field" data-field="first_item_duration"><?php _e("Generate by Atlas") ?></button>
                                            </div>
                                            <input type="number" class="with-border" name="first_item_duration" value="<?php _esc(!empty($setup_defaults['first_item_duration']) ? $setup_defaults['first_item_duration'] : '60') ?>" required>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="submit-field atlas-website-field-group">
                                <div class="atlas-website-field-head">
                                    <h5><?php echo $selected_template['type'] === 'ecommerce' ? __('Product description') : __('Service description'); ?></h5>
                                    <button type="button" class="button small gray atlas-generate-field" data-field="first_item_description"><?php _e("Generate by Atlas") ?></button>
                                </div>
                                <textarea class="with-border" rows="5" name="first_item_description" required><?php _esc(!empty($setup_defaults['first_item_description']) ? $setup_defaults['first_item_description'] : '') ?></textarea>
                            </div>

                            <div class="dashboard-box atlas-website-company-context-box margin-bottom-20">
                                <div class="content with-padding">
                                    <span class="atlas-dashboard-label"><?php _e("Using Company Intelligence") ?></span>
                                    <p class="margin-top-10 margin-bottom-0"><?php _esc(!empty($company_intelligence['company_summary']) ? $company_intelligence['company_summary'] : __('Atlas will use your saved company intelligence, target audience, tone, differentiators, and brand colors to shape the website build.')) ?></p>
                                </div>
                            </div>

                            <div class="social-generator-progress margin-top-15 atlas-website-build-progress" style="display:none;">
                                <div class="social-generator-progress-bar-wrap">
                                    <div class="social-generator-progress-bar" style="width:0%;"></div>
                                </div>
                                <div class="social-generator-progress-text margin-top-10">0%</div>
                                <div class="atlas-website-progress-stage margin-top-10"><?php _e("Preparing") ?></div>
                            </div>

                            <div class="atlas-website-editor-footer">
                                <p><?php _e("Atlas will build the first version of your website, connect the template to your brand system, and then take you into the editor where you can manage content, products or services, and preview the public site."); ?></p>
                                <button type="submit" class="button ripple-effect atlas-primary-action" <?php echo $profile_ready ? '' : 'disabled'; ?>><?php _e("Build website") ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>

            <div class="dashboard-box margin-bottom-24">
                <div class="content with-padding">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="atlas-website-how-card">
                                <span class="atlas-dashboard-label"><?php _e("Step 1") ?></span>
                                <h5><?php _e("Use Company Intelligence") ?></h5>
                                <p><?php _e("Atlas pulls your website summary, ICP, differentiators, tone, reference brands, and color direction before building the draft.") ?></p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="atlas-website-how-card">
                                <span class="atlas-dashboard-label"><?php _e("Step 2") ?></span>
                                <h5><?php _e("Apply a complete template") ?></h5>
                                <p><?php _e("We start from a full template, then reshape the copy, structure, and brand tokens so the website feels like it belongs to the business.") ?></p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="atlas-website-how-card">
                                <span class="atlas-dashboard-label"><?php _e("Step 3") ?></span>
                                <h5><?php _e("Sell or take bookings") ?></h5>
                                <p><?php _e("Atlas will connect checkout, bookings, and wallet-ready earnings so customers can pay immediately and owners can withdraw later.") ?></p>
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
        "use strict";

        function websiteProgressStage(value) {
            if (value <= 25) return "<?php echo addslashes(__('Thinking')); ?>";
            if (value <= 50) return "<?php echo addslashes(__('Building')); ?>";
            if (value <= 75) return "<?php echo addslashes(__('Putting everything together')); ?>";
            return "<?php echo addslashes(__('Polishing and finalizing')); ?>";
        }

        function setWebsiteProgress($form, value) {
            var safeValue = Math.max(0, Math.min(100, Math.round(value)));
            $form.find('.atlas-website-build-progress').stop(true, true).fadeIn(120);
            $form.find('.social-generator-progress-bar').css('width', safeValue + '%');
            $form.find('.social-generator-progress-text').text(safeValue + '%');
            $form.find('.atlas-website-progress-stage').text(websiteProgressStage(safeValue));
        }

        function resetWebsiteProgress($form) {
            $form.find('.atlas-website-build-progress').stop(true, true).fadeOut(180, function () {
                $form.find('.social-generator-progress-bar').css('width', '0%');
                $form.find('.social-generator-progress-text').text('0%');
                $form.find('.atlas-website-progress-stage').text("<?php echo addslashes(__('Preparing')); ?>");
            });
        }

        $(document).on('click', '.atlas-generate-field', function () {
            var $btn = $(this);
            var $form = $('#atlas-website-build-form');
            var field = $btn.data('field');
            if (!$form.length || !field) return;

            $btn.addClass('button-progress').prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: ajaxurl + '?action=generate_website_setup_field',
                dataType: 'json',
                data: {
                    field: field,
                    site_type: $form.find('[name="site_type"]').val()
                },
                success: function (response) {
                    if (response.success) {
                        var $target = $form.find('[name="' + field + '"]');
                        if ($target.is('textarea, input')) {
                            $target.val(response.value || '');
                        }
                    } else {
                        quick_alert(response.error || "<?php echo addslashes(__('Unable to generate this field right now.')); ?>", 'error');
                    }
                },
                error: function () {
                    quick_alert("<?php echo addslashes(__('Unable to generate this field right now.')); ?>", 'error');
                },
                complete: function () {
                    $btn.removeClass('button-progress').prop('disabled', false);
                }
            });
        });

        $(document).on('submit', '#atlas-website-build-form', function (e) {
            e.preventDefault();
            var $form = $(this);
            var $btn = $form.find('button[type="submit"]');
            var progressValue = 0;
            var progressTimer;

            $btn.addClass('button-progress').prop('disabled', true);
            setWebsiteProgress($form, progressValue);

            progressTimer = window.setInterval(function () {
                if (progressValue < 24) {
                    progressValue += 2;
                } else if (progressValue < 49) {
                    progressValue += 1.5;
                } else if (progressValue < 74) {
                    progressValue += 1.2;
                } else if (progressValue < 92) {
                    progressValue += 0.8;
                }
                setWebsiteProgress($form, progressValue);
            }, 180);

            $.ajax({
                type: 'POST',
                url: ajaxurl + '?action=build_website_draft',
                dataType: 'json',
                data: $form.serialize(),
                success: function (response) {
                    if (response.success && response.redirect) {
                        window.clearInterval(progressTimer);
                        setWebsiteProgress($form, 100);
                        window.setTimeout(function () {
                            window.location.href = response.redirect;
                        }, 260);
                    } else {
                        window.clearInterval(progressTimer);
                        $btn.removeClass('button-progress').prop('disabled', false);
                        resetWebsiteProgress($form);
                        quick_alert(response.error || "<?php echo addslashes(__('Unable to build your website right now.')); ?>", 'error');
                    }
                },
                error: function () {
                    window.clearInterval(progressTimer);
                    $btn.removeClass('button-progress').prop('disabled', false);
                    resetWebsiteProgress($form);
                    quick_alert("<?php echo addslashes(__('Unable to build your website right now.')); ?>", 'error');
                }
            });
        });
    })(jQuery);
</script>
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH . '/overall_footer_dashboard.php';
