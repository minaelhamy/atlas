<?php
overall_header($site['site_name']);
$themeTokens = !empty($site['theme_tokens']) ? $site['theme_tokens'] : [];
$siteContent = !empty($site['content']) ? $site['content'] : [];
$homePage = !empty($pages[0]) ? $pages[0] : [];
$homeContent = !empty($homePage['content']) ? $homePage['content'] : [];
$hero = !empty($homeContent['hero']) ? $homeContent['hero'] : [];
$offerings = !empty($homeContent['offerings']) ? $homeContent['offerings'] : [];
$proof = !empty($homeContent['proof']) ? $homeContent['proof'] : [];
$faq = !empty($homeContent['faq']) ? $homeContent['faq'] : [];
$bodyPages = array_slice($pages, 1);
$rescheduleService = !empty($reschedule_booking) ? website_builder_get_service($site['id'], $reschedule_booking['service_id']) : null;
?>
<div class="atlas-public-site" style="--website-primary: <?php _esc(!empty($themeTokens['primary']) ? $themeTokens['primary'] : '#111111'); ?>; --website-secondary: <?php _esc(!empty($themeTokens['secondary']) ? $themeTokens['secondary'] : '#f3efe6'); ?>; --website-accent: <?php _esc(!empty($themeTokens['accent']) ? $themeTokens['accent'] : '#111111'); ?>;">
    <div class="container">
        <header class="atlas-public-site-header">
            <a href="<?php url("INDEX") ?>" class="atlas-public-site-brand">
                <strong><?php _esc($site['site_name']) ?></strong>
                <span><?php echo $site['site_type'] === 'ecommerce' ? __('Atlas-powered store') : __('Atlas-powered booking site'); ?></span>
            </a>
            <nav class="atlas-public-site-nav">
                <a href="#offerings"><?php _e("Offerings") ?></a>
                <?php foreach ($bodyPages as $page) { ?>
                    <a href="#page-<?php _esc($page['page_key']) ?>"><?php _esc($page['title']) ?></a>
                <?php } ?>
                <a href="#faq"><?php _e("FAQ") ?></a>
            </nav>
            <div class="atlas-public-site-actions">
                <a href="<?php url("YOUR_WEBSITE") ?>" class="button"><?php _e("Built with Atlas") ?></a>
            </div>
        </header>

        <section class="atlas-public-site-hero">
            <div class="atlas-public-site-hero-copy">
                <span class="atlas-website-preview-eyebrow"><?php _esc(!empty($hero['eyebrow']) ? $hero['eyebrow'] : __('Your Website')); ?></span>
                <h1><?php _esc(!empty($hero['title']) ? $hero['title'] : $site['site_name']) ?></h1>
                <p><?php _esc(!empty($hero['subtitle']) ? $hero['subtitle'] : __('This is an Atlas-generated website draft shaped by your company intelligence.')) ?></p>
                <div class="atlas-website-preview-actions">
                    <a href="#offerings" class="atlas-website-preview-button atlas-website-preview-button-primary"><?php _esc(!empty($hero['primary_cta']) ? $hero['primary_cta'] : __('Get started')) ?></a>
                    <a href="#faq" class="atlas-website-preview-button"><?php _esc(!empty($hero['secondary_cta']) ? $hero['secondary_cta'] : __('Learn more')) ?></a>
                </div>
            </div>
            <div class="atlas-public-site-hero-panel">
                <div class="atlas-public-site-hero-stats">
                    <div>
                        <span><?php _e("Experience") ?></span>
                        <strong><?php echo $site['site_type'] === 'ecommerce' ? __('Online sales ready') : __('Bookings ready'); ?></strong>
                    </div>
                    <div>
                        <span><?php _e("Payments") ?></span>
                        <strong><?php _e("Atlas wallet"); ?></strong>
                    </div>
                    <div>
                        <span><?php _e("Brand fit") ?></span>
                        <strong><?php _e("Built from your company intelligence"); ?></strong>
                    </div>
                </div>
            </div>
        </section>

        <?php if (!empty($proof)) { ?>
            <section class="atlas-public-site-proof">
                <?php foreach (array_slice($proof, 0, 4) as $proofPoint) { ?>
                    <span><?php _esc($proofPoint) ?></span>
                <?php } ?>
            </section>
        <?php } ?>

        <section class="atlas-public-site-section" id="offerings">
            <div class="atlas-public-site-section-head">
                <span class="atlas-dashboard-label"><?php echo $site['site_type'] === 'ecommerce' ? __('Featured catalog') : __('Core services'); ?></span>
                <h2><?php echo $site['site_type'] === 'ecommerce' ? __('What customers can buy from you') : __('What customers can book with you'); ?></h2>
                <p><?php _e("These featured cards are generated from your business profile and can be refined in the website editor."); ?></p>
            </div>
            <div class="atlas-public-site-grid">
                <?php if ($site['site_type'] === 'ecommerce' && !empty($products)) : ?>
                    <?php foreach ($products as $product) : ?>
                        <article class="atlas-public-site-card">
                            <strong><?php _esc($product['title']) ?></strong>
                            <p><?php _esc($product['description']) ?></p>
                            <div class="atlas-public-site-card-meta">
                                <span><?php _esc(price_format($product['price'])) ?> / <?php _esc($product['currency']) ?></span>
                                <span><?php _e("Atlas checkout") ?></span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php elseif ($site['site_type'] === 'service' && !empty($services)) : ?>
                    <?php foreach ($services as $service) : ?>
                        <article class="atlas-public-site-card">
                            <strong><?php _esc($service['title']) ?></strong>
                            <p><?php _esc($service['description']) ?></p>
                            <div class="atlas-public-site-card-meta">
                                <span><?php _esc($service['duration_minutes']) ?> <?php _e("mins") ?></span>
                                <span><?php _esc(price_format($service['price'])) ?> / <?php _esc($service['currency']) ?></span>
                            </div>
                            <?php if (!empty($service['availability_note'])) { ?>
                                <div class="atlas-public-site-card-note"><?php _esc($service['availability_note']) ?></div>
                            <?php } ?>
                        </article>
                    <?php endforeach; ?>
                <?php else : ?>
                    <?php foreach (array_slice($offerings, 0, 6) as $offering) : ?>
                        <article class="atlas-public-site-card">
                            <strong><?php _esc($offering) ?></strong>
                            <p><?php echo $site['site_type'] === 'ecommerce'
                                    ? __('Use this slot for a highlighted product, bundle, or collection that should convert first-time visitors.')
                                    : __('Use this slot to position one of your most important services and make the booking path obvious.'); ?></p>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <?php foreach ($bodyPages as $page) { $pageContent = !empty($page['content']) ? $page['content'] : []; ?>
            <section class="atlas-public-site-section" id="page-<?php _esc($page['page_key']) ?>">
                <div class="atlas-public-site-content-card">
                    <span class="atlas-dashboard-label"><?php _esc($page['title']) ?></span>
                    <h2><?php _esc(!empty($pageContent['title']) ? $pageContent['title'] : $page['title']) ?></h2>
                    <p><?php _esc(!empty($pageContent['body']) ? $pageContent['body'] : __('This section is ready for your final content edits.')) ?></p>
                </div>
            </section>
        <?php } ?>

        <section class="atlas-public-site-section" id="faq">
            <div class="atlas-public-site-section-head">
                <span class="atlas-dashboard-label"><?php _e("FAQ") ?></span>
                <h2><?php _e("Questions customers will ask before they convert"); ?></h2>
            </div>
            <div class="atlas-public-site-faq">
                <?php foreach (array_slice($faq, 0, 4) as $item) { ?>
                    <article class="atlas-public-site-faq-item">
                        <strong><?php _esc($item['question']) ?></strong>
                        <p><?php _esc($item['answer']) ?></p>
                    </article>
                <?php } ?>
            </div>
        </section>

        <section class="atlas-public-site-section" id="atlas-action">
            <div class="atlas-public-site-section-head">
                <span class="atlas-dashboard-label"><?php echo $site['site_type'] === 'ecommerce' ? __('Take an order') : __('Capture a booking'); ?></span>
                <h2><?php echo $site['site_type'] === 'ecommerce' ? __('Send this customer into Atlas checkout') : __('Send this customer into Atlas booking flow'); ?></h2>
                <p><?php _e("This is the first live capture layer. Requests are attached to the website and recorded for Atlas wallet handling."); ?></p>
            </div>

            <?php if (!empty($form_success)) { ?>
                <div class="notification success margin-bottom-20"><?php _esc($form_success) ?></div>
            <?php } ?>
            <?php if (!empty($form_error)) { ?>
                <div class="notification error margin-bottom-20"><?php _esc($form_error) ?></div>
            <?php } ?>

            <?php if ($site['site_type'] === 'service' && !empty($reschedule_booking) && !empty($rescheduleService)) { ?>
                <div class="atlas-public-site-content-card margin-bottom-20">
                    <div class="atlas-public-site-section-head">
                        <span class="atlas-dashboard-label"><?php _e("Manage booking") ?></span>
                        <h2><?php _e("Reschedule your booking") ?></h2>
                        <p><?php echo sprintf(__('You are updating %s for %s.'), _esc(!empty($reschedule_booking['metadata']['service_title']) ? $reschedule_booking['metadata']['service_title'] : __('your booking'), 0), _esc($reschedule_booking['customer_name'], 0)); ?></p>
                    </div>
                    <form method="post" class="atlas-public-site-form atlas-public-booking-form atlas-public-reschedule-form" data-site-slug="<?php _esc($site['slug']) ?>" data-booking-id="<?php _esc($reschedule_booking['id']) ?>">
                        <input type="hidden" name="booking_id" value="<?php _esc($reschedule_booking['id']) ?>">
                        <input type="hidden" name="reschedule_token" value="<?php _esc(!empty($_GET['token']) ? validate_input($_GET['token']) : '') ?>">
                        <div class="submit-field">
                            <h5><?php _e("Current booking") ?></h5>
                            <input type="text" class="with-border" value="<?php _esc(date('d M Y h:i A', strtotime($reschedule_booking['booking_start']))) ?>" readonly>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="submit-field">
                                    <h5><?php _e("Choose a new date") ?></h5>
                                    <input type="date" class="with-border atlas-booking-date-input" min="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="submit-field">
                                    <h5><?php _e("Selected new slot") ?></h5>
                                    <input type="text" class="with-border atlas-booking-selected-slot-label" value="<?php _e("No slot selected yet"); ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="submit-field">
                            <h5><?php _e("Available slots") ?></h5>
                            <div class="atlas-booking-slot-feedback"><?php _e("Pick a date to load available times."); ?></div>
                            <div class="atlas-booking-slots"></div>
                            <input type="hidden" name="booking_start" class="atlas-booking-start-input" required>
                        </div>
                        <button type="submit" name="reschedule_website_booking" class="atlas-website-preview-button atlas-website-preview-button-primary"><?php _e("Confirm new time") ?></button>
                    </form>
                </div>
            <?php } ?>

            <div class="atlas-public-site-content-card">
                <?php if ($site['site_type'] === 'ecommerce') { ?>
                    <form method="post" class="atlas-public-site-form">
                        <div class="submit-field">
                            <h5><?php _e("Product") ?></h5>
                            <select name="product_id" class="with-border" required>
                                <?php foreach ($products as $product) { ?>
                                    <option value="<?php _esc($product['id']) ?>"><?php _esc($product['title']) ?> - <?php _esc(price_format($product['price'])) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="submit-field">
                                    <h5><?php _e("Customer name") ?></h5>
                                    <input type="text" class="with-border" name="customer_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="submit-field">
                                    <h5><?php _e("Customer email") ?></h5>
                                    <input type="email" class="with-border" name="customer_email" required>
                                </div>
                            </div>
                        </div>
                        <div class="submit-field">
                            <h5><?php _e("Order notes") ?></h5>
                            <textarea class="with-border" rows="4" name="notes"></textarea>
                        </div>
                        <button type="submit" name="place_website_order" class="atlas-website-preview-button atlas-website-preview-button-primary"><?php _e("Capture order in Atlas") ?></button>
                    </form>
                <?php } else { ?>
                    <form method="post" class="atlas-public-site-form atlas-public-booking-form" data-site-slug="<?php _esc($site['slug']) ?>" data-booking-id="">
                        <div class="submit-field">
                            <h5><?php _e("Service") ?></h5>
                            <select name="service_id" class="with-border atlas-booking-service-select" required>
                                <?php foreach ($services as $service) { ?>
                                    <option value="<?php _esc($service['id']) ?>"><?php _esc($service['title']) ?> - <?php _esc(price_format($service['price'])) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="submit-field">
                                    <h5><?php _e("Choose date") ?></h5>
                                    <input type="date" class="with-border atlas-booking-date-input" min="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="submit-field">
                                    <h5><?php _e("Selected slot") ?></h5>
                                    <input type="text" class="with-border atlas-booking-selected-slot-label" value="<?php _e("No slot selected yet"); ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="submit-field">
                            <h5><?php _e("Available slots") ?></h5>
                            <div class="atlas-booking-slot-feedback"><?php _e("Pick a date to load available times."); ?></div>
                            <div class="atlas-booking-slots"></div>
                            <input type="hidden" name="booking_start" class="atlas-booking-start-input" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="submit-field">
                                    <h5><?php _e("Customer name") ?></h5>
                                    <input type="text" class="with-border" name="customer_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="submit-field">
                                    <h5><?php _e("Customer email") ?></h5>
                                    <input type="email" class="with-border" name="customer_email" required>
                                </div>
                            </div>
                        </div>
                        <div class="submit-field">
                            <h5><?php _e("Requested date and time") ?></h5>
                            <p class="margin-bottom-0 atlas-booking-helper-copy"><?php _e("Atlas shows live slots based on the service schedule, notice window, blocked dates, and existing bookings."); ?></p>
                        </div>
                        <div class="submit-field">
                            <h5><?php _e("Booking notes") ?></h5>
                            <textarea class="with-border" rows="4" name="notes"></textarea>
                        </div>
                        <button type="submit" name="place_website_booking" class="atlas-website-preview-button atlas-website-preview-button-primary"><?php _e("Capture booking in Atlas") ?></button>
                    </form>
                <?php } ?>
            </div>
        </section>

        <section class="atlas-public-site-cta">
            <div>
                <span class="atlas-dashboard-label"><?php _e("Next step") ?></span>
                <h2><?php echo $site['site_type'] === 'ecommerce' ? __('Start taking payments through Atlas') : __('Start taking bookings through Atlas'); ?></h2>
                <p><?php _e("This public preview now captures website-originated requests. The next phase is converting these requests into full checkout, booking status, and payout flows."); ?></p>
            </div>
            <a href="<?php url("YOUR_WEBSITE") ?>" class="atlas-website-preview-button atlas-website-preview-button-primary"><?php _e("Open builder") ?></a>
        </section>
    </div>
</div>
<?php if ($site['site_type'] === 'service') { ?>
    <script>
        (function () {
            const bookingForms = document.querySelectorAll('.atlas-public-booking-form');
            if (!bookingForms.length) {
                return;
            }

            function setupForm(bookingForm) {
                const serviceSelect = bookingForm.querySelector('.atlas-booking-service-select');
                const dateInput = bookingForm.querySelector('.atlas-booking-date-input');
                const slotsWrap = bookingForm.querySelector('.atlas-booking-slots');
                const feedback = bookingForm.querySelector('.atlas-booking-slot-feedback');
                const bookingStartInput = bookingForm.querySelector('.atlas-booking-start-input');
                const selectedLabel = bookingForm.querySelector('.atlas-booking-selected-slot-label');
                const siteSlug = bookingForm.getAttribute('data-site-slug');
                const bookingId = bookingForm.getAttribute('data-booking-id') || '';

                function resetSlots(message) {
                    if (slotsWrap) {
                        slotsWrap.innerHTML = '';
                    }
                    if (bookingStartInput) {
                        bookingStartInput.value = '';
                    }
                    if (selectedLabel) {
                        selectedLabel.value = '<?php echo addslashes(__('No slot selected yet')); ?>';
                    }
                    if (feedback) {
                        feedback.textContent = message || '<?php echo addslashes(__('Pick a date to load available times.')); ?>';
                    }
                }

                function renderSlots(slots) {
                    slotsWrap.innerHTML = '';
                    slots.forEach(function (slot) {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'atlas-booking-slot-button';
                        button.textContent = slot.label;
                        button.dataset.value = slot.value;
                        button.addEventListener('click', function () {
                            bookingStartInput.value = slot.value;
                            selectedLabel.value = slot.label;
                            slotsWrap.querySelectorAll('.atlas-booking-slot-button').forEach(function (item) {
                                item.classList.remove('atlas-booking-slot-button-active');
                            });
                            button.classList.add('atlas-booking-slot-button-active');
                        });
                        slotsWrap.appendChild(button);
                    });
                }

                function loadSlots() {
                    const serviceId = serviceSelect ? serviceSelect.value : '<?php echo !empty($rescheduleService['id']) ? (int) $rescheduleService['id'] : ''; ?>';
                    const dateValue = dateInput ? dateInput.value : '';
                    if (!serviceId || !dateValue) {
                        resetSlots();
                        return;
                    }

                    feedback.textContent = '<?php echo addslashes(__('Loading available slots...')); ?>';
                    slotsWrap.innerHTML = '';
                    bookingStartInput.value = '';

                    const params = new URLSearchParams({
                        action: 'website_service_slots',
                        slug: siteSlug,
                        service_id: serviceId,
                        date: dateValue
                    });
                    if (bookingId) {
                        params.append('booking_id', bookingId);
                    }

                    fetch('<?php echo $config['site_url']; ?>php/6a7i8lsi3m.php?' + params.toString(), {
                        credentials: 'same-origin'
                    }).then(function (response) {
                        return response.json();
                    }).then(function (data) {
                        if (!data.success) {
                            resetSlots(data.message || '<?php echo addslashes(__('No slots available for this date.')); ?>');
                            return;
                        }
                        feedback.textContent = '<?php echo addslashes(__('Choose one of the available times below.')); ?>';
                        renderSlots(data.slots || []);
                    }).catch(function () {
                        resetSlots('<?php echo addslashes(__('Unable to load slots right now. Please try another date.')); ?>');
                    });
                }

                if (serviceSelect) {
                    serviceSelect.addEventListener('change', loadSlots);
                }
                if (dateInput) {
                    dateInput.addEventListener('change', loadSlots);
                }

                bookingForm.addEventListener('submit', function (event) {
                    if (!bookingStartInput.value) {
                        event.preventDefault();
                        feedback.textContent = '<?php echo addslashes(__('Please choose an available slot before continuing.')); ?>';
                    }
                });
            }

            bookingForms.forEach(setupForm);
        })();
    </script>
<?php } ?>
<?php overall_footer(); ?>
