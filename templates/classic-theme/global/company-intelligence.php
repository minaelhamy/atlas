<?php
overall_header(__("Company Intelligence"));
$websiteSnapshot = !empty($social_profile['website_snapshot']) && is_array($social_profile['website_snapshot']) ? $social_profile['website_snapshot'] : [];
$moodboardImages = !empty($social_profile['moodboard_images']) && is_array($social_profile['moodboard_images']) ? $social_profile['moodboard_images'] : [];
$topProblemsSolved = !empty($social_profile['top_problems_solved']) ? implode("\n", $social_profile['top_problems_solved']) : '';
$uniqueSellingPoints = !empty($social_profile['unique_selling_points']) ? implode("\n", $social_profile['unique_selling_points']) : '';
$brandColors = !empty($social_profile['brand_colors']) ? implode("\n", $social_profile['brand_colors']) : '';
$referenceBrands = !empty($social_profile['reference_brands']) ? implode("\n", $social_profile['reference_brands']) : '';
$competitors = !empty($social_profile['competitors']) ? implode("\n", $social_profile['competitors']) : '';
$toneAttributes = !empty($social_profile['tone_attributes']) ? $social_profile['tone_attributes'] : [];
$visualMood = !empty($social_profile['visual_mood']) ? $social_profile['visual_mood'] : [];
$allToneOptions = ['Bold & direct', 'Educational', 'Playful', 'Inspirational', 'Grounded / real', 'Professional', 'Conversational', 'Minimal / quiet'];
$allVisualMoodOptions = ['Dark & editorial', 'Warm & minimal', 'Documentary grid', 'Soft lifestyle', 'Bold & direct'];
?>
<style>
.ci-wrap{background:#f2f0eb;padding:28px 20px 40px;min-height:100vh}
.ci-shell{background:#fff;border:.5px solid #e0ddd6;border-radius:16px;max-width:780px;margin:0 auto;overflow:hidden}
.ci-hdr{padding:24px 28px 20px;border-bottom:.5px solid #eee}
.ci-hdr-title{font-size:17px;font-weight:600;color:#1a1a1a;margin-bottom:4px;letter-spacing:-.01em}
.ci-hdr-sub{font-size:13px;color:#888;line-height:1.5;margin-bottom:18px}
.ci-steps{display:flex;align-items:center}
.ci-step{display:flex;align-items:center;gap:7px;font-size:12.5px;font-weight:500;color:#bbb;flex:1}
.ci-step.active{color:#1a1a1a}
.ci-step.done{color:#2FAF49}
.ci-step-num{width:24px;height:24px;border-radius:50%;border:1.5px solid #e0ddd6;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:600;background:#fff;flex-shrink:0;color:#bbb}
.ci-step.active .ci-step-num{border-color:#1a1a1a;background:#1a1a1a;color:#fff}
.ci-step.done .ci-step-num{border-color:#2FAF49;background:#2FAF49;color:#fff}
.ci-step-connector{flex:1;height:1px;background:#e0ddd6;margin:0 10px;max-width:60px}
.ci-body{padding:24px 28px}
.ci-panel{display:none}
.ci-panel.active{display:block}
.ci-ai-zone{border:1.5px dashed #c8a8c4;border-radius:12px;padding:18px 20px;margin-bottom:22px;background:#fdf8fd}
.ci-ai-badge{display:inline-flex;align-items:center;gap:5px;font-size:10px;font-weight:700;color:#871F7A;background:#f5eaf4;border:1px solid #e0d0de;border-radius:20px;padding:3px 10px;letter-spacing:.06em;text-transform:uppercase;margin-bottom:10px}
.ci-ai-zone-title{font-size:13.5px;font-weight:600;color:#1a1a1a;margin-bottom:3px}
.ci-ai-zone-sub{font-size:12px;color:#888;line-height:1.5;margin-bottom:14px}
.ci-ai-input-row{display:flex;gap:8px}
.ci-ai-input{flex:1;height:42px;border:1px solid #e0ddd6;border-radius:9px;padding:0 14px;font-size:13px;font-family:inherit;color:#1a1a1a;outline:none;background:#fff}
.ci-ai-input:focus{border-color:#871F7A}
.ci-ai-input::placeholder{color:#bbb}
.ci-btn-atlas{height:42px;padding:0 20px;background:#f5eaf4;color:#871F7A;border:1px solid #e0d0de;border-radius:9px;font-size:12.5px;font-weight:600;font-family:inherit;cursor:pointer;white-space:nowrap}
.ci-btn-atlas:hover{background:#eddceb}
.ci-ai-note{font-size:11px;color:#aaa;margin-top:8px;display:flex;align-items:center;gap:4px}
.ci-divider-label{display:flex;align-items:center;gap:10px;margin:20px 0}
.ci-divider-label::before,.ci-divider-label::after{content:'';flex:1;height:.5px;background:#e8e6e0}
.ci-divider-label span{font-size:10.5px;color:#bbb;font-weight:600;letter-spacing:.09em;text-transform:uppercase;white-space:nowrap}
.ci-field-group{margin-bottom:20px}
.ci-field-label{font-size:12px;font-weight:600;color:#555;margin-bottom:4px;display:flex;align-items:center;gap:6px}
.ci-field-badge{font-size:10px;color:#bbb;background:#f4f2ee;border-radius:4px;padding:1px 6px;font-weight:500}
.ci-field-hint{font-size:11.5px;color:#aaa;margin-bottom:8px;line-height:1.4}
.ci-generate-row{display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:8px}
.ci-generate-row .ci-field-label,.ci-generate-row .ci-field-hint{margin-bottom:0}
.ci-generate-copy{flex:1}
.ci-generate-btn{height:34px;padding:0 14px;background:#f5eaf4;border:1px solid #e0d0de;border-radius:8px;font-size:11.5px;font-weight:600;color:#871F7A;font-family:inherit;cursor:pointer;white-space:nowrap}
.ci-generate-btn:hover{background:#eddceb}
.ci-textarea,.ci-input{width:100%;border:1px solid #e0ddd6;border-radius:9px;font-size:13px;font-family:inherit;color:#1a1a1a;background:#fff;outline:none;transition:border-color .15s}
.ci-textarea{padding:11px 14px;resize:vertical;line-height:1.6}
.ci-input{height:40px;padding:0 14px}
.ci-textarea:focus,.ci-input:focus{border-color:#c8c4bc}
.ci-textarea::placeholder,.ci-input::placeholder{color:#c8c8c8}
.ci-tag-field{border:1px solid #e0ddd6;border-radius:9px;padding:8px 10px;background:#fff;min-height:44px;display:flex;flex-wrap:wrap;gap:6px;align-items:flex-start;cursor:text;transition:border-color .15s}
.ci-tag-field:focus-within{border-color:#c8c4bc}
.ci-tag{display:inline-flex;align-items:center;gap:5px;background:#edf5ee;border:.5px solid #c8e0cc;border-radius:6px;padding:4px 10px;font-size:12px;color:#2d6636}
.ci-tag button{background:none;border:none;cursor:pointer;color:#a0c8a8;padding:0;font-size:14px;line-height:1}
.ci-tag button:hover{color:#2d6636}
.ci-tag-input{border:none;outline:none;font-size:12.5px;font-family:inherit;color:#1a1a1a;background:transparent;min-width:120px;flex:1}
.ci-tag-input::placeholder{color:#c8c8c8}
.ci-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.ci-section-block{margin-bottom:32px}
.ci-section-num{font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#ccc;margin-bottom:3px}
.ci-section-title-row{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:4px}
.ci-section-title{font-size:14px;font-weight:600;color:#1a1a1a;letter-spacing:-.01em}
.ci-section-sub{font-size:12.5px;color:#888;line-height:1.5;margin-bottom:14px}
.ci-opt-badge{font-size:10px;color:#aaa;background:#f4f2ee;border:.5px solid #e0ddd6;border-radius:4px;padding:1px 7px;font-weight:500;margin-left:6px;letter-spacing:.02em;vertical-align:middle}
.ci-color-zone{background:#faf9f5;border:1px solid #e8e5de;border-radius:12px;padding:16px}
.ci-swatches-row{display:flex;align-items:flex-end;gap:10px;flex-wrap:wrap}
.ci-swatch-item{display:flex;flex-direction:column;align-items:center;gap:5px;cursor:pointer;position:relative}
.ci-swatch-circle{width:42px;height:42px;border-radius:50%;border:2px solid transparent;transition:transform .15s,border-color .15s}
.ci-swatch-item.selected .ci-swatch-circle{border-color:#1a1a1a;transform:scale(1.08)}
.ci-swatch-hex{font-size:9.5px;color:#aaa;font-weight:500;letter-spacing:.04em}
.ci-upload-area{border:1.5px dashed #e0ddd6;border-radius:10px;padding:28px 20px;text-align:center;background:#fafaf8}
.ci-upload-sub{font-size:11.5px;color:#bbb;margin-top:6px}
.ci-pill-row{display:flex;flex-wrap:wrap;gap:8px}
.ci-pill{font-size:12.5px;font-weight:500;padding:7px 16px;border:1.5px solid #e0ddd6;border-radius:20px;cursor:pointer;color:#666;background:#fff;font-family:inherit;transition:all .15s}
.ci-pill:hover{border-color:#aaa;color:#333}
.ci-pill.selected{border-color:#1a1a1a;background:#1a1a1a;color:#fff}
.ci-brand-input-wrap{border:1px solid #e0ddd6;border-radius:9px;padding:0 14px;background:#fff;height:44px;display:flex;align-items:center;transition:border-color .15s}
.ci-brand-input-wrap:focus-within{border-color:#c8c4bc}
.ci-brand-input{border:none;outline:none;font-size:13px;font-family:inherit;color:#1a1a1a;background:transparent;width:100%}
.ci-brand-input::placeholder{color:#c8c8c8}
.ci-moodboard{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px;margin-top:12px}
.ci-moodboard img{width:100%;aspect-ratio:1/1;object-fit:cover;border-radius:10px;border:1px solid #e8e5de}
.ci-review-strip{background:#f5f0ff;border:1px solid #d8cced;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:14px;margin-bottom:22px}
.ci-review-strip-body{flex:1;min-width:0}
.ci-review-strip-title{font-size:13.5px;font-weight:600;color:#2d0a2a;margin-bottom:2px;letter-spacing:-.01em}
.ci-review-strip-sub{font-size:12px;color:#7a4a78;line-height:1.5}
.ci-website-row{display:flex;align-items:center;gap:8px;margin-bottom:16px;padding:10px 14px;background:#faf9f5;border:1px solid #e8e5de;border-radius:10px}
.ci-website-url{font-size:13px;font-weight:500;color:#3E93E8;flex:1}
.ci-website-meta{font-size:11px;color:#aaa;display:flex;align-items:center;gap:4px;white-space:nowrap}
.ci-review-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px}
.ci-review-card{border:1px solid #e8e5de;border-radius:10px;overflow:hidden}
.ci-review-card.full-width{grid-column:1/-1}
.ci-review-head{display:flex;align-items:center;justify-content:space-between;padding:10px 13px;background:#faf9f5;border-bottom:.5px solid #e8e5de}
.ci-review-label{font-size:9.5px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:#aaa}
.ci-review-body{padding:12px 13px}
.ci-review-text{font-size:12.5px;color:#444;line-height:1.6}
.ci-review-tags{display:flex;flex-wrap:wrap;gap:5px}
.ci-review-tag{font-size:11.5px;background:#f2f0eb;border:.5px solid #e0ddd6;border-radius:5px;padding:3px 9px;color:#555}
.ci-review-tag.green{background:#edf5ee;border-color:#c8e0cc;color:#2d6636}
.ci-color-swatches-preview{display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:10px}
.ci-color-swatch{display:flex;flex-direction:column;align-items:center;gap:4px}
.ci-color-swatch-circle{width:32px;height:32px;border-radius:50%}
.ci-color-swatch-hex{font-size:9px;color:#aaa;font-weight:500;letter-spacing:.03em}
.ci-footer-bar{display:flex;align-items:center;justify-content:space-between;padding:18px 28px;border-top:.5px solid #eee;background:#fafaf8;gap:12px}
.ci-btn-back{height:38px;padding:0 18px;background:transparent;color:#888;border:1px solid #e0ddd6;border-radius:8px;font-size:13px;font-weight:500;font-family:inherit;cursor:pointer}
.ci-btn-back:hover{background:#f0eee8}
.ci-btn-next{height:38px;padding:0 22px;background:#1a1a1a;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;font-family:inherit;cursor:pointer}
.ci-btn-next:hover{background:#333}
.ci-footer-note{font-size:11.5px;color:#bbb;display:flex;flex-direction:column;gap:4px;text-align:center}
.ci-save-state{font-size:11px;color:#2FAF49;font-weight:500}
.ci-hidden{display:none!important}
.ci-spinner{opacity:.7;pointer-events:none}
@media (max-width: 991px){
  .ci-grid-2,.ci-review-grid{grid-template-columns:1fr}
  .ci-ai-input-row,.ci-footer-bar,.ci-section-title-row,.ci-review-strip{flex-direction:column;align-items:stretch}
  .ci-footer-note{text-align:left}
}
</style>
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

            <div class="ci-wrap">
                <div class="ci-shell">
                    <div class="ci-hdr">
                        <div class="ci-hdr-title" id="ci-header-title"><?php _e("Tell us about your business") ?></div>
                        <div class="ci-hdr-sub" id="ci-header-sub"><?php _e("This takes 2 minutes. Your answers power everything Atlas generates.") ?></div>
                        <div class="ci-steps">
                            <div class="ci-step active" data-ci-step="1"><div class="ci-step-num">1</div><span><?php _e("Business info") ?></span></div>
                            <div class="ci-step-connector"></div>
                            <div class="ci-step" data-ci-step="2"><div class="ci-step-num">2</div><span><?php _e("Brand guidelines") ?></span></div>
                            <div class="ci-step-connector"></div>
                            <div class="ci-step" data-ci-step="3"><div class="ci-step-num">3</div><span><?php _e("Review") ?></span></div>
                        </div>
                    </div>

                    <form method="post" enctype="multipart/form-data" id="company-intelligence-form">
                        <input type="hidden" name="website_snapshot_json" id="website_snapshot_json" value="<?php echo !empty($websiteSnapshot) ? _esc(json_encode($websiteSnapshot), 0) : ''; ?>">
                        <input type="hidden" name="website_extracted_at" id="website_extracted_at" value="<?php _esc(!empty($social_profile['website_extracted_at']) ? $social_profile['website_extracted_at'] : '') ?>">
                        <input type="hidden" name="existing_moodboard_images" id="existing_moodboard_images" value="<?php _esc(implode("\n", $moodboardImages)) ?>">

                        <div class="ci-body">
                            <div class="ci-panel active" data-panel="1">
                                <div class="ci-ai-zone">
                                    <div class="ci-ai-badge"><?php _e("AI Extract") ?></div>
                                    <div class="ci-ai-zone-title"><?php _e("Let Atlas read your website and fill this in") ?></div>
                                    <div class="ci-ai-zone-sub"><?php _e("Paste your URL and Atlas will extract your ICP, problems, USPs, and company summary automatically. You can edit everything after.") ?></div>
                                    <div class="ci-ai-input-row">
                                        <input class="ci-ai-input" type="text" name="company_website" id="company_website" value="<?php _esc($social_profile['company_website']) ?>" placeholder="https://yourwebsite.com">
                                        <button type="button" class="ci-btn-atlas atlas-extract-button"><?php _e("Generate with Atlas") ?></button>
                                    </div>
                                    <div class="ci-ai-note"><?php _e("Your site is only read for extraction and profile building."); ?></div>
                                </div>

                                <div class="ci-divider-label"><span><?php _e("or fill in manually") ?></span></div>

                                <div class="ci-field-group">
                                    <div class="ci-generate-row">
                                        <div class="ci-generate-copy">
                                            <div class="ci-field-label"><?php _e("Company description") ?></div>
                                            <div class="ci-field-hint"><?php _e("What does your company do and who is it for? 2–4 sentences.") ?></div>
                                        </div>
                                        <button type="button" class="ci-generate-btn" data-generate-field="company_description"><?php _e("Generate with Atlas") ?></button>
                                    </div>
                                    <textarea class="ci-textarea" rows="3" name="company_description" id="company_description" placeholder="<?php _e("Describe what your company does, who it serves, and why it matters.") ?>"><?php _esc($social_profile['company_description']) ?></textarea>
                                </div>

                                <div class="ci-field-group">
                                    <div class="ci-generate-row">
                                        <div class="ci-generate-copy">
                                            <div class="ci-field-label"><?php _e("Ideal customer profile (ICP)") ?></div>
                                            <div class="ci-field-hint"><?php _e("Who is your best customer? Think job title, situation, goals, and pain points.") ?></div>
                                        </div>
                                        <button type="button" class="ci-generate-btn" data-generate-field="ideal_customer_profile"><?php _e("Generate with Atlas") ?></button>
                                    </div>
                                    <textarea class="ci-textarea" rows="3" name="ideal_customer_profile" id="ideal_customer_profile" placeholder="<?php _e("e.g. independent musicians with 1K–25K followers who struggle to monetize beyond streaming...") ?>"><?php _esc($social_profile['ideal_customer_profile']) ?></textarea>
                                </div>

                                <div class="ci-field-group">
                                    <div class="ci-generate-row">
                                        <div class="ci-generate-copy">
                                            <div class="ci-field-label"><?php _e("Top problems you solve") ?> <span class="ci-field-badge">3–5</span></div>
                                            <div class="ci-field-hint"><?php _e("Press Enter after each problem to add it as a tag.") ?></div>
                                        </div>
                                        <button type="button" class="ci-generate-btn" data-generate-field="top_problems_solved"><?php _e("Generate with Atlas") ?></button>
                                    </div>
                                    <div class="ci-tag-field" data-target="top_problems_solved">
                                        <textarea class="ci-hidden" name="top_problems_solved" id="top_problems_solved"><?php _esc($topProblemsSolved) ?></textarea>
                                        <input class="ci-tag-input" type="text" placeholder="<?php _e("Add a problem...") ?>">
                                    </div>
                                </div>

                                <div class="ci-field-group">
                                    <div class="ci-generate-row">
                                        <div class="ci-generate-copy">
                                            <div class="ci-field-label"><?php _e("Unique selling points (USPs)") ?> <span class="ci-field-badge">3–5</span></div>
                                            <div class="ci-field-hint"><?php _e("What makes you different or better than the alternatives?") ?></div>
                                        </div>
                                        <button type="button" class="ci-generate-btn" data-generate-field="unique_selling_points"><?php _e("Generate with Atlas") ?></button>
                                    </div>
                                    <div class="ci-tag-field" data-target="unique_selling_points">
                                        <textarea class="ci-hidden" name="unique_selling_points" id="unique_selling_points"><?php _esc($uniqueSellingPoints) ?></textarea>
                                        <input class="ci-tag-input" type="text" placeholder="<?php _e("Add a USP...") ?>">
                                    </div>
                                </div>

                                <div class="ci-divider-label"><span><?php _e("optional") ?></span></div>

                                <div class="ci-field-group">
                                    <div class="ci-field-label"><?php _e("Competitor websites") ?> <span class="ci-field-badge"><?php _e("optional") ?></span></div>
                                    <div class="ci-field-hint"><?php _e("We'll analyse their positioning to sharpen yours. Add up to 5 URLs.") ?></div>
                                    <div class="ci-tag-field" data-target="competitors">
                                        <textarea class="ci-hidden" name="competitors" id="competitors"><?php _esc($competitors) ?></textarea>
                                        <input class="ci-tag-input" type="text" placeholder="<?php _e("Add a competitor URL...") ?>">
                                    </div>
                                </div>

                                <div class="ci-grid-2">
                                    <div class="ci-field-group">
                                        <div class="ci-field-label"><?php _e("Company name") ?></div>
                                        <input class="ci-input" type="text" name="company_name" id="company_name" value="<?php _esc($social_profile['company_name']) ?>">
                                    </div>
                                    <div class="ci-field-group">
                                        <div class="ci-field-label"><?php _e("Industry") ?></div>
                                        <input class="ci-input" type="text" name="company_industry" id="company_industry" value="<?php _esc($social_profile['company_industry']) ?>">
                                    </div>
                                    <div class="ci-field-group">
                                        <div class="ci-field-label"><?php _e("Founder name") ?></div>
                                        <input class="ci-input" type="text" name="founder_name" id="founder_name" value="<?php _esc($social_profile['founder_name']) ?>">
                                    </div>
                                    <div class="ci-field-group">
                                        <div class="ci-field-label"><?php _e("Founder title") ?></div>
                                        <input class="ci-input" type="text" name="founder_title" id="founder_title" value="<?php _esc($social_profile['founder_title']) ?>">
                                    </div>
                                </div>

                                <textarea class="ci-hidden" name="target_audience" id="target_audience"><?php _esc($social_profile['target_audience']) ?></textarea>
                                <textarea class="ci-hidden" name="content_goals" id="content_goals"><?php _esc($social_profile['content_goals']) ?></textarea>
                                <textarea class="ci-hidden" name="key_products" id="key_products"><?php _esc($social_profile['key_products']) ?></textarea>
                                <textarea class="ci-hidden" name="differentiators" id="differentiators"><?php _esc($social_profile['differentiators']) ?></textarea>
                                <textarea class="ci-hidden" name="competitor_notes" id="competitor_notes"><?php _esc($social_profile['competitor_notes']) ?></textarea>
                            </div>

                            <div class="ci-panel" data-panel="2">
                                <div class="ci-section-block">
                                    <div class="ci-section-num">01 — <?php _e("Brand colors") ?></div>
                                    <div class="ci-section-title-row">
                                        <div>
                                            <div class="ci-section-title"><?php _e("Confirm your brand colors") ?></div>
                                            <div class="ci-section-sub"><?php _e("Pick the colors Atlas should keep using across social media generation and your website.") ?></div>
                                        </div>
                                        <button type="button" class="ci-generate-btn" data-generate-field="brand_colors"><?php _e("Generate with Atlas") ?></button>
                                    </div>
                                    <div class="ci-color-zone">
                                        <div class="ci-tag-field" data-target="brand_colors">
                                            <textarea class="ci-hidden" name="brand_colors" id="brand_colors"><?php _esc($brandColors) ?></textarea>
                                            <input class="ci-tag-input" type="text" placeholder="#111111">
                                        </div>
                                        <div class="ci-swatches-row" id="atlas-ci-color-preview" style="margin-top:14px;"></div>
                                    </div>
                                </div>

                                <div class="ci-section-block">
                                    <div class="ci-section-num">02 — <?php _e("Logo") ?> <span class="ci-opt-badge"><?php _e("optional") ?></span></div>
                                    <div class="ci-section-title"><?php _e("Upload your logo") ?></div>
                                    <div class="ci-section-sub"><?php _e("We'll use it to preview how your content and website will look before you generate.") ?></div>
                                    <div class="ci-upload-area">
                                        <div><?php _e("Drop your logo here") ?></div>
                                        <div class="ci-upload-sub"><?php _e("SVG, PNG, or JPG — transparent background preferred") ?></div>
                                        <input type="file" name="company_logo" accept="image/*" style="margin-top:10px;">
                                    </div>
                                </div>

                                <div class="ci-section-block">
                                    <div class="ci-section-num">03 — <?php _e("Visual mood") ?></div>
                                    <div class="ci-section-title-row">
                                        <div>
                                            <div class="ci-section-title"><?php _e("Pick a visual mood") ?></div>
                                            <div class="ci-section-sub"><?php _e("Based on your brand, choose the direction Atlas should follow visually.") ?></div>
                                        </div>
                                        <button type="button" class="ci-generate-btn" data-generate-field="visual_mood"><?php _e("Generate with Atlas") ?></button>
                                    </div>
                                    <div class="ci-pill-row" data-pill-target="visual_mood">
                                        <?php foreach ($allVisualMoodOptions as $option) { ?>
                                            <button type="button" class="ci-pill <?php echo in_array($option, $visualMood, true) ? 'selected' : ''; ?>" data-value="<?php _esc($option) ?>"><?php _esc($option) ?></button>
                                        <?php } ?>
                                    </div>
                                    <textarea class="ci-hidden" name="visual_mood" id="visual_mood"><?php _esc(implode("\n", $visualMood)) ?></textarea>
                                </div>

                                <div class="ci-section-block">
                                    <div class="ci-section-num">04 — <?php _e("Tone of voice") ?></div>
                                    <div class="ci-section-title-row">
                                        <div>
                                            <div class="ci-section-title"><?php _e("How should your content sound?") ?></div>
                                            <div class="ci-section-sub"><?php _e("Pick the tone attributes Atlas should use when writing for your brand.") ?></div>
                                        </div>
                                        <button type="button" class="ci-generate-btn" data-generate-field="tone_attributes"><?php _e("Generate with Atlas") ?></button>
                                    </div>
                                    <div class="ci-pill-row" data-pill-target="tone_attributes">
                                        <?php foreach ($allToneOptions as $option) { ?>
                                            <button type="button" class="ci-pill <?php echo in_array($option, $toneAttributes, true) ? 'selected' : ''; ?>" data-value="<?php _esc($option) ?>"><?php _esc($option) ?></button>
                                        <?php } ?>
                                    </div>
                                    <textarea class="ci-hidden" name="tone_attributes" id="tone_attributes"><?php _esc(implode("\n", $toneAttributes)) ?></textarea>
                                </div>

                                <div class="ci-section-block">
                                    <div class="ci-section-num">05 — <?php _e("Reference brands") ?> <span class="ci-opt-badge"><?php _e("optional") ?></span></div>
                                    <div class="ci-section-title-row">
                                        <div>
                                            <div class="ci-section-title"><?php _e("Brands or accounts you admire") ?></div>
                                            <div class="ci-section-sub"><?php _e("Atlas can visit these and learn how they communicate, print, and present themselves.") ?></div>
                                        </div>
                                        <button type="button" class="ci-generate-btn" data-generate-field="reference_brands"><?php _e("Generate with Atlas") ?></button>
                                    </div>
                                    <div class="ci-tag-field" data-target="reference_brands">
                                        <textarea class="ci-hidden" name="reference_brands" id="reference_brands"><?php _esc($referenceBrands) ?></textarea>
                                        <input class="ci-tag-input" type="text" placeholder="<?php _e("@handle, brand name, or website URL — press Enter to add") ?>">
                                    </div>
                                </div>

                                <div class="ci-section-block">
                                    <div class="ci-section-num">06 — <?php _e("Moodboard") ?> <span class="ci-opt-badge"><?php _e("optional") ?></span></div>
                                    <div class="ci-section-title"><?php _e("Images that inspire you") ?></div>
                                    <div class="ci-section-sub"><?php _e("Aesthetic references, campaign posts, or visuals that capture the feeling you’re going for.") ?></div>
                                    <div class="ci-upload-area">
                                        <div><?php _e("Drop images or paste URLs") ?></div>
                                        <div class="ci-upload-sub"><?php _e("PNG, JPG, or any visual reference.") ?></div>
                                        <input type="file" name="moodboard_images[]" multiple accept="image/*" style="margin-top:10px;">
                                    </div>
                                    <?php if (!empty($moodboardImages)) { ?>
                                        <div class="ci-moodboard">
                                            <?php foreach ($moodboardImages as $moodboardImage) { ?>
                                                <img src="<?php echo _esc($config['site_url'], 0) . 'storage/company/' . $moodboardImage; ?>" alt="">
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>

                                <textarea class="ci-hidden" name="brand_voice" id="brand_voice"><?php _esc($social_profile['brand_voice']) ?></textarea>
                                <input class="ci-hidden" type="text" name="instagram_handle" id="instagram_handle" value="<?php _esc($social_profile['instagram_handle']) ?>">
                            </div>

                            <div class="ci-panel" data-panel="3">
                                <div class="ci-review-strip">
                                    <div class="ci-review-strip-body">
                                        <div class="ci-review-strip-title"><?php _e("Ready to generate your marketing statements") ?></div>
                                        <div class="ci-review-strip-sub"><?php _e("Atlas will use this profile to write your hooks, value propositions, AI context, and Instagram content.") ?></div>
                                    </div>
                                    <button type="button" class="ci-btn-atlas intelligence-refresh-btn"><?php _e("Generate with Atlas") ?></button>
                                </div>

                                <div class="ci-website-row">
                                    <div class="ci-website-url" id="review-website"><?php _esc(!empty($social_profile['company_website']) ? $social_profile['company_website'] : __('No website added yet.')) ?></div>
                                    <div class="ci-website-meta"><?php _e("Profile source"); ?></div>
                                    <button type="button" class="ci-generate-btn" data-jump-step="1"><?php _e("Edit") ?></button>
                                </div>

                                <div class="ci-review-grid">
                                    <div class="ci-review-card full-width">
                                        <div class="ci-review-head"><span class="ci-review-label"><?php _e("Company description") ?></span><button type="button" class="ci-generate-btn" data-jump-step="1"><?php _e("Edit") ?></button></div>
                                        <div class="ci-review-body"><div class="ci-review-text" id="review-company-description"><?php _esc(!empty($social_profile['company_description']) ? $social_profile['company_description'] : __('Not added yet.')) ?></div></div>
                                    </div>
                                    <div class="ci-review-card">
                                        <div class="ci-review-head"><span class="ci-review-label"><?php _e("Ideal customer (ICP)") ?></span><button type="button" class="ci-generate-btn" data-jump-step="1"><?php _e("Edit") ?></button></div>
                                        <div class="ci-review-body"><div class="ci-review-text" id="review-icp"><?php _esc(!empty($social_profile['ideal_customer_profile']) ? $social_profile['ideal_customer_profile'] : __('Not added yet.')) ?></div></div>
                                    </div>
                                    <div class="ci-review-card">
                                        <div class="ci-review-head"><span class="ci-review-label"><?php _e("Problems solved") ?></span><button type="button" class="ci-generate-btn" data-jump-step="1"><?php _e("Edit") ?></button></div>
                                        <div class="ci-review-body"><div class="ci-review-tags" id="review-problems"></div></div>
                                    </div>
                                    <div class="ci-review-card">
                                        <div class="ci-review-head"><span class="ci-review-label"><?php _e("USPs") ?></span><button type="button" class="ci-generate-btn" data-jump-step="1"><?php _e("Edit") ?></button></div>
                                        <div class="ci-review-body"><div class="ci-review-tags" id="review-usps"></div></div>
                                    </div>
                                    <div class="ci-review-card">
                                        <div class="ci-review-head"><span class="ci-review-label"><?php _e("Competitors") ?></span><button type="button" class="ci-generate-btn" data-jump-step="1"><?php _e("Edit") ?></button></div>
                                        <div class="ci-review-body"><div class="ci-review-tags" id="review-competitors"></div></div>
                                    </div>
                                    <div class="ci-review-card">
                                        <div class="ci-review-head"><span class="ci-review-label"><?php _e("Brand tone") ?></span><button type="button" class="ci-generate-btn" data-jump-step="2"><?php _e("Edit") ?></button></div>
                                        <div class="ci-review-body"><div class="ci-review-tags" id="review-tone"></div></div>
                                    </div>
                                    <div class="ci-review-card">
                                        <div class="ci-review-head"><span class="ci-review-label"><?php _e("Brand colors") ?></span><button type="button" class="ci-generate-btn" data-jump-step="2"><?php _e("Edit") ?></button></div>
                                        <div class="ci-review-body">
                                            <div class="ci-color-swatches-preview" id="review-colors-swatches"></div>
                                            <div class="ci-review-tags" id="review-colors"></div>
                                        </div>
                                    </div>
                                    <div class="ci-review-card">
                                        <div class="ci-review-head"><span class="ci-review-label"><?php _e("Reference brands") ?></span><button type="button" class="ci-generate-btn" data-jump-step="2"><?php _e("Edit") ?></button></div>
                                        <div class="ci-review-body"><div class="ci-review-tags" id="review-reference-brands"></div></div>
                                    </div>
                                    <div class="ci-review-card full-width">
                                        <div class="ci-review-head"><span class="ci-review-label"><?php _e("Atlas company intelligence") ?></span></div>
                                        <div class="ci-review-body">
                                            <div class="ci-review-text"><strong><?php _e("Company summary") ?>:</strong> <span id="review-company-summary"><?php _esc(!empty($company_intelligence['company_summary']) ? $company_intelligence['company_summary'] : __('Not generated yet.')) ?></span></div>
                                            <div class="ci-review-text" style="margin-top:10px;"><strong><?php _e("Market research") ?>:</strong> <span id="review-market-research"><?php _esc(!empty($company_intelligence['market_research']) ? $company_intelligence['market_research'] : __('Not generated yet.')) ?></span></div>
                                            <div class="ci-review-text" style="margin-top:10px;"><strong><?php _e("Competitive edges") ?>:</strong> <span id="review-competitive-edges"><?php _esc(!empty($company_intelligence['competitive_edges']) ? $company_intelligence['competitive_edges'] : __('Not generated yet.')) ?></span></div>
                                            <div class="ci-review-text" style="margin-top:10px;"><strong><?php _e("Strategic guidance") ?>:</strong> <span id="review-strategic-guidance"><?php _esc(!empty($company_intelligence['strategic_guidance']) ? $company_intelligence['strategic_guidance'] : __('Not generated yet.')) ?></span></div>
                                        </div>
                                    </div>
                                </div>
                                <?php if(!empty($social_error)){ _esc($social_error); } ?>
                            </div>
                        </div>

                        <div class="ci-footer-bar">
                            <button type="button" class="ci-btn-back" id="ci-prev-btn"><?php _e("Back") ?></button>
                            <div class="ci-footer-note">
                                <span id="ci-footer-note"><?php _e("Step 1 of 3 · Progress saved automatically"); ?></span>
                                <span class="ci-save-state" id="ci-save-state"><?php _e("Not saved yet."); ?></span>
                            </div>
                            <div style="display:flex;gap:10px;">
                                <button type="button" class="ci-btn-next" id="ci-next-btn"><?php _e("Save & continue") ?></button>
                                <button type="submit" name="company-intelligence-submit" class="ci-btn-next ci-hidden" id="ci-save-btn"><?php _e("Save profile") ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

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
<script>
$(function () {
    var currentStep = 1;
    var stepTitles = {
        1: {title: "<?php echo addslashes(__('Tell us about your business')); ?>", sub: "<?php echo addslashes(__('This takes 2 minutes. Your answers power everything Atlas generates.')); ?>"},
        2: {title: "<?php echo addslashes(__("What's your visual direction?")); ?>", sub: "<?php echo addslashes(__('We pulled your brand colors from your website. Confirm them, add your logo, then set your tone and references.')); ?>"},
        3: {title: "<?php echo addslashes(__('Review your business profile')); ?>", sub: "<?php echo addslashes(__('Check everything looks right. Edit any section before generating.')); ?>"}
    };

    function parseLines(value) {
        if (!value) return [];
        return value.split(/\n+/).map(function (item) { return $.trim(item); }).filter(Boolean);
    }

    function setSaveState(message, type) {
        var $state = $('#ci-save-state');
        $state.css('color', type === 'error' ? '#c05959' : (type === 'success' ? '#2FAF49' : '#888'));
        $state.text(message);
    }

    function syncTagField($field) {
        var targetId = $field.data('target');
        var values = [];
        $field.find('.ci-tag').each(function () {
            values.push($(this).data('value'));
        });
        $('#' + targetId).val(values.join("\n"));
        if (targetId === 'brand_colors') {
            renderColorPreview(values);
        }
    }

    function addTagValue($field, value) {
        value = $.trim(value || '');
        if (!value) return;
        var exists = false;
        $field.find('.ci-tag').each(function () {
            if ($(this).data('value').toLowerCase() === value.toLowerCase()) {
                exists = true;
            }
        });
        if (exists) return;
        var $tag = $('<span class="ci-tag" data-value=""></span>');
        $tag.attr('data-value', value).append(document.createTextNode(value));
        var $btn = $('<button type="button">×</button>');
        $btn.on('click', function () {
            $tag.remove();
            syncTagField($field);
            updateReview();
        });
        $tag.append($btn);
        $field.find('.ci-tag-input').before($tag);
        syncTagField($field);
    }

    function initTagField($field) {
        var targetId = $field.data('target');
        $field.find('.ci-tag').remove();
        parseLines($('#' + targetId).val()).forEach(function (value) {
            addTagValue($field, value);
        });
        $field.off('click').on('click', function () {
            $field.find('.ci-tag-input').trigger('focus');
        });
        $field.find('.ci-tag-input').off('keydown').on('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addTagValue($field, $(this).val());
                $(this).val('');
                updateReview();
            }
        });
    }

    function renderColorPreview(colors) {
        var $wrap = $('#atlas-ci-color-preview');
        $wrap.empty();
        colors.forEach(function (color) {
            var $item = $('<div class="ci-swatch-item selected"></div>');
            $item.append('<div class="ci-swatch-circle" style="background:' + color + ';border-color:' + color + ';"></div>');
            $item.append('<div class="ci-swatch-hex">' + color + '</div>');
            $wrap.append($item);
        });

        var $review = $('#review-colors-swatches');
        $review.empty();
        colors.forEach(function (color) {
            $review.append('<div class="ci-color-swatch"><div class="ci-color-swatch-circle" style="background:' + color + ';border:1px solid rgba(0,0,0,.08)"></div><div class="ci-color-swatch-hex">' + color + '</div></div>');
        });
    }

    function syncPillTarget(targetId) {
        var values = [];
        $('[data-pill-target="' + targetId + '"] .ci-pill.selected').each(function () {
            values.push($(this).data('value'));
        });
        $('#' + targetId).val(values.join("\n"));
    }

    function renderReviewTags(selector, values, green) {
        var $wrap = $(selector);
        $wrap.empty();
        if (!values.length) {
            $wrap.append('<span class="ci-review-text"><?php echo addslashes(__('Not added yet.')); ?></span>');
            return;
        }
        values.forEach(function (value) {
            $wrap.append('<span class="ci-review-tag' + (green ? ' green' : '') + '">' + $('<div>').text(value).html() + '</span>');
        });
    }

    function updateReview() {
        $('#review-website').text($('#company_website').val() || "<?php echo addslashes(__('No website added yet.')); ?>");
        $('#review-company-description').text($('#company_description').val() || "<?php echo addslashes(__('Not added yet.')); ?>");
        $('#review-icp').text($('#ideal_customer_profile').val() || "<?php echo addslashes(__('Not added yet.')); ?>");
        renderReviewTags('#review-problems', parseLines($('#top_problems_solved').val()), true);
        renderReviewTags('#review-usps', parseLines($('#unique_selling_points').val()), false);
        renderReviewTags('#review-competitors', parseLines($('#competitors').val()), false);
        renderReviewTags('#review-reference-brands', parseLines($('#reference_brands').val()), false);
        renderReviewTags('#review-tone', parseLines($('#tone_attributes').val()).concat(parseLines($('#visual_mood').val())), false);
        renderReviewTags('#review-colors', parseLines($('#brand_colors').val()), false);
    }

    function setStep(step) {
        currentStep = step;
        $('.ci-panel').removeClass('active');
        $('.ci-panel[data-panel="' + step + '"]').addClass('active');
        $('.ci-step').removeClass('active done');
        $('.ci-step').each(function () {
            var idx = parseInt($(this).data('ci-step'), 10);
            if (idx < step) $(this).addClass('done');
            else if (idx === step) $(this).addClass('active');
        });
        $('#ci-header-title').text(stepTitles[step].title);
        $('#ci-header-sub').text(stepTitles[step].sub);
        $('#ci-footer-note').text('<?php echo addslashes(__('Step')); ?> ' + step + ' <?php echo addslashes(__('of 3 · Progress saved automatically')); ?>');
        $('#ci-prev-btn').toggle(step > 1);
        $('#ci-next-btn').toggle(step < 3);
        $('#ci-save-btn').toggleClass('ci-hidden', step !== 3);
        if (step === 3) {
            updateReview();
        }
    }

    function validateStep(step) {
        if (step === 1) {
            if ($.trim($('#company_description').val()) === '' || $.trim($('#ideal_customer_profile').val()) === '' || !parseLines($('#top_problems_solved').val()).length || !parseLines($('#unique_selling_points').val()).length) {
                quick_alert("<?php echo addslashes(__('Please complete the key business information before continuing.')); ?>", 'error');
                return false;
            }
        }
        if (step === 2) {
            if (!parseLines($('#brand_colors').val()).length || !parseLines($('#visual_mood').val()).length || !parseLines($('#tone_attributes').val()).length) {
                quick_alert("<?php echo addslashes(__('Please complete your brand guidelines before continuing.')); ?>", 'error');
                return false;
            }
        }
        return true;
    }

    function saveDraft(step, done) {
        var formData = new FormData(document.getElementById('company-intelligence-form'));
        formData.append('step', step);
        setSaveState("<?php echo addslashes(__('Saving...')); ?>", '');
        $.ajax({
            url: ajaxurl + '?action=save_company_intelligence_draft',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false
        }).done(function (response) {
            response = typeof response === 'string' ? JSON.parse(response) : response;
            if (!response.success) {
                setSaveState(response.error || "<?php echo addslashes(__('Save failed.')); ?>", 'error');
                quick_alert(response.error || "<?php echo addslashes(__('Atlas could not save this step right now.')); ?>", 'error');
                return;
            }
            if (response.profile && response.profile.moodboard_images) {
                $('#existing_moodboard_images').val(response.profile.moodboard_images.join("\n"));
            }
            setSaveState(response.message || "<?php echo addslashes(__('Saved')); ?>", 'success');
            if (typeof done === 'function') {
                done();
            }
        }).fail(function () {
            setSaveState("<?php echo addslashes(__('Save failed.')); ?>", 'error');
            quick_alert("<?php echo addslashes(__('Atlas could not save this step right now.')); ?>", 'error');
        });
    }

    $('.ci-tag-field').each(function () {
        initTagField($(this));
    });

    $('[data-pill-target="visual_mood"]').on('click', '.ci-pill', function () {
        $(this).toggleClass('selected');
        syncPillTarget('visual_mood');
        updateReview();
    });

    $('[data-pill-target="tone_attributes"]').on('click', '.ci-pill', function () {
        $(this).toggleClass('selected');
        syncPillTarget('tone_attributes');
        updateReview();
    });

    $('#ci-prev-btn').on('click', function () {
        if (currentStep > 1) setStep(currentStep - 1);
    });

    $('#ci-next-btn').on('click', function () {
        if (!validateStep(currentStep)) return;
        saveDraft(currentStep, function () {
            if (currentStep < 3) {
                setStep(currentStep + 1);
            }
        });
    });

    $(document).on('click', '[data-jump-step]', function () {
        setStep(parseInt($(this).data('jump-step'), 10));
    });

    function serializeContext(field) {
        return $('#company-intelligence-form').serialize() + '&field=' + encodeURIComponent(field);
    }

    $('.atlas-extract-button').on('click', function (e) {
        e.preventDefault();
        var $btn = $(this);
        $btn.addClass('ci-spinner').prop('disabled', true).text('<?php echo addslashes(__('Generating...')); ?>');
        $.post(ajaxurl + '?action=extract_company_profile', {website: $('#company_website').val()}, function (response) {
            response = typeof response === 'string' ? JSON.parse(response) : response;
            if (!response.success) {
                quick_alert(response.error || "<?php echo addslashes(__('Unable to extract your website right now.')); ?>", 'error');
                return;
            }
            var p = response.profile || {};
            if (p.company_website) $('#company_website').val(p.company_website);
            if (p.company_name) $('#company_name').val(p.company_name);
            if (p.company_industry) $('#company_industry').val(p.company_industry);
            if (p.company_description) $('#company_description').val(p.company_description);
            if (p.ideal_customer_profile) $('#ideal_customer_profile').val(p.ideal_customer_profile);
            if (p.website_snapshot) $('#website_snapshot_json').val(JSON.stringify(p.website_snapshot));
            if (p.website_extracted_at) $('#website_extracted_at').val(p.website_extracted_at);
            ['top_problems_solved','unique_selling_points','competitors','reference_brands','brand_colors'].forEach(function (field) {
                if (p[field]) {
                    $('#' + field).val((p[field] || []).join("\n"));
                    initTagField($('[data-target="' + field + '"]'));
                }
            });
            if (p.visual_mood) {
                $('#visual_mood').val((p.visual_mood || []).join("\n"));
                syncGeneratedPills('visual_mood');
            }
            if (p.tone_attributes) {
                $('#tone_attributes').val((p.tone_attributes || []).join("\n"));
                syncGeneratedPills('tone_attributes');
            }
            renderColorPreview(parseLines($('#brand_colors').val()));
            updateReview();
            quick_alert(response.message || "<?php echo addslashes(__('Website extracted successfully.')); ?>", 'success');
        }).fail(function () {
            quick_alert("<?php echo addslashes(__('Unable to extract your website right now.')); ?>", 'error');
        }).always(function () {
            $btn.removeClass('ci-spinner').prop('disabled', false).text('<?php echo addslashes(__('Generate with Atlas')); ?>');
        });
    });

    function syncGeneratedPills(field) {
        var values = parseLines($('#' + field).val());
        $('[data-pill-target="' + field + '"] .ci-pill').each(function () {
            $(this).toggleClass('selected', values.indexOf($(this).data('value')) !== -1);
        });
    }

    $(document).on('click', '[data-generate-field]', function () {
        var field = $(this).data('generate-field');
        var $btn = $(this);
        $btn.addClass('ci-spinner').prop('disabled', true).text('<?php echo addslashes(__('Generating...')); ?>');
        $.post(ajaxurl + '?action=generate_company_intelligence_field', serializeContext(field), function (response) {
            response = typeof response === 'string' ? JSON.parse(response) : response;
            if (!response.success) {
                quick_alert(response.error || "<?php echo addslashes(__('Atlas could not generate this field right now.')); ?>", 'error');
                return;
            }
            var value = response.value;
            if (field === 'company_description' || field === 'ideal_customer_profile') {
                $('#' + field).val(value || '');
            } else if (field === 'top_problems_solved' || field === 'unique_selling_points' || field === 'brand_colors' || field === 'reference_brands' || field === 'competitors') {
                $('#' + field).val((value || []).join("\n"));
                initTagField($('[data-target="' + field + '"]'));
            } else if (field === 'visual_mood' || field === 'tone_attributes') {
                $('#' + field).val((value || []).join("\n"));
                syncGeneratedPills(field);
            }
            renderColorPreview(parseLines($('#brand_colors').val()));
            updateReview();
        }).fail(function () {
            quick_alert("<?php echo addslashes(__('Atlas could not generate this field right now.')); ?>", 'error');
        }).always(function () {
            $btn.removeClass('ci-spinner').prop('disabled', false).text('<?php echo addslashes(__('Generate with Atlas')); ?>');
        });
    });

    $('.intelligence-refresh-btn').on('click', function (e) {
        e.preventDefault();
        var $btn = $(this);
        $btn.addClass('ci-spinner').prop('disabled', true).text('<?php echo addslashes(__('Generating...')); ?>');
        $.post(ajaxurl + '?action=refresh_company_intelligence', {}, function (response) {
            response = typeof response === 'string' ? JSON.parse(response) : response;
            if (!response.success) {
                quick_alert(response.error || "<?php echo addslashes(__('Unable to refresh company intelligence right now.')); ?>", 'error');
                return;
            }
            if (response.intelligence) {
                $('#review-company-summary').text(response.intelligence.company_summary || '');
                $('#review-market-research').text(response.intelligence.market_research || '');
                $('#review-competitive-edges').text(response.intelligence.competitive_edges || '');
                $('#review-strategic-guidance').text(response.intelligence.strategic_guidance || '');
            }
            quick_alert(response.message || "<?php echo addslashes(__('Company intelligence refreshed successfully.')); ?>", 'success');
        }).fail(function () {
            quick_alert("<?php echo addslashes(__('Unable to refresh company intelligence right now.')); ?>", 'error');
        }).always(function () {
            $btn.removeClass('ci-spinner').prop('disabled', false).text('<?php echo addslashes(__('Generate with Atlas')); ?>');
        });
    });

    $('#company-intelligence-form').on('input change', 'input, textarea', function () {
        updateReview();
        setSaveState("<?php echo addslashes(__('Unsaved changes')); ?>", '');
    });

    renderColorPreview(parseLines($('#brand_colors').val()));
    syncPillTarget('visual_mood');
    syncPillTarget('tone_attributes');
    syncGeneratedPills('visual_mood');
    syncGeneratedPills('tone_attributes');
    updateReview();
    setStep(1);
});
</script>
<?php
overall_footer();
