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
.ci-flow{max-width:980px;margin:0 auto}
.ci-shell{background:#fff;border:1px solid #ebe6dc;border-radius:28px;box-shadow:0 18px 44px rgba(31,27,18,.05);overflow:hidden}
.ci-header{padding:28px 32px 24px;border-bottom:1px solid #f0ece4}
.ci-kicker{font-size:11px;font-weight:700;letter-spacing:.12em;color:#b6ada0;text-transform:uppercase;margin-bottom:8px}
.ci-title{font-size:30px;line-height:1.04;letter-spacing:-.04em;font-weight:700;color:#1e1d1a;margin-bottom:8px}
.ci-sub{font-size:14px;line-height:1.6;color:#8d8477;max-width:720px}
.ci-stepbar{display:flex;align-items:center;gap:14px;margin-top:22px;flex-wrap:wrap}
.ci-step{display:flex;align-items:center;gap:10px;font-size:13px;font-weight:600;color:#b7aea1}
.ci-step-circle{width:28px;height:28px;border-radius:50%;border:1px solid #e7e1d7;display:flex;align-items:center;justify-content:center;background:#fff;color:#b7aea1;font-size:11px;font-weight:700}
.ci-step-badge{display:none;font-size:10px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#34a853;background:#edf7ef;border:1px solid #d4e7d7;border-radius:999px;padding:4px 8px;line-height:1}
.ci-step.active{color:#1e1d1a}
.ci-step.active .ci-step-circle{background:#1e1d1a;border-color:#1e1d1a;color:#fff}
.ci-step.done{color:#34a853}
.ci-step.done .ci-step-circle{background:#34a853;border-color:#34a853;color:#fff}
.ci-step.done .ci-step-badge{display:inline-flex}
.ci-step-line{flex:1 1 48px;min-width:28px;height:1px;background:#ece7df}
.ci-body{padding:28px 32px 30px}
.ci-panel{display:none}
.ci-panel.active{display:block}
.ci-card{background:#fff;border:1px solid #eee8de;border-radius:22px;padding:22px 22px 20px;margin-bottom:18px}
.ci-card-muted{background:#fbfaf7}
.ci-card-head{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;margin-bottom:14px}
.ci-card-title{font-size:12px;font-weight:700;letter-spacing:.12em;color:#b3ab9f;text-transform:uppercase}
.ci-card-title-main{font-size:24px;letter-spacing:-.03em;line-height:1.08;color:#201f1a;font-weight:700;margin:2px 0 6px}
.ci-card-copy{font-size:14px;line-height:1.65;color:#8d8477;max-width:720px}
.ci-generate-btn,.ci-ai-btn,.ci-ghost-btn,.ci-next-btn,.ci-back-btn{font-family:inherit;border-radius:12px;cursor:pointer;transition:.18s ease}
.ci-generate-btn,.ci-ai-btn,.ci-ghost-btn{height:38px;padding:0 14px;font-size:12px;font-weight:700;letter-spacing:.02em}
.ci-ai-btn{border:1px solid #e3d4e3;background:#faf3fb;color:#8d2f8d}
.ci-ai-btn:hover{background:#f6ebf8}
.ci-generate-btn{border:1px solid #e8e1d8;background:#fff;color:#534d43}
.ci-generate-btn:hover,.ci-ghost-btn:hover{background:#f8f5ee}
.ci-ghost-btn{border:1px solid #e8e1d8;background:#fff;color:#534d43}
.ci-note{display:flex;align-items:center;gap:8px;font-size:11px;color:#b3ab9f;margin-top:10px}
.ci-note:before{content:'';width:6px;height:6px;border-radius:50%;background:#ddd2c8}
.ci-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.ci-field{margin-bottom:18px}
.ci-field-row{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:7px}
.ci-label{font-size:12px;font-weight:700;color:#57524a}
.ci-label small{font-size:10px;color:#b4ac9e;font-weight:700;margin-left:6px;text-transform:uppercase;letter-spacing:.08em}
.ci-help{font-size:11.5px;line-height:1.45;color:#b0a79a;margin-bottom:8px}
.ci-input,.ci-textarea{width:100%;font-family:inherit;border:1px solid #e8e1d8;border-radius:14px;background:#fff;color:#201f1a;outline:none;transition:border-color .18s ease,box-shadow .18s ease}
.ci-input{height:48px;padding:0 16px;font-size:14px}
.ci-textarea{padding:13px 16px;resize:vertical;font-size:14px;line-height:1.6}
.ci-input:focus,.ci-textarea:focus{border-color:#d2c9bc;box-shadow:0 0 0 3px rgba(219,211,198,.18)}
.ci-input::placeholder,.ci-textarea::placeholder{color:#c5bdae}
.ci-ai-zone{border:1px dashed #d9b7d7;background:#fdf8fd;border-radius:18px;padding:18px}
.ci-ai-badge{display:inline-flex;align-items:center;gap:6px;background:#f6ebf6;border:1px solid #ead7ea;border-radius:999px;padding:5px 10px;color:#8d2f8d;font-size:10px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:10px}
.ci-divider{display:flex;align-items:center;gap:10px;margin:18px 0}
.ci-divider:before,.ci-divider:after{content:'';flex:1;height:1px;background:#f0ebe2}
.ci-divider span{font-size:10px;font-weight:800;letter-spacing:.14em;color:#c2b8aa;text-transform:uppercase}
.ci-tag-box{display:flex;flex-wrap:wrap;gap:8px;border:1px solid #e8e1d8;border-radius:14px;background:#fff;padding:10px 12px;min-height:52px;cursor:text}
.ci-tag-chip{display:inline-flex;align-items:center;gap:7px;background:#eef6ef;border:1px solid #d4e7d7;border-radius:999px;padding:7px 11px;font-size:12px;color:#3f7251;line-height:1}
.ci-tag-chip button{background:none;border:none;padding:0;cursor:pointer;font-size:14px;color:#90b29b}
.ci-tag-input{flex:1;min-width:140px;border:none;outline:none;background:transparent;font-family:inherit;font-size:13px;color:#201f1a}
.ci-hidden{display:none!important}
.ci-color-card{background:#fbfaf7;border:1px solid #eee8de;border-radius:18px;padding:16px}
.ci-swatches{display:flex;flex-wrap:wrap;gap:10px;margin-top:14px}
.ci-swatch{display:flex;flex-direction:column;align-items:center;gap:5px}
.ci-swatch-circle{width:44px;height:44px;border-radius:50%;border:1px solid rgba(0,0,0,.08)}
.ci-swatch-label{font-size:10px;color:#aea495;font-weight:700;letter-spacing:.05em}
.ci-choice-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px}
.ci-choice{border:1px solid #ece5db;border-radius:16px;background:#fff;padding:14px 12px;min-height:96px;cursor:pointer;transition:.18s ease}
.ci-choice strong{display:block;font-size:13px;color:#26231e;margin-bottom:6px}
.ci-choice span{display:block;font-size:11.5px;line-height:1.5;color:#9b9286}
.ci-choice.selected{border-color:#c984d5;box-shadow:0 0 0 2px rgba(201,132,213,.12);background:#fffafd}
.ci-pill-row{display:flex;flex-wrap:wrap;gap:8px}
.ci-pill{border:1px solid #e7dfd4;background:#fff;border-radius:999px;padding:8px 14px;font-size:12px;font-weight:600;color:#57524a;cursor:pointer}
.ci-pill.selected{background:#1f1d1a;border-color:#1f1d1a;color:#fff}
.ci-upload{border:1px dashed #e2dacd;background:#fcfbf8;border-radius:18px;padding:22px;text-align:center}
.ci-upload small{display:block;color:#b4ab9f;font-size:11px;margin-top:4px}
.ci-moodboard{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px;margin-top:12px}
.ci-moodboard img{width:100%;aspect-ratio:1/1;object-fit:cover;border-radius:14px;border:1px solid #e9e3da}
.ci-review-strip{display:flex;align-items:center;justify-content:space-between;gap:14px;background:#f6effd;border:1px solid #ddcdec;border-radius:18px;padding:16px 18px;margin-bottom:18px}
.ci-review-strip strong{display:block;font-size:15px;color:#341737;margin-bottom:3px}
.ci-review-strip span{display:block;font-size:12.5px;line-height:1.55;color:#886690}
.ci-completion-summary{display:flex;align-items:center;justify-content:space-between;gap:16px;background:#eef8f0;border:1px solid #d6ead9;border-radius:18px;padding:15px 18px;margin-bottom:14px}
.ci-completion-summary strong{display:block;font-size:14px;color:#2a6b3f;margin-bottom:3px}
.ci-completion-summary span{display:block;font-size:12px;color:#5f8768;line-height:1.5}
.ci-completion-pill{display:inline-flex;align-items:center;justify-content:center;min-width:116px;height:34px;padding:0 12px;border-radius:999px;background:#fff;border:1px solid #d6ead9;font-size:12px;font-weight:800;color:#2a6b3f;letter-spacing:.02em}
.ci-review-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.ci-review-card{border:1px solid #ece6dc;border-radius:18px;background:#fff;overflow:hidden}
.ci-review-card.full{grid-column:1 / -1}
.ci-review-head{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid #f3eee7;background:#fcfbf8}
.ci-review-head span{font-size:10px;font-weight:800;letter-spacing:.12em;color:#b4ab9f;text-transform:uppercase}
.ci-review-body{padding:16px}
.ci-review-text{font-size:13px;line-height:1.65;color:#5a554b}
.ci-review-tags{display:flex;flex-wrap:wrap;gap:8px}
.ci-review-tag{display:inline-flex;align-items:center;background:#f4f1eb;border:1px solid #e7dfd4;border-radius:999px;padding:6px 10px;font-size:12px;color:#5b554d}
.ci-review-tag.green{background:#eef6ef;border-color:#d4e7d7;color:#3f7251}
.ci-footer{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:20px 32px;border-top:1px solid #f0ece4;background:#fcfbf8}
.ci-footer-note{font-size:11.5px;color:#b4aa9c;text-align:center;flex:1}
.ci-save-state{display:block;margin-top:4px;font-size:11px;color:#9f9587}
.ci-save-state.success{color:#3f7251}
.ci-save-state.error{color:#a85454}
.ci-saved-at{display:block;margin-top:4px;font-size:11px;color:#b8af9f}
.ci-back-btn,.ci-next-btn{height:42px;padding:0 18px;font-size:13px;font-weight:700}
.ci-back-btn{border:1px solid #e8e1d8;background:#fff;color:#625d54}
.ci-next-btn{border:none;background:#1f1d1a;color:#fff}
.ci-next-btn:hover{background:#33302b}
.ci-compact-btn{padding:0 12px!important;height:32px!important;font-size:11.5px!important}
.ci-spinner{opacity:.72;pointer-events:none}
@media (max-width: 991px){
  .ci-grid,.ci-review-grid,.ci-choice-grid{grid-template-columns:1fr}
  .ci-card-head,.ci-field-row,.ci-footer,.ci-review-strip{flex-direction:column;align-items:stretch}
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

            <div class="ci-flow">
                <div class="ci-shell">
                    <div class="ci-header">
                        <div class="ci-kicker"><?php _e("Build your profile") ?></div>
                        <div class="ci-title" id="ci-header-title"><?php _e("Tell us about your business") ?></div>
                        <div class="ci-sub" id="ci-header-sub"><?php _e("Atlas uses this profile to shape your AI agents, social campaigns, Instagram grid, and website generation.") ?></div>
                        <div class="ci-stepbar">
                            <div class="ci-step active" data-ci-step="1"><div class="ci-step-circle">1</div><span><?php _e("Your website") ?></span><span class="ci-step-badge"><?php _e("Done") ?></span></div>
                            <div class="ci-step-line"></div>
                            <div class="ci-step" data-ci-step="2"><div class="ci-step-circle">2</div><span><?php _e("Business info") ?></span><span class="ci-step-badge"><?php _e("Done") ?></span></div>
                            <div class="ci-step-line"></div>
                            <div class="ci-step" data-ci-step="3"><div class="ci-step-circle">3</div><span><?php _e("Inspiration") ?></span><span class="ci-step-badge"><?php _e("Done") ?></span></div>
                            <div class="ci-step-line"></div>
                            <div class="ci-step" data-ci-step="4"><div class="ci-step-circle">4</div><span><?php _e("Review") ?></span><span class="ci-step-badge"><?php _e("Done") ?></span></div>
                        </div>
                    </div>

                    <form method="post" enctype="multipart/form-data" id="company-intelligence-form">
                        <input type="hidden" name="website_snapshot_json" id="website_snapshot_json" value="<?php echo !empty($websiteSnapshot) ? _esc(json_encode($websiteSnapshot), 0) : ''; ?>">
                        <input type="hidden" name="website_extracted_at" id="website_extracted_at" value="<?php _esc(!empty($social_profile['website_extracted_at']) ? $social_profile['website_extracted_at'] : '') ?>">
                        <input type="hidden" name="existing_moodboard_images" id="existing_moodboard_images" value="<?php _esc(implode("\n", $moodboardImages)) ?>">

                        <div class="ci-body">
                            <div class="ci-panel active" data-panel="1">
                                <div class="ci-card ci-ai-zone">
                                    <div class="ci-ai-badge"><?php _e("AI Extract") ?></div>
                                    <div class="ci-card-title-main"><?php _e("Let Atlas read your website and fill this in") ?></div>
                                    <div class="ci-card-copy"><?php _e("Paste your URL and Atlas will extract your company summary, ideal customer, problems, and strategic context. You can edit everything after.") ?></div>
                                    <div class="ci-grid" style="margin-top:14px;">
                                        <div class="ci-field" style="margin-bottom:0;">
                                            <input class="ci-input" type="text" name="company_website" id="company_website" value="<?php _esc($social_profile['company_website']) ?>" placeholder="https://yourwebsite.com">
                                            <div class="ci-note"><?php _e("Your site is only used for extraction and profile building.") ?></div>
                                        </div>
                                        <div class="ci-field" style="margin-bottom:0;">
                                            <button type="button" class="ci-ai-btn atlas-extract-button" style="width:100%;height:48px;"><?php _e("Generate with Atlas") ?></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="ci-divider"><span><?php _e("or fill in manually") ?></span></div>

                                <div class="ci-card">
                                    <div class="ci-grid">
                                        <div class="ci-field">
                                            <div class="ci-field-row"><label class="ci-label"><?php _e("Company name") ?></label></div>
                                            <input class="ci-input" type="text" name="company_name" id="company_name" value="<?php _esc($social_profile['company_name']) ?>" placeholder="<?php _e("Your company name") ?>">
                                        </div>
                                        <div class="ci-field">
                                            <div class="ci-field-row"><label class="ci-label"><?php _e("Industry") ?></label></div>
                                            <input class="ci-input" type="text" name="company_industry" id="company_industry" value="<?php _esc($social_profile['company_industry']) ?>" placeholder="<?php _e("eCommerce, local services, SaaS...") ?>">
                                        </div>
                                        <div class="ci-field">
                                            <div class="ci-field-row"><label class="ci-label"><?php _e("Founder name") ?></label></div>
                                            <input class="ci-input" type="text" name="founder_name" id="founder_name" value="<?php _esc($social_profile['founder_name']) ?>" placeholder="<?php _e("Who is behind the business?") ?>">
                                        </div>
                                        <div class="ci-field">
                                            <div class="ci-field-row"><label class="ci-label"><?php _e("Founder title") ?></label></div>
                                            <input class="ci-input" type="text" name="founder_title" id="founder_title" value="<?php _esc($social_profile['founder_title']) ?>" placeholder="<?php _e("Founder, CEO, Creative director...") ?>">
                                        </div>
                                    </div>
                                    <div class="ci-field" style="margin-bottom:0;">
                                        <div class="ci-field-row">
                                            <div>
                                                <label class="ci-label"><?php _e("Company description") ?></label>
                                                <div class="ci-help"><?php _e("What do you do, who do you serve, and why should someone trust you?") ?></div>
                                            </div>
                                            <button type="button" class="ci-generate-btn" data-generate-field="company_description"><?php _e("Generate with Atlas") ?></button>
                                        </div>
                                        <textarea class="ci-textarea" rows="5" name="company_description" id="company_description" placeholder="<?php _e("Describe your company in a few concise sentences.") ?>"><?php _esc($social_profile['company_description']) ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="ci-panel" data-panel="2">
                                <div class="ci-card">
                                    <div class="ci-card-head">
                                        <div>
                                            <div class="ci-card-title"><?php _e("Business info") ?></div>
                                            <div class="ci-card-title-main"><?php _e("Tell Atlas who you serve and why people choose you") ?></div>
                                            <div class="ci-card-copy"><?php _e("We use this to write stronger hooks, CTAs, website copy, and positioning-aware content.") ?></div>
                                        </div>
                                    </div>

                                    <div class="ci-field">
                                        <div class="ci-field-row">
                                            <div>
                                                <label class="ci-label"><?php _e("Ideal customer profile (ICP)") ?></label>
                                                <div class="ci-help"><?php _e("Who is your best customer? Include stage, role, goals, and pain points.") ?></div>
                                            </div>
                                            <button type="button" class="ci-generate-btn" data-generate-field="ideal_customer_profile"><?php _e("Generate with Atlas") ?></button>
                                        </div>
                                        <textarea class="ci-textarea" rows="4" name="ideal_customer_profile" id="ideal_customer_profile" placeholder="<?php _e("Example: busy dog owners who want premium accessories but don’t know which products actually last.") ?>"><?php _esc($social_profile['ideal_customer_profile']) ?></textarea>
                                    </div>

                                    <div class="ci-grid">
                                        <div class="ci-field">
                                            <div class="ci-field-row">
                                                <div>
                                                    <label class="ci-label"><?php _e("Top problems you solve") ?><small>3-5</small></label>
                                                    <div class="ci-help"><?php _e("Press Enter after each problem to add it as a chip.") ?></div>
                                                </div>
                                                <button type="button" class="ci-generate-btn ci-compact-btn" data-generate-field="top_problems_solved"><?php _e("Generate with Atlas") ?></button>
                                            </div>
                                            <div class="ci-tag-box" data-target="top_problems_solved">
                                                <textarea class="ci-hidden" name="top_problems_solved" id="top_problems_solved"><?php _esc($topProblemsSolved) ?></textarea>
                                                <input class="ci-tag-input" type="text" placeholder="<?php _e("Add a problem...") ?>">
                                            </div>
                                        </div>
                                        <div class="ci-field">
                                            <div class="ci-field-row">
                                                <div>
                                                    <label class="ci-label"><?php _e("Unique selling points") ?><small>3-5</small></label>
                                                    <div class="ci-help"><?php _e("What makes your offer better or different?") ?></div>
                                                </div>
                                                <button type="button" class="ci-generate-btn ci-compact-btn" data-generate-field="unique_selling_points"><?php _e("Generate with Atlas") ?></button>
                                            </div>
                                            <div class="ci-tag-box" data-target="unique_selling_points">
                                                <textarea class="ci-hidden" name="unique_selling_points" id="unique_selling_points"><?php _esc($uniqueSellingPoints) ?></textarea>
                                                <input class="ci-tag-input" type="text" placeholder="<?php _e("Add a USP...") ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="ci-grid">
                                        <div class="ci-field">
                                            <div class="ci-field-row"><label class="ci-label"><?php _e("Target audience") ?></label></div>
                                            <textarea class="ci-textarea" rows="3" name="target_audience" id="target_audience" placeholder="<?php _e("Who exactly are you trying to attract?") ?>"><?php _esc($social_profile['target_audience']) ?></textarea>
                                        </div>
                                        <div class="ci-field">
                                            <div class="ci-field-row"><label class="ci-label"><?php _e("Key products / services") ?></label></div>
                                            <textarea class="ci-textarea" rows="3" name="key_products" id="key_products" placeholder="<?php _e("What do you sell or deliver most often?") ?>"><?php _esc($social_profile['key_products']) ?></textarea>
                                        </div>
                                        <div class="ci-field">
                                            <div class="ci-field-row"><label class="ci-label"><?php _e("Differentiators") ?></label></div>
                                            <textarea class="ci-textarea" rows="3" name="differentiators" id="differentiators" placeholder="<?php _e("Why should someone choose you over alternatives?") ?>"><?php _esc($social_profile['differentiators']) ?></textarea>
                                        </div>
                                        <div class="ci-field">
                                            <div class="ci-field-row"><label class="ci-label"><?php _e("Content goals") ?></label></div>
                                            <textarea class="ci-textarea" rows="3" name="content_goals" id="content_goals" placeholder="<?php _e("Awareness, leads, sales, trust building...") ?>"><?php _esc($social_profile['content_goals']) ?></textarea>
                                        </div>
                                    </div>

                                    <div class="ci-grid">
                                        <div class="ci-field">
                                            <div class="ci-field-row">
                                                <div>
                                                    <label class="ci-label"><?php _e("Competitor websites") ?><small><?php _e("optional") ?></small></label>
                                                    <div class="ci-help"><?php _e("Add brands Atlas should analyze to sharpen your positioning.") ?></div>
                                                </div>
                                                <button type="button" class="ci-generate-btn ci-compact-btn" data-generate-field="competitors"><?php _e("Generate with Atlas") ?></button>
                                            </div>
                                            <div class="ci-tag-box" data-target="competitors">
                                                <textarea class="ci-hidden" name="competitors" id="competitors"><?php _esc($competitors) ?></textarea>
                                                <input class="ci-tag-input" type="text" placeholder="<?php _e("Add a URL...") ?>">
                                            </div>
                                        </div>
                                        <div class="ci-field">
                                            <div class="ci-field-row"><label class="ci-label"><?php _e("Competitor notes") ?></label></div>
                                            <textarea class="ci-textarea" rows="3" name="competitor_notes" id="competitor_notes" placeholder="<?php _e("Anything specific Atlas should notice when comparing you?") ?>"><?php _esc($social_profile['competitor_notes']) ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ci-panel" data-panel="3">
                                <div class="ci-card">
                                    <div class="ci-card-head">
                                        <div>
                                            <div class="ci-card-title"><?php _e("Inspiration") ?></div>
                                            <div class="ci-card-title-main"><?php _e("Shape how Atlas should look and sound") ?></div>
                                            <div class="ci-card-copy"><?php _e("We’ll use your color system, visual direction, tone, references, and moodboard to keep all generated output on-brand.") ?></div>
                                        </div>
                                    </div>

                                    <div class="ci-field">
                                        <div class="ci-field-row">
                                            <div>
                                                <label class="ci-label"><?php _e("Brand colors") ?></label>
                                                <div class="ci-help"><?php _e("Add hex colors Atlas should use across social generation and website drafts.") ?></div>
                                            </div>
                                            <button type="button" class="ci-generate-btn" data-generate-field="brand_colors"><?php _e("Generate with Atlas") ?></button>
                                        </div>
                                        <div class="ci-color-card">
                                            <div class="ci-tag-box" data-target="brand_colors">
                                                <textarea class="ci-hidden" name="brand_colors" id="brand_colors"><?php _esc($brandColors) ?></textarea>
                                                <input class="ci-tag-input" type="text" placeholder="#111111">
                                            </div>
                                            <div class="ci-swatches" id="atlas-ci-color-preview"></div>
                                        </div>
                                    </div>

                                    <div class="ci-grid">
                                        <div class="ci-field">
                                            <div class="ci-field-row"><label class="ci-label"><?php _e("Upload your logo") ?><small><?php _e("optional") ?></small></label></div>
                                            <div class="ci-upload">
                                                <div><?php _e("Upload a logo Atlas can use in previews and generated assets.") ?></div>
                                                <small><?php _e("PNG, JPG, or transparent logo preferred.") ?></small>
                                                <input type="file" name="company_logo" accept="image/*" style="margin-top:12px;">
                                            </div>
                                        </div>
                                        <div class="ci-field">
                                            <div class="ci-field-row"><label class="ci-label"><?php _e("Instagram handle") ?><small><?php _e("optional") ?></small></label></div>
                                            <input class="ci-input" type="text" name="instagram_handle" id="instagram_handle" value="<?php _esc($social_profile['instagram_handle']) ?>" placeholder="@yourbrand">
                                        </div>
                                    </div>

                                    <div class="ci-field">
                                        <div class="ci-field-row">
                                            <div>
                                                <label class="ci-label"><?php _e("Visual mood") ?></label>
                                                <div class="ci-help"><?php _e("Choose 1–2 directions Atlas should use for visuals and layouts.") ?></div>
                                            </div>
                                            <button type="button" class="ci-generate-btn" data-generate-field="visual_mood"><?php _e("Generate with Atlas") ?></button>
                                        </div>
                                        <div class="ci-choice-grid" data-pill-target="visual_mood">
                                            <?php foreach ($allVisualMoodOptions as $option) { ?>
                                                <button type="button" class="ci-choice <?php echo in_array($option, $visualMood, true) ? 'selected' : ''; ?>" data-value="<?php _esc($option) ?>">
                                                    <strong><?php _esc($option) ?></strong>
                                                    <span><?php
                                                        $descriptions = [
                                                            'Dark & editorial' => __('High contrast, premium, polished, and cinematic.'),
                                                            'Warm & minimal' => __('Soft neutrals, light layouts, and calm spacing.'),
                                                            'Documentary grid' => __('Authentic, practical, lived-in brand storytelling.'),
                                                            'Soft lifestyle' => __('Gentle lifestyle imagery with easy everyday energy.'),
                                                            'Bold & direct' => __('Clear contrast, stronger type, and decisive visual hierarchy.')
                                                        ];
                                                        _esc($descriptions[$option]);
                                                    ?></span>
                                                </button>
                                            <?php } ?>
                                        </div>
                                        <textarea class="ci-hidden" name="visual_mood" id="visual_mood"><?php _esc(implode("\n", $visualMood)) ?></textarea>
                                    </div>

                                    <div class="ci-field">
                                        <div class="ci-field-row">
                                            <div>
                                                <label class="ci-label"><?php _e("Tone of voice") ?></label>
                                                <div class="ci-help"><?php _e("Select the attributes Atlas should use when writing content.") ?></div>
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

                                    <div class="ci-grid">
                                        <div class="ci-field">
                                            <div class="ci-field-row">
                                                <div>
                                                    <label class="ci-label"><?php _e("Reference brands") ?><small><?php _e("optional") ?></small></label>
                                                    <div class="ci-help"><?php _e("Brands or creators Atlas should study to understand your desired style.") ?></div>
                                                </div>
                                                <button type="button" class="ci-generate-btn ci-compact-btn" data-generate-field="reference_brands"><?php _e("Generate with Atlas") ?></button>
                                            </div>
                                            <div class="ci-tag-box" data-target="reference_brands">
                                                <textarea class="ci-hidden" name="reference_brands" id="reference_brands"><?php _esc($referenceBrands) ?></textarea>
                                                <input class="ci-tag-input" type="text" placeholder="<?php _e("@handle, brand, or website URL") ?>">
                                            </div>
                                        </div>
                                        <div class="ci-field">
                                            <div class="ci-field-row"><label class="ci-label"><?php _e("Brand voice notes") ?></label></div>
                                            <textarea class="ci-textarea" rows="3" name="brand_voice" id="brand_voice" placeholder="<?php _e("Anything Atlas should remember about how you speak or present yourself?") ?>"><?php _esc($social_profile['brand_voice']) ?></textarea>
                                        </div>
                                    </div>

                                    <div class="ci-field" style="margin-bottom:0;">
                                        <div class="ci-field-row"><label class="ci-label"><?php _e("Moodboard / inspiration") ?><small><?php _e("optional") ?></small></label></div>
                                        <div class="ci-upload">
                                            <div><?php _e("Drop images or upload references Atlas can use to understand your visual direction.") ?></div>
                                            <small><?php _e("PNG, JPG, or visual inspiration boards are all fine.") ?></small>
                                            <input type="file" name="moodboard_images[]" multiple accept="image/*" style="margin-top:12px;">
                                        </div>
                                        <?php if (!empty($moodboardImages)) { ?>
                                            <div class="ci-moodboard">
                                                <?php foreach ($moodboardImages as $moodboardImage) { ?>
                                                    <img src="<?php echo _esc($config['site_url'], 0) . 'storage/company/' . $moodboardImage; ?>" alt="">
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="ci-panel" data-panel="4">
                                <div class="ci-completion-summary">
                                    <div>
                                        <strong id="ci-completion-title"><?php _e("Profile complete · 0 of 8 sections filled") ?></strong>
                                        <span id="ci-completion-sub"><?php _e("Complete the missing sections to give Atlas stronger context for content, website generation, and AI responses.") ?></span>
                                    </div>
                                    <div class="ci-completion-pill" id="ci-completion-pill">0 / 8</div>
                                </div>

                                <div class="ci-review-strip">
                                    <div>
                                        <strong><?php _e("Ready to generate your business profile") ?></strong>
                                        <span><?php _e("Atlas will use this profile for AI agents, social content, website generation, and all future brand-aware decisions.") ?></span>
                                    </div>
                                    <button type="button" class="ci-ai-btn intelligence-refresh-btn"><?php _e("Generate with Atlas") ?></button>
                                </div>

                                <div class="ci-review-grid">
                                    <div class="ci-review-card">
                                        <div class="ci-review-head"><span><?php _e("Website") ?></span><button type="button" class="ci-generate-btn ci-compact-btn" data-jump-step="1"><?php _e("Edit") ?></button></div>
                                        <div class="ci-review-body"><div class="ci-review-text" id="review-website"><?php _esc(!empty($social_profile['company_website']) ? $social_profile['company_website'] : __('No website added yet.')) ?></div></div>
                                    </div>
                                    <div class="ci-review-card">
                                        <div class="ci-review-head"><span><?php _e("Company description") ?></span><button type="button" class="ci-generate-btn ci-compact-btn" data-jump-step="1"><?php _e("Edit") ?></button></div>
                                        <div class="ci-review-body"><div class="ci-review-text" id="review-company-description"><?php _esc(!empty($social_profile['company_description']) ? $social_profile['company_description'] : __('Not added yet.')) ?></div></div>
                                    </div>
                                    <div class="ci-review-card">
                                        <div class="ci-review-head"><span><?php _e("Ideal customer") ?></span><button type="button" class="ci-generate-btn ci-compact-btn" data-jump-step="2"><?php _e("Edit") ?></button></div>
                                        <div class="ci-review-body"><div class="ci-review-text" id="review-icp"></div></div>
                                    </div>
                                    <div class="ci-review-card">
                                        <div class="ci-review-head"><span><?php _e("Products / services") ?></span><button type="button" class="ci-generate-btn ci-compact-btn" data-jump-step="2"><?php _e("Edit") ?></button></div>
                                        <div class="ci-review-body"><div class="ci-review-text" id="review-products"><?php _esc(!empty($social_profile['key_products']) ? $social_profile['key_products'] : __('Not added yet.')) ?></div></div>
                                    </div>
                                    <div class="ci-review-card">
                                        <div class="ci-review-head"><span><?php _e("Problems solved") ?></span><button type="button" class="ci-generate-btn ci-compact-btn" data-jump-step="2"><?php _e("Edit") ?></button></div>
                                        <div class="ci-review-body"><div class="ci-review-tags" id="review-problems"></div></div>
                                    </div>
                                    <div class="ci-review-card">
                                        <div class="ci-review-head"><span><?php _e("USPs") ?></span><button type="button" class="ci-generate-btn ci-compact-btn" data-jump-step="2"><?php _e("Edit") ?></button></div>
                                        <div class="ci-review-body"><div class="ci-review-tags" id="review-usps"></div></div>
                                    </div>
                                    <div class="ci-review-card">
                                        <div class="ci-review-head"><span><?php _e("Brand colors") ?></span><button type="button" class="ci-generate-btn ci-compact-btn" data-jump-step="3"><?php _e("Edit") ?></button></div>
                                        <div class="ci-review-body"><div class="ci-review-tags" id="review-colors"></div></div>
                                    </div>
                                    <div class="ci-review-card">
                                        <div class="ci-review-head"><span><?php _e("Tone & mood") ?></span><button type="button" class="ci-generate-btn ci-compact-btn" data-jump-step="3"><?php _e("Edit") ?></button></div>
                                        <div class="ci-review-body"><div class="ci-review-tags" id="review-tone"></div></div>
                                    </div>
                                    <div class="ci-review-card">
                                        <div class="ci-review-head"><span><?php _e("Competitors") ?></span><button type="button" class="ci-generate-btn ci-compact-btn" data-jump-step="2"><?php _e("Edit") ?></button></div>
                                        <div class="ci-review-body"><div class="ci-review-tags" id="review-competitors"></div></div>
                                    </div>
                                    <div class="ci-review-card">
                                        <div class="ci-review-head"><span><?php _e("Reference brands") ?></span><button type="button" class="ci-generate-btn ci-compact-btn" data-jump-step="3"><?php _e("Edit") ?></button></div>
                                        <div class="ci-review-body"><div class="ci-review-tags" id="review-reference-brands"></div></div>
                                    </div>
                                    <div class="ci-review-card full">
                                        <div class="ci-review-head"><span><?php _e("Atlas company intelligence") ?></span></div>
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

                        <div class="ci-footer">
                            <button type="button" class="ci-back-btn" id="ci-prev-btn"><?php _e("Back") ?></button>
                            <div class="ci-footer-note">
                                <span id="ci-footer-note"><?php _e("Step 1 of 4 · Atlas saves your progress as you move"); ?></span>
                                <span class="ci-save-state" id="ci-save-state"><?php _e("Not saved yet."); ?></span>
                                <span class="ci-saved-at" id="ci-saved-at"><?php _e("Last saved: not yet"); ?></span>
                            </div>
                            <div style="display:flex;gap:10px;">
                                <button type="button" class="ci-next-btn" id="ci-next-btn"><?php _e("Save & continue") ?></button>
                                <button type="submit" name="company-intelligence-submit" class="ci-next-btn ci-hidden" id="ci-save-btn"><?php _e("Save profile") ?></button>
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
    var isSavingStep = false;
    var stepTitles = {
        1: {title: "<?php echo addslashes(__('Tell us about your business')); ?>", sub: "<?php echo addslashes(__('Start with your website and core company basics. Atlas can extract the first draft for you.')); ?>"},
        2: {title: "<?php echo addslashes(__('Clarify your business positioning')); ?>", sub: "<?php echo addslashes(__('Define your customer, the problems you solve, and what makes your offer different.')); ?>"},
        3: {title: "<?php echo addslashes(__("What's your visual direction?")); ?>", sub: "<?php echo addslashes(__('Choose your colors, visual mood, tone of voice, and the references Atlas should study.')); ?>"},
        4: {title: "<?php echo addslashes(__('Review your business profile')); ?>", sub: "<?php echo addslashes(__('Check the final profile before Atlas uses it across your workspace.')); ?>"}
    };

    function parseLines(value) {
        if (!value) return [];
        return value.split(/\n+/).map(function (item) { return $.trim(item); }).filter(Boolean);
    }

    function syncTagField($field) {
        var targetId = $field.data('target');
        var values = [];
        $field.find('.ci-tag-chip').each(function () {
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
        $field.find('.ci-tag-chip').each(function () {
            if ($(this).data('value').toLowerCase() === value.toLowerCase()) {
                exists = true;
            }
        });
        if (exists) return;
        var $tag = $('<span class="ci-tag-chip" data-value=""></span>');
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
        $field.find('.ci-tag-chip').remove();
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
            $wrap.append(
                $('<div class="ci-swatch"></div>')
                    .append('<div class="ci-swatch-circle" style="background:' + color + ';"></div>')
                    .append('<div class="ci-swatch-label">' + color + '</div>')
            );
        });
    }

    function syncChoiceTarget(targetId) {
        var values = [];
        $('[data-pill-target="' + targetId + '"] .selected').each(function () {
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
        $('#review-icp').text($('#ideal_customer_profile').val() || "<?php echo addslashes(__('No ICP added yet.')); ?>");
        $('#review-products').text($('#key_products').val() || "<?php echo addslashes(__('Not added yet.')); ?>");
        renderReviewTags('#review-problems', parseLines($('#top_problems_solved').val()), true);
        renderReviewTags('#review-usps', parseLines($('#unique_selling_points').val()), false);
        renderReviewTags('#review-colors', parseLines($('#brand_colors').val()), false);
        renderReviewTags('#review-competitors', parseLines($('#competitors').val()), false);
        renderReviewTags('#review-reference-brands', parseLines($('#reference_brands').val()), false);
        renderReviewTags('#review-tone', parseLines($('#tone_attributes').val()).concat(parseLines($('#visual_mood').val())), false);
        updateCompletionSummary();
    }

    function updateCompletionSummary() {
        var sections = [
            $.trim($('#company_website').val()) !== '' || $.trim($('#company_name').val()) !== '',
            $.trim($('#company_description').val()) !== '',
            $.trim($('#ideal_customer_profile').val()) !== '',
            parseLines($('#top_problems_solved').val()).length > 0,
            parseLines($('#unique_selling_points').val()).length > 0,
            parseLines($('#brand_colors').val()).length > 0,
            parseLines($('#tone_attributes').val()).length > 0 || parseLines($('#visual_mood').val()).length > 0,
            parseLines($('#competitors').val()).length > 0 || parseLines($('#reference_brands').val()).length > 0 || $.trim($('#brand_voice').val()) !== '' || $('#existing_moodboard_images').val().trim() !== ''
        ];
        var completed = sections.filter(Boolean).length;
        $('#ci-completion-title').text("<?php echo addslashes(__('Profile complete')); ?> · " + completed + " <?php echo addslashes(__('of 8 sections filled')); ?>");
        $('#ci-completion-pill').text(completed + ' / 8');
        $('#ci-completion-sub').text(
            completed === 8
                ? "<?php echo addslashes(__('Everything important is in place. Atlas now has strong context to generate on-brand output.')); ?>"
                : "<?php echo addslashes(__('Complete the missing sections to give Atlas stronger context for content, website generation, and AI responses.')); ?>"
        );
    }

    function setSaveState(message, type) {
        var $state = $('#ci-save-state');
        $state.removeClass('success error');
        if (type) {
            $state.addClass(type);
        }
        $state.text(message);
    }

    function formatSavedAt(value) {
        if (!value) return "<?php echo addslashes(__('Last saved: not yet')); ?>";
        var normalized = value.replace(' ', 'T');
        var date = new Date(normalized);
        if (isNaN(date.getTime())) {
            return "<?php echo addslashes(__('Last saved: ')); ?>" + value;
        }
        return "<?php echo addslashes(__('Last saved: ')); ?>" + date.toLocaleTimeString([], {hour: 'numeric', minute: '2-digit'});
    }

    function setSavedAt(value) {
        $('#ci-saved-at').text(formatSavedAt(value));
    }

    function getStepStatus(step) {
        if (step === 1) {
            var hasWebsite = $.trim($('#company_website').val()) !== '';
            var hasManualCore = $.trim($('#company_name').val()) !== '' && $.trim($('#company_industry').val()) !== '' && $.trim($('#company_description').val()) !== '';
            return hasWebsite || hasManualCore;
        }
        if (step === 2) {
            return $.trim($('#ideal_customer_profile').val()) !== '' &&
                parseLines($('#top_problems_solved').val()).length > 0 &&
                parseLines($('#unique_selling_points').val()).length > 0;
        }
        if (step === 3) {
            return parseLines($('#brand_colors').val()).length > 0 &&
                parseLines($('#visual_mood').val()).length > 0 &&
                parseLines($('#tone_attributes').val()).length > 0;
        }
        return true;
    }

    function validateStep(step) {
        if (getStepStatus(step)) {
            return true;
        }
        var messages = {
            1: "<?php echo addslashes(__('Add your website or complete company name, industry, and description before continuing.')); ?>",
            2: "<?php echo addslashes(__('Add your ICP, at least one problem you solve, and at least one USP before continuing.')); ?>",
            3: "<?php echo addslashes(__('Choose at least one brand color, one visual mood, and one tone attribute before continuing.')); ?>"
        };
        quick_alert(messages[step] || "<?php echo addslashes(__('Please complete this step before continuing.')); ?>", 'error');
        return false;
    }

    function updateStepStateUI() {
        $('.ci-step').each(function () {
            var idx = parseInt($(this).data('ci-step'), 10);
            var completed = getStepStatus(idx);
            $(this).removeClass('done');
            if (completed && idx !== currentStep) {
                $(this).addClass('done');
            }
            if (idx === currentStep) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
            $(this).find('.ci-step-badge').toggle(completed && idx !== currentStep);
        });
    }

    function applySavedProfile(profile) {
        if (!profile) return;
        if (profile.company_logo) {
            // saved server-side; no UI preview change needed here
        }
        if (profile.moodboard_images && profile.moodboard_images.length) {
            $('#existing_moodboard_images').val(profile.moodboard_images.join("\n"));
        }
    }

    function saveCurrentStep(step, options) {
        options = options || {};
        if (isSavingStep) {
            return $.Deferred().reject().promise();
        }
        isSavingStep = true;
        setSaveState("<?php echo addslashes(__('Saving...')); ?>", '');

        var formEl = document.getElementById('company-intelligence-form');
        var formData = new FormData(formEl);
        formData.append('step', step);

        return $.ajax({
            url: ajaxurl + '?action=save_company_intelligence_draft',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false
        }).done(function (response) {
            response = typeof response === 'string' ? JSON.parse(response) : response;
            if (!response.success) {
                setSaveState(response.error || "<?php echo addslashes(__('Save failed.')); ?>", 'error');
                if (!options.silent) {
                    quick_alert(response.error || "<?php echo addslashes(__('Atlas could not save this step right now.')); ?>", 'error');
                }
                return;
            }
            applySavedProfile(response.profile || {});
            setSaveState(response.message || "<?php echo addslashes(__('Saved.')); ?>", 'success');
            setSavedAt(response.saved_at || '');
        }).fail(function () {
            setSaveState("<?php echo addslashes(__('Save failed.')); ?>", 'error');
            if (!options.silent) {
                quick_alert("<?php echo addslashes(__('Atlas could not save this step right now.')); ?>", 'error');
            }
        }).always(function () {
            isSavingStep = false;
        });
    }

    function setStep(step) {
        currentStep = step;
        $('.ci-panel').removeClass('active');
        $('.ci-panel[data-panel="' + step + '"]').addClass('active');
        $('.ci-step').removeClass('active done');
        updateStepStateUI();
        $('#ci-header-title').text(stepTitles[step].title);
        $('#ci-header-sub').text(stepTitles[step].sub);
        $('#ci-footer-note').text('<?php echo addslashes(__('Step')); ?> ' + step + ' <?php echo addslashes(__('of 4 · Atlas saves your progress as you move')); ?>');
        $('#ci-prev-btn').toggle(step > 1);
        $('#ci-next-btn').toggle(step < 4);
        $('#ci-save-btn').toggleClass('ci-hidden', step !== 4);
        if (step === 4) updateReview();
    }

    $('.ci-tag-box').each(function () {
        initTagField($(this));
    });

    $('[data-pill-target="visual_mood"]').on('click', '.ci-choice', function () {
        $(this).toggleClass('selected');
        syncChoiceTarget('visual_mood');
        updateReview();
    });

    $('[data-pill-target="tone_attributes"]').on('click', '.ci-pill', function () {
        $(this).toggleClass('selected');
        syncChoiceTarget('tone_attributes');
        updateReview();
    });

    $('#ci-prev-btn').on('click', function () {
        if (currentStep > 1) setStep(currentStep - 1);
    });

    $('#ci-next-btn').on('click', function () {
        if (currentStep >= 4) return;
        if (!validateStep(currentStep)) {
            return;
        }
        saveCurrentStep(currentStep).done(function (response) {
            response = typeof response === 'string' ? JSON.parse(response) : response;
            if (response && response.success) {
                updateStepStateUI();
                setStep(currentStep + 1);
            }
        });
    });

    $(document).on('click', '[data-jump-step]', function () {
        var targetStep = parseInt($(this).data('jump-step'), 10);
        if (targetStep > currentStep && !validateStep(currentStep)) {
            return;
        }
        setStep(targetStep);
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
            if (p.company_name) $('#company_name').val(p.company_name);
            if (p.company_website) $('#company_website').val(p.company_website);
            if (p.company_description) $('#company_description').val(p.company_description);
            if (p.company_industry) $('#company_industry').val(p.company_industry);
            if (p.ideal_customer_profile) $('#ideal_customer_profile').val(p.ideal_customer_profile);
            if (p.company_description) $('#company_description').val(p.company_description);
            if (p.target_audience) $('#target_audience').val(p.target_audience);
            if (p.key_products) $('#key_products').val(p.key_products);
            if (p.differentiators) $('#differentiators').val(p.differentiators);
            if (p.brand_voice) $('#brand_voice').val(p.brand_voice);
            if (p.website_snapshot) $('#website_snapshot_json').val(JSON.stringify(p.website_snapshot));
            if (p.website_extracted_at) $('#website_extracted_at').val(p.website_extracted_at);

            ['top_problems_solved','unique_selling_points','brand_colors','reference_brands','competitors'].forEach(function (field) {
                if (p[field]) {
                    $('#' + field).val((p[field] || []).join("\n"));
                    initTagField($('[data-target="' + field + '"]'));
                }
            });
            if (p.visual_mood) {
                $('#visual_mood').val((p.visual_mood || []).join("\n"));
                syncGeneratedChoices('visual_mood');
            }
            if (p.tone_attributes) {
                $('#tone_attributes').val((p.tone_attributes || []).join("\n"));
                syncGeneratedChoices('tone_attributes');
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

    function syncGeneratedChoices(field) {
        var chosen = parseLines($('#' + field).val());
        $('[data-pill-target="' + field + '"] [data-value]').each(function () {
            $(this).toggleClass('selected', chosen.indexOf($(this).data('value')) !== -1);
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
                syncGeneratedChoices(field);
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

    var autosaveTimer = null;
    $('#company-intelligence-form').on('input change', 'input, textarea', function () {
        updateReview();
        updateStepStateUI();
    setSaveState("<?php echo addslashes(__('Unsaved changes')); ?>", '');
        clearTimeout(autosaveTimer);
        if (currentStep < 4) {
            autosaveTimer = setTimeout(function () {
                if (getStepStatus(currentStep)) {
                    saveCurrentStep(currentStep, {silent: true});
                }
            }, 900);
        }
    });

    $('#company-intelligence-form').on('submit', function () {
        setSaveState("<?php echo addslashes(__('Saving final profile...')); ?>", '');
    });

    updateReview();
    renderColorPreview(parseLines($('#brand_colors').val()));
    syncChoiceTarget('visual_mood');
    syncChoiceTarget('tone_attributes');
    syncGeneratedChoices('visual_mood');
    syncGeneratedChoices('tone_attributes');
    updateStepStateUI();
    setSavedAt('');
    setStep(1);
});
</script>
<?php
overall_footer();
