<?php
overall_header(__("Website Editor"));
$template = $site['template'];
$themeTokens = !empty($site['theme_tokens']) ? $site['theme_tokens'] : [];
$content = !empty($site['content']) ? $site['content'] : [];
$homePage = !empty($pages[0]) ? $pages[0] : [];
$hero = !empty($homePage['content']['hero']) ? $homePage['content']['hero'] : [];
$offerings = !empty($content['offerings']) ? $content['offerings'] : [];
$faq = !empty($content['faq']) ? $content['faq'] : [];
$selectedPageKey = !empty($selected_page['page_key']) ? $selected_page['page_key'] : 'home';
$selectedPageContent = !empty($selected_page['content']) ? $selected_page['content'] : [];
$hero = $selectedPageKey === 'home' ? (!empty($selectedPageContent['hero']) ? $selectedPageContent['hero'] : $hero) : $hero;
$offerings = $selectedPageKey === 'home' && !empty($selectedPageContent['offerings']) ? $selectedPageContent['offerings'] : $offerings;
$faq = $selectedPageKey === 'home' && !empty($selectedPageContent['faq']) ? $selectedPageContent['faq'] : $faq;
$proof = $selectedPageKey === 'home' && !empty($selectedPageContent['proof']) ? $selectedPageContent['proof'] : (!empty($homePage['content']['proof']) ? $homePage['content']['proof'] : []);
$previewUrl = website_builder_get_preview_url($site['id']);
$publicSiteUrl = website_builder_get_site_public_url($site);
$websiteBuilderUrl = $config['site_url'] . 'your-website';
$websiteDashboardUrl = website_builder_get_dashboard_url($site['id']);
$websiteEditorBaseUrl = website_builder_get_editor_url($site['id']);
$weekdayLabels = function_exists('website_builder_weekday_labels') ? website_builder_weekday_labels() : [];
$defaultServiceSchedule = function_exists('website_builder_default_service_schedule') ? website_builder_default_service_schedule() : [];
?>
<div class="dashboard-container">
    <?php include_once TEMPLATE_PATH . '/dashboard_sidebar.php'; ?>
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner atlas-workflow-shell">
            <?php print_adsense_code('header_bottom'); ?>

            <div class="dashboard-headline">
                <h3><?php _e("Website Editor") ?></h3>
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><a href="<?php url("YOUR_WEBSITE") ?>"><?php _e("Your Website") ?></a></li>
                        <li><?php _esc($site['site_name']) ?></li>
                    </ul>
                </nav>
            </div>

            <div class="atlas-workflow-hero margin-bottom-30">
                <span class="atlas-workflow-eyebrow"><?php _e("Draft website") ?></span>
                <h2><?php _esc($site['site_name']) ?></h2>
                <p><?php echo sprintf(__('This draft uses the %s template and is shaped by Company Intelligence, your brand colors, and your business positioning.'), _esc($template['title'], 0)); ?></p>
                <div class="atlas-workflow-actions margin-top-20">
                    <a href="<?php echo $previewUrl; ?>" class="button"><?php _e("Open preview") ?></a>
                    <a href="<?php echo $publicSiteUrl; ?>" target="_blank" class="button gray"><?php _e("Open public site") ?></a>
                    <a href="<?php echo $websiteDashboardUrl; ?>" class="button gray"><?php _e("Open dashboard") ?></a>
                    <a href="<?php echo $websiteBuilderUrl; ?>" class="button button-sliding-icon ripple-effect"><?php _e("Back to templates") ?><i class="icon-material-outline-arrow-forward"></i></a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-7 margin-bottom-30">
                    <div class="dashboard-box atlas-website-preview-box">
                        <div class="content with-padding">
                            <div class="atlas-website-preview-browser">
                                <span></span><span></span><span></span>
                                <strong><?php _esc($site['subdomain']) ?></strong>
                            </div>
                            <div class="atlas-website-preview-toolbar">
                                <span class="atlas-dashboard-label"><?php _e("Public preview") ?></span>
                                <a href="<?php echo $previewUrl; ?>" target="_blank" class="button"><?php _e("Open preview") ?></a>
                            </div>
                            <div class="atlas-website-preview-canvas" style="--website-primary: <?php _esc(!empty($themeTokens['primary']) ? $themeTokens['primary'] : '#111111'); ?>; --website-secondary: <?php _esc(!empty($themeTokens['secondary']) ? $themeTokens['secondary'] : '#f3efe6'); ?>; --website-accent: <?php _esc(!empty($themeTokens['accent']) ? $themeTokens['accent'] : '#111111'); ?>;">
                                <section class="atlas-website-preview-hero">
                                    <span class="atlas-website-preview-eyebrow"><?php _esc(!empty($hero['eyebrow']) ? $hero['eyebrow'] : __('Your Website')); ?></span>
                                    <h1><?php _esc(!empty($hero['title']) ? $hero['title'] : $site['site_name']); ?></h1>
                                    <p><?php _esc(!empty($hero['subtitle']) ? $hero['subtitle'] : __('This is your generated website draft.')); ?></p>
                                    <div class="atlas-website-preview-actions">
                                        <span class="atlas-website-preview-button atlas-website-preview-button-primary"><?php _esc(!empty($hero['primary_cta']) ? $hero['primary_cta'] : __('Get started')); ?></span>
                                        <span class="atlas-website-preview-button"><?php _esc(!empty($hero['secondary_cta']) ? $hero['secondary_cta'] : __('Learn more')); ?></span>
                                    </div>
                                </section>

                                <section class="atlas-website-preview-grid">
                                    <?php foreach (array_slice($offerings, 0, 3) as $offering) { ?>
                                        <article class="atlas-website-preview-card">
                                            <strong><?php _esc($offering) ?></strong>
                                            <p><?php _e("Starter section generated from your company intelligence and selected template."); ?></p>
                                        </article>
                                    <?php } ?>
                                </section>

                                <?php if (!empty($proof)) { ?>
                                    <section class="atlas-website-preview-proof">
                                        <?php foreach (array_slice($proof, 0, 3) as $proofPoint) { ?>
                                            <span><?php _esc($proofPoint) ?></span>
                                        <?php } ?>
                                    </section>
                                <?php } ?>

                                <section class="atlas-website-preview-faq">
                                    <h3><?php _e("FAQ") ?></h3>
                                    <?php foreach (array_slice($faq, 0, 2) as $item) { ?>
                                        <div class="atlas-website-preview-faq-item">
                                            <strong><?php _esc($item['question']) ?></strong>
                                            <p><?php _esc($item['answer']) ?></p>
                                        </div>
                                    <?php } ?>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 margin-bottom-30">
                    <div class="dashboard-box margin-bottom-24">
                        <div class="headline">
                            <h3><i class="icon-feather-monitor"></i> <?php _e("Site setup") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <div class="atlas-website-meta-list">
                                <div><span><?php _e("Template") ?></span><strong><?php _esc($template['title']) ?></strong></div>
                                <div><span><?php _e("Type") ?></span><strong><?php _esc(ucfirst($site['site_type'])) ?></strong></div>
                                <div><span><?php _e("Status") ?></span><strong><?php _esc(ucfirst($site['status'])) ?></strong></div>
                                <div><span><?php _e("Subdomain") ?></span><strong><?php _esc($site['subdomain']) ?></strong></div>
                                <div><span><?php _e("Payments") ?></span><strong><?php _e("Atlas checkout + wallet") ?></strong></div>
                                <div><span><?php _e("Template source") ?></span><strong><?php _esc($template['source']) ?></strong></div>
                            </div>
                            <div class="margin-top-20">
                                <a href="<?php url("COMPANY_INTELLIGENCE") ?>" class="button ripple-effect"><?php _e("Edit Company Intelligence") ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-box margin-bottom-24">
                        <div class="headline">
                            <h3><i class="icon-feather-layers"></i> <?php _e("Generated pages") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <div class="atlas-dashboard-quick-list margin-bottom-24">
                                <?php foreach ($pages as $page) { ?>
                                    <a href="<?php echo website_builder_get_editor_url($site['id'], ['page' => $page['page_key']]); ?>" class="atlas-dashboard-quick-item <?php echo $page['page_key'] === $selectedPageKey ? 'atlas-dashboard-quick-item-active' : ''; ?>">
                                        <span class="atlas-dashboard-quick-avatar"><i class="icon-feather-file-text"></i></span>
                                        <span class="atlas-dashboard-quick-copy">
                                            <strong><?php _esc($page['title']) ?></strong>
                                            <small><?php echo $page['slug'] !== '' ? '/' . $page['slug'] : '/'; ?></small>
                                        </span>
                                        <span class="atlas-dashboard-quick-meta"><?php _esc(ucfirst($page['status'])) ?></span>
                                    </a>
                                <?php } ?>
                            </div>

                            <?php if (!empty($selected_page)) { ?>
                                <form method="post" class="atlas-website-editor-form">
                                    <input type="hidden" name="page_key" value="<?php _esc($selectedPageKey) ?>">
                                    <div class="atlas-website-editor-heading">
                                        <div>
                                            <span class="atlas-dashboard-label"><?php _e("Editing") ?></span>
                                            <h4><?php _esc($selected_page['title']) ?></h4>
                                        </div>
                                        <span class="atlas-dashboard-quick-meta"><?php echo $selectedPageKey === 'home' ? __('Homepage') : __('Content page'); ?></span>
                                    </div>

                                    <?php if ($selectedPageKey === 'home') { ?>
                                        <div class="submit-field">
                                            <h5><?php _e("Hero eyebrow") ?></h5>
                                            <input type="text" class="with-border" name="hero_eyebrow" value="<?php _esc(!empty($hero['eyebrow']) ? $hero['eyebrow'] : '') ?>">
                                        </div>
                                        <div class="submit-field">
                                            <h5><?php _e("Hero title") ?></h5>
                                            <textarea class="with-border" rows="3" name="hero_title"><?php _esc(!empty($hero['title']) ? $hero['title'] : '') ?></textarea>
                                        </div>
                                        <div class="submit-field">
                                            <h5><?php _e("Hero subtitle") ?></h5>
                                            <textarea class="with-border" rows="4" name="hero_subtitle"><?php _esc(!empty($hero['subtitle']) ? $hero['subtitle'] : '') ?></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="submit-field">
                                                    <h5><?php _e("Primary CTA") ?></h5>
                                                    <input type="text" class="with-border" name="hero_primary_cta" value="<?php _esc(!empty($hero['primary_cta']) ? $hero['primary_cta'] : '') ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="submit-field">
                                                    <h5><?php _e("Secondary CTA") ?></h5>
                                                    <input type="text" class="with-border" name="hero_secondary_cta" value="<?php _esc(!empty($hero['secondary_cta']) ? $hero['secondary_cta'] : '') ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="submit-field">
                                            <h5><?php _e("Offerings") ?></h5>
                                            <textarea class="with-border" rows="4" name="offerings"><?php _esc(implode("\n", $offerings)) ?></textarea>
                                            <small><?php _e("One per line. Atlas uses these as your featured services or products."); ?></small>
                                        </div>
                                        <div class="submit-field">
                                            <h5><?php _e("Proof points") ?></h5>
                                            <textarea class="with-border" rows="4" name="proof"><?php _esc(implode("\n", $proof)) ?></textarea>
                                            <small><?php _e("Use short trust-building points like guarantees, differentiation, or social proof."); ?></small>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="submit-field">
                                                    <h5><?php _e("FAQ question 1") ?></h5>
                                                    <input type="text" class="with-border" name="faq_question_1" value="<?php _esc(!empty($faq[0]['question']) ? $faq[0]['question'] : '') ?>">
                                                </div>
                                                <div class="submit-field">
                                                    <h5><?php _e("FAQ answer 1") ?></h5>
                                                    <textarea class="with-border" rows="4" name="faq_answer_1"><?php _esc(!empty($faq[0]['answer']) ? $faq[0]['answer'] : '') ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="submit-field">
                                                    <h5><?php _e("FAQ question 2") ?></h5>
                                                    <input type="text" class="with-border" name="faq_question_2" value="<?php _esc(!empty($faq[1]['question']) ? $faq[1]['question'] : '') ?>">
                                                </div>
                                                <div class="submit-field">
                                                    <h5><?php _e("FAQ answer 2") ?></h5>
                                                    <textarea class="with-border" rows="4" name="faq_answer_2"><?php _esc(!empty($faq[1]['answer']) ? $faq[1]['answer'] : '') ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="submit-field">
                                            <h5><?php _e("Section title") ?></h5>
                                            <input type="text" class="with-border" name="section_title" value="<?php _esc(!empty($selectedPageContent['title']) ? $selectedPageContent['title'] : $selected_page['title']) ?>">
                                        </div>
                                        <div class="submit-field">
                                            <h5><?php _e("Section body") ?></h5>
                                            <textarea class="with-border" rows="10" name="section_body"><?php _esc(!empty($selectedPageContent['body']) ? $selectedPageContent['body'] : '') ?></textarea>
                                        </div>
                                    <?php } ?>

                                    <div class="atlas-website-editor-footer">
                                        <p><?php _e("Changes save into your Atlas draft. You can keep refining the structure before checkout, bookings, and publishing are wired in."); ?></p>
                                        <button type="submit" name="save_website_page" class="button ripple-effect"><?php _e("Save page") ?></button>
                                    </div>
                                </form>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="dashboard-box margin-bottom-24">
                        <div class="headline">
                            <h3><i class="icon-feather-credit-card"></i> <?php _e("Atlas wallet flow") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <div class="atlas-website-meta-list">
                                <div><span><?php _e("Pending earnings") ?></span><strong><?php _esc(price_format($wallet_summary['pending'])) ?></strong></div>
                                <div><span><?php _e("Posted earnings") ?></span><strong><?php _esc(price_format($wallet_summary['posted'])) ?></strong></div>
                                <div><span><?php _e("Captured requests") ?></span><strong><?php _esc($wallet_summary['count']) ?></strong></div>
                            </div>
                            <p class="margin-top-15 margin-bottom-0"><?php _e("Every public order or booking request is attached to this site and recorded against the Atlas wallet ledger so payouts can be layered in next."); ?></p>
                        </div>
                    </div>

                    <?php if ($site['site_type'] === 'ecommerce') { ?>
                        <div class="dashboard-box margin-bottom-24">
                            <div class="headline">
                                <h3><i class="icon-feather-shopping-bag"></i> <?php _e("Products") ?></h3>
                            </div>
                            <div class="content with-padding">
                                <div class="atlas-dashboard-quick-list margin-bottom-20">
                                    <?php foreach ($products as $product) { ?>
                                        <div class="atlas-dashboard-quick-item atlas-dashboard-quick-item-static">
                                            <span class="atlas-dashboard-quick-avatar"><i class="icon-feather-package"></i></span>
                                            <span class="atlas-dashboard-quick-copy">
                                                <strong><?php _esc($product['title']) ?></strong>
                                                <small><?php _esc(price_format($product['price'])) ?> / <?php _esc($product['currency']) ?></small>
                                            </span>
                                            <span class="atlas-dashboard-quick-meta"><?php _esc(ucfirst($product['status'])) ?></span>
                                        </div>
                                    <?php } ?>
                                </div>

                                <form method="post" class="atlas-website-editor-form">
                                    <div class="atlas-website-editor-heading">
                                        <div>
                                            <span class="atlas-dashboard-label"><?php _e("Catalog") ?></span>
                                            <h4><?php _e("Add or refresh a product") ?></h4>
                                        </div>
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e("Product title") ?></h5>
                                        <input type="text" class="with-border" name="product_title" required>
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e("Description") ?></h5>
                                        <textarea class="with-border" rows="4" name="product_description" required></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="submit-field">
                                                <h5><?php _e("Price") ?></h5>
                                                <input type="number" step="0.01" class="with-border" name="product_price" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="submit-field">
                                                <h5><?php _e("Currency") ?></h5>
                                                <input type="text" class="with-border" name="product_currency" value="USD">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" name="save_website_product" class="button ripple-effect"><?php _e("Save product") ?></button>
                                </form>

                                <?php if (!empty($products)) { ?>
                                    <div class="atlas-website-inline-actions">
                                        <?php foreach ($products as $product) { ?>
                                            <form method="post">
                                                <input type="hidden" name="product_id" value="<?php _esc($product['id']) ?>">
                                                <button type="submit" name="delete_website_product" class="button gray"><?php echo sprintf(__('Delete %s'), _esc($product['title'], 0)); ?></button>
                                            </form>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="dashboard-box margin-bottom-24">
                            <div class="headline">
                                <h3><i class="icon-feather-calendar"></i> <?php _e("Services") ?></h3>
                            </div>
                            <div class="content with-padding">
                                <div class="atlas-dashboard-quick-list margin-bottom-20">
                                    <?php foreach ($services as $service) { ?>
                                        <div class="atlas-dashboard-quick-item atlas-dashboard-quick-item-static">
                                            <span class="atlas-dashboard-quick-avatar"><i class="icon-feather-clock"></i></span>
                                            <span class="atlas-dashboard-quick-copy">
                                                <strong><?php _esc($service['title']) ?></strong>
                                                <small><?php _esc($service['duration_minutes']) ?> <?php _e("mins") ?> / <?php _esc(price_format($service['price'])) ?></small>
                                                <?php if (!empty($service['availability_note'])) { ?>
                                                    <small><?php _esc($service['availability_note']) ?></small>
                                                <?php } ?>
                                            </span>
                                            <span class="atlas-dashboard-quick-meta"><?php _esc(ucfirst($service['status'])) ?></span>
                                        </div>
                                    <?php } ?>
                                </div>

                                <form method="post" class="atlas-website-editor-form">
                                    <div class="atlas-website-editor-heading">
                                        <div>
                                            <span class="atlas-dashboard-label"><?php _e("Service setup") ?></span>
                                            <h4><?php _e("Add or refresh a service") ?></h4>
                                        </div>
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e("Service title") ?></h5>
                                        <input type="text" class="with-border" name="service_title" required>
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e("Description") ?></h5>
                                        <textarea class="with-border" rows="4" name="service_description" required></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="submit-field">
                                                <h5><?php _e("Duration") ?></h5>
                                                <input type="number" class="with-border" name="service_duration_minutes" value="60" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="submit-field">
                                                <h5><?php _e("Price") ?></h5>
                                                <input type="number" step="0.01" class="with-border" name="service_price" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="submit-field">
                                                <h5><?php _e("Currency") ?></h5>
                                                <input type="text" class="with-border" name="service_currency" value="USD">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e("Booking availability") ?></h5>
                                        <textarea class="with-border" rows="3" name="service_availability_note" placeholder="<?php _e("Example: Monday to Friday, 9 AM to 6 PM. Weekend by request."); ?>"></textarea>
                                    </div>
                                    <div class="atlas-website-calendar-settings">
                                        <div class="atlas-website-editor-heading">
                                            <div>
                                                <span class="atlas-dashboard-label"><?php _e("Real booking calendar") ?></span>
                                                <h4><?php _e("Set weekly availability") ?></h4>
                                            </div>
                                        </div>
                                        <div class="atlas-website-schedule-grid">
                                            <?php foreach ($defaultServiceSchedule as $dayKey => $daySchedule) { ?>
                                                <div class="atlas-website-schedule-row">
                                                    <label class="atlas-website-schedule-toggle">
                                                        <input type="checkbox" name="service_schedule[<?php _esc($dayKey) ?>][enabled]" value="1" <?php echo !empty($daySchedule['enabled']) ? 'checked' : ''; ?>>
                                                        <span><?php _esc(!empty($weekdayLabels[$dayKey]) ? $weekdayLabels[$dayKey] : ucfirst($dayKey)) ?></span>
                                                    </label>
                                                    <input type="time" class="with-border" name="service_schedule[<?php _esc($dayKey) ?>][start]" value="<?php _esc($daySchedule['start']) ?>">
                                                    <input type="time" class="with-border" name="service_schedule[<?php _esc($dayKey) ?>][end]" value="<?php _esc($daySchedule['end']) ?>">
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="row margin-top-15">
                                            <div class="col-md-3">
                                                <div class="submit-field">
                                                    <h5><?php _e("Slot interval") ?></h5>
                                                    <input type="number" class="with-border" name="service_slot_interval_minutes" value="30">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="submit-field">
                                                    <h5><?php _e("Buffer") ?></h5>
                                                    <input type="number" class="with-border" name="service_booking_buffer_minutes" value="0">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="submit-field">
                                                    <h5><?php _e("Min notice") ?></h5>
                                                    <input type="number" class="with-border" name="service_min_notice_hours" value="2">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="submit-field">
                                                    <h5><?php _e("Max advance") ?></h5>
                                                    <input type="number" class="with-border" name="service_max_advance_days" value="30">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="submit-field">
                                            <h5><?php _e("Blocked dates") ?></h5>
                                            <input type="text" class="with-border" name="service_blocked_dates" placeholder="<?php _e("2026-04-10, 2026-04-11"); ?>">
                                            <small><?php _e("Use comma-separated dates to block holidays or unavailable days."); ?></small>
                                        </div>
                                    </div>
                                    <button type="submit" name="save_website_service" class="button ripple-effect"><?php _e("Save service") ?></button>
                                </form>

                                <?php if (!empty($services)) { ?>
                                    <div class="atlas-website-inline-actions">
                                        <?php foreach ($services as $service) { ?>
                                            <form method="post">
                                                <input type="hidden" name="service_id" value="<?php _esc($service['id']) ?>">
                                                <button type="submit" name="delete_website_service" class="button gray"><?php echo sprintf(__('Delete %s'), _esc($service['title'], 0)); ?></button>
                                            </form>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="dashboard-box">
                        <div class="headline">
                            <h3><i class="icon-feather-activity"></i> <?php _e("Atlas implementation notes") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <ul class="atlas-automation-feature-list">
                                <li><?php _e("This draft is generated from Company Intelligence and can be regenerated as your business profile changes.") ?></li>
                                <li><?php _e("Service sites will connect to bookings. Ecommerce sites will connect to products, checkout, and Atlas wallet payouts.") ?></li>
                                <li><?php _e("The current phase gives you the structure, template selection, and tenant scaffolding we need before full editing and publishing.") ?></li>
                            </ul>
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
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH . '/overall_footer_dashboard.php';
