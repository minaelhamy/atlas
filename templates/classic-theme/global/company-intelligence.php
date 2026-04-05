<?php
overall_header(__("Company Intelligence"));
$websiteSnapshot = !empty($social_profile['website_snapshot']) && is_array($social_profile['website_snapshot']) ? $social_profile['website_snapshot'] : [];
$topProblemsSolved = !empty($social_profile['top_problems_solved']) ? implode("\n", $social_profile['top_problems_solved']) : '';
$uniqueSellingPoints = !empty($social_profile['unique_selling_points']) ? implode("\n", $social_profile['unique_selling_points']) : '';
$brandColors = !empty($social_profile['brand_colors']) ? array_values($social_profile['brand_colors']) : [];
$referenceBrands = !empty($social_profile['reference_brands']) ? array_values($social_profile['reference_brands']) : [];
$competitors = !empty($social_profile['competitors']) ? array_values($social_profile['competitors']) : [];
$toneAttributes = !empty($social_profile['tone_attributes']) ? array_values($social_profile['tone_attributes']) : [];
$currentLogo = !empty($social_profile['company_logo']) ? $social_profile['company_logo'] : '';
$brandColorSource = !empty($social_profile['company_website']) ? $social_profile['company_website'] : 'your website';
$allToneOptions = ['Bold & direct', 'Educational', 'Playful', 'Inspirational', 'Grounded / real', 'Professional', 'Conversational', 'Minimal / quiet'];

$summaryParts = [];
if (!empty($social_profile['company_name'])) {
    $summaryParts[] = $social_profile['company_name'];
}
if (!empty($social_profile['company_industry'])) {
    $summaryParts[] = $social_profile['company_industry'];
}
if (!empty($social_profile['company_description'])) {
    $summaryParts[] = $social_profile['company_description'];
}
$summaryText = !empty($summaryParts) ? implode(' · ', $summaryParts) : 'Company profile not filled yet.';

$aiSuggestions = [
    'company_description' => [
        'title' => 'Atlas suggestion:',
        'text' => 'Add a credibility signal and clearer outcome so the company positioning feels more specific and trustworthy.'
    ],
    'ideal_customer_profile' => [
        'title' => 'Atlas suggestion:',
        'text' => 'Add audience detail like platform, income range, and stage of business to make content targeting much stronger.'
    ],
    'top_problems_solved' => [
        'title' => 'Atlas suggestion:',
        'text' => 'Atlas found another likely pain point from your positioning. Apply it if it matches your customers.'
    ],
    'unique_selling_points' => [
        'title' => 'Atlas suggestion:',
        'text' => 'Try making one USP more concrete with proof, years of experience, or a measurable result.'
    ],
];
?>
<style>
*{box-sizing:border-box;margin:0;padding:0}
.wrap{background:#f2f0eb;padding:28px 20px 40px;font-family:system-ui,-apple-system,sans-serif;min-height:100vh}
.shell{background:#fff;border:0.5px solid #e0ddd6;border-radius:16px;max-width:780px;margin:0 auto;overflow:hidden}
.hdr{padding:24px 28px 20px;border-bottom:0.5px solid #eee}
.hdr-title{font-size:17px;font-weight:600;color:#1a1a1a;margin-bottom:4px;letter-spacing:-0.01em}
.hdr-sub{font-size:13px;color:#888;margin-bottom:18px;line-height:1.5;max-width:580px}
.steps{display:flex;align-items:center}
.step{display:flex;align-items:center;gap:7px;font-size:12.5px;font-weight:500;color:#bbb;flex:1}
.step.active{color:#1a1a1a}
.step.done{color:#2FAF49}
.step-num{width:24px;height:24px;border-radius:50%;border:1.5px solid #e0ddd6;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:600;background:#fff;flex-shrink:0;color:#bbb}
.step.active .step-num{border-color:#1a1a1a;background:#1a1a1a;color:#fff}
.step.done .step-num{border-color:#2FAF49;background:#2FAF49;color:#fff}
.step-connector{flex:1;height:1px;background:#e0ddd6;margin:0 10px;max-width:60px}
.body{padding:24px 28px}
.panel{display:none}
.panel.active{display:block}
.ai-zone{border:1.5px dashed #c8a8c4;border-radius:12px;padding:18px 20px;margin-bottom:22px;background:#fdf8fd}
.ai-badge{display:inline-flex;align-items:center;gap:5px;font-size:10px;font-weight:700;color:#871F7A;background:#f5eaf4;border:1px solid #e0d0de;border-radius:20px;padding:3px 10px;letter-spacing:0.06em;text-transform:uppercase;margin-bottom:10px}
.ai-badge i{font-size:10px}
.ai-zone-title{font-size:13.5px;font-weight:600;color:#1a1a1a;margin-bottom:3px}
.ai-zone-sub{font-size:12px;color:#888;line-height:1.5;margin-bottom:14px}
.ai-input-row{display:flex;gap:8px}
.ai-input{flex:1;height:42px;border:1px solid #e0ddd6;border-radius:9px;padding:0 14px;font-size:13px;font-family:inherit;color:#1a1a1a;outline:none;background:#fff}
.ai-input:focus{border-color:#871F7A}
.ai-input::placeholder{color:#bbb}
.btn-extract{height:42px;padding:0 20px;background:#1a1a1a;color:#fff;border:none;border-radius:9px;font-size:12.5px;font-weight:600;font-family:inherit;cursor:pointer;white-space:nowrap}
.btn-extract:hover{background:#333}
.ai-note{font-size:11px;color:#aaa;margin-top:8px;display:flex;align-items:center;gap:4px}
.ai-note i{font-size:11px}
.divider-label{display:flex;align-items:center;gap:10px;margin:20px 0}
.divider-label::before,.divider-label::after{content:'';flex:1;height:0.5px;background:#e8e6e0}
.divider-label span{font-size:10.5px;color:#bbb;font-weight:600;letter-spacing:0.09em;text-transform:uppercase;white-space:nowrap}
.field-group{margin-bottom:20px}
.field-label{font-size:12px;font-weight:600;color:#555;margin-bottom:4px;display:flex;align-items:center;gap:6px}
.field-badge{font-size:10px;color:#bbb;background:#f4f2ee;border-radius:4px;padding:1px 6px;font-weight:500}
.field-hint{font-size:11.5px;color:#aaa;margin-bottom:8px;line-height:1.4}
textarea,input[type=text]{width:100%;border:1px solid #e0ddd6;border-radius:9px;font-size:13px;font-family:inherit;color:#1a1a1a;background:#fff;outline:none;transition:border-color 0.15s}
textarea{padding:11px 14px;resize:vertical;line-height:1.6}
input[type=text]{height:40px;padding:0 14px}
textarea:focus,input[type=text]:focus{border-color:#c8c4bc}
textarea::placeholder,input[type=text]::placeholder{color:#c8c8c8}
.tag-field{border:1px solid #e0ddd6;border-radius:9px;padding:8px 10px;background:#fff;min-height:44px;display:flex;flex-wrap:wrap;gap:6px;align-items:flex-start;cursor:text;transition:border-color 0.15s}
.tag-field:focus-within{border-color:#c8c4bc}
.tag{display:inline-flex;align-items:center;gap:5px;background:#edf5ee;border:0.5px solid #c8e0cc;border-radius:6px;padding:4px 10px;font-size:12px;color:#2d6636}
.tag-del{background:none;border:none;cursor:pointer;color:#a0c8a8;padding:0;font-size:14px;line-height:1;display:flex;align-items:center}
.tag-del:hover{color:#2d6636}
.tag-input-bare{border:none;outline:none;font-size:12.5px;font-family:inherit;color:#1a1a1a;background:transparent;min-width:120px;flex:1}
.tag-input-bare::placeholder{color:#c8c8c8}
.ai-sug-wrap{margin-top:8px}
.ai-sug-full{background:#fdf8fd;border:1px solid #ead5e8;border-radius:9px;padding:11px 14px;display:flex;align-items:flex-start;gap:9px}
.ai-sug-icon{width:15px;height:15px;flex-shrink:0;color:#871F7A;margin-top:1px}
.ai-sug-icon i{font-size:13px}
.ai-sug-body{flex:1;min-width:0}
.ai-sug-text{font-size:12px;color:#5a3a58;line-height:1.5}
.ai-sug-text strong{color:#3d1a3b;font-weight:600}
.ai-sug-actions{display:flex;gap:6px;margin-top:7px}
.btn-apply{font-size:11.5px;font-weight:600;padding:5px 12px;border-radius:6px;cursor:pointer;font-family:inherit;background:#871F7A;color:#fff;border:none;transition:background 0.1s}
.btn-apply:hover{background:#6d1864}
.btn-x{background:none;border:none;cursor:pointer;color:#c8b0c6;padding:2px;display:flex;align-items:center;flex-shrink:0;transition:color 0.1s;margin-top:-1px}
.btn-x:hover{color:#871F7A}
.btn-x i{font-size:12px}
.ai-help-pill{display:inline-flex;align-items:center;gap:5px;margin-top:7px;font-size:11.5px;font-weight:500;color:#871F7A;background:#fdf8fd;border:1px solid #ead5e8;border-radius:20px;padding:4px 12px;cursor:pointer;transition:background 0.1s;user-select:none}
.ai-help-pill:hover{background:#f5eaf4}
.ai-help-pill i{font-size:11px}
.ai-applied-row{display:inline-flex;align-items:center;gap:5px;margin-top:7px;font-size:11.5px;color:#2FAF49;background:#f0f9f2;border:1px solid #c8e0cc;border-radius:20px;padding:4px 12px}
.ai-applied-row i{font-size:11px}
.section-block{margin-bottom:32px}
.section-num{font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#ccc;margin-bottom:3px}
.section-title-row{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:4px}
.section-title{font-size:14px;font-weight:600;color:#1a1a1a;letter-spacing:-0.01em}
.section-sub{font-size:12.5px;color:#888;line-height:1.5;margin-bottom:14px}
.opt-badge{font-size:10px;color:#aaa;background:#f4f2ee;border:0.5px solid #e0ddd6;border-radius:4px;padding:1px 7px;font-weight:500;margin-left:6px;letter-spacing:0.02em;vertical-align:middle}
.btn-rescan{height:34px;padding:0 16px;background:#fff;border:1px solid #e0ddd6;border-radius:8px;font-size:12px;font-weight:500;color:#666;font-family:inherit;cursor:pointer;display:flex;align-items:center;gap:5px;flex-shrink:0;transition:background 0.1s}
.btn-rescan:hover{background:#f5f3ee}
.btn-rescan i{font-size:12px}
.color-zone{background:#faf9f5;border:1px solid #e8e5de;border-radius:12px;padding:16px}
.cz-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
.cz-source{display:flex;align-items:center;gap:6px;font-size:12px;color:#888}
.cz-url{display:flex;align-items:center;gap:5px;font-size:12px;font-weight:500;color:#555;background:#fff;border:1px solid #e0ddd6;border-radius:6px;padding:3px 10px}
.cz-url i{font-size:11px;color:#2FAF49}
.cz-count{font-size:11px;color:#aaa}
.swatches-row{display:flex;align-items:flex-end;gap:10px;flex-wrap:wrap}
.swatch-item{display:flex;flex-direction:column;align-items:center;gap:5px;cursor:pointer;position:relative}
.swatch-circle{width:42px;height:42px;border-radius:50%;border:2px solid transparent;transition:transform 0.15s,border-color 0.15s}
.swatch-item.selected .swatch-circle{border-color:#1a1a1a;transform:scale(1.1)}
.swatch-item.selected .swatch-circle::after{content:'';position:absolute;bottom:0;right:0;width:14px;height:14px;background:#1a1a1a;border-radius:50%;border:2px solid #faf9f5}
.swatch-hex{font-size:9.5px;color:#aaa;font-weight:500;letter-spacing:0.04em}
.swatch-label{font-size:9px;color:#ccc;text-transform:uppercase;letter-spacing:0.06em}
.swatch-add{width:42px;height:42px;border-radius:50%;border:1.5px dashed #ddd;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#ccc;font-size:20px;transition:border-color 0.15s,color 0.15s}
.swatch-add:hover{border-color:#bbb;color:#888}
.logo-upload-area{border:1.5px dashed #e0ddd6;border-radius:10px;padding:28px 20px;text-align:center;cursor:pointer;background:#fafaf8;transition:all 0.15s;display:flex;flex-direction:column;align-items:center;gap:8px;position:relative}
.logo-upload-area:hover{border-color:#bbb;background:#f5f3ee}
.logo-upload-area.has-logo{border-style:solid;border-color:#e0ddd6;background:#fff;padding:20px}
.lu-icon i{font-size:22px;color:#ccc}
.lu-title{font-size:13px;font-weight:500;color:#888}
.lu-sub{font-size:11.5px;color:#bbb}
.btn-browse{height:30px;padding:0 16px;border:1px solid #e0ddd6;border-radius:7px;background:#fff;color:#666;font-size:12px;font-weight:500;font-family:inherit;cursor:pointer;margin-top:4px;transition:background 0.1s;display:inline-flex;align-items:center;justify-content:center}
.btn-browse:hover{background:#f5f3ee}
.logo-preview-strip{display:flex;align-items:center;gap:12px;width:100%;margin-top:4px}
.logo-thumb-dark{width:56px;height:56px;border-radius:10px;background:#111;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.logo-thumb-light{width:56px;height:56px;border-radius:10px;background:#f2f0eb;border:1px solid #e0ddd6;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.logo-thumb-dark img,.logo-thumb-light img{max-width:26px;max-height:26px;object-fit:contain}
.logo-info{flex:1;text-align:left}
.logo-name{font-size:12.5px;font-weight:500;color:#1a1a1a;margin-bottom:2px}
.logo-meta{font-size:11.5px;color:#aaa}
.btn-use-logo-colors{height:34px;padding:0 16px;background:#f5eaf4;border:1px solid #e0d0de;border-radius:8px;font-size:12.5px;font-weight:500;color:#871F7A;font-family:inherit;cursor:pointer;display:flex;align-items:center;gap:6px;margin-top:12px;transition:background 0.1s;width:100%;justify-content:center}
.btn-use-logo-colors:hover{background:#edd5eb}
.btn-use-logo-colors i{font-size:12px}
.logo-colors-applied{display:flex;align-items:center;gap:7px;margin-top:12px;background:#f0f9f2;border:1px solid #c8e0cc;border-radius:8px;padding:10px 14px;width:100%}
.lca-swatches{display:flex;gap:5px}
.lca-dot{width:18px;height:18px;border-radius:50%}
.lca-text{font-size:12px;color:#2d6636;font-weight:500;flex:1}
.lca-undo{font-size:11px;color:#aaa;background:none;border:none;cursor:pointer;font-family:inherit;padding:0}
.lca-undo:hover{color:#555}
.tone-pills{display:flex;flex-wrap:wrap;gap:8px}
.tone-pill{font-size:12.5px;font-weight:500;padding:7px 16px;border:1.5px solid #e0ddd6;border-radius:20px;cursor:pointer;color:#666;background:#fff;font-family:inherit;transition:all 0.15s}
.tone-pill:hover{border-color:#aaa;color:#333}
.tone-pill.selected{border-color:#1a1a1a;background:#1a1a1a;color:#fff}
.ref-brands-list{display:flex;flex-wrap:wrap;gap:7px;margin-bottom:8px}
.ref-brand{display:flex;align-items:center;gap:6px;font-size:12.5px;padding:5px 10px 5px 8px;border:1px solid #e0ddd6;border-radius:8px;background:#fff;color:#555}
.ref-brand-dot{width:16px;height:16px;border-radius:4px;flex-shrink:0;background:#1a1a1a}
.ref-brand-del{background:none;border:none;cursor:pointer;color:#ccc;padding:0;margin-left:2px;font-size:15px;line-height:1;transition:color 0.1s}
.ref-brand-del:hover{color:#888}
.brand-input-wrap{border:1px solid #e0ddd6;border-radius:9px;padding:0 14px;background:#fff;height:44px;display:flex;align-items:center;transition:border-color 0.15s}
.brand-input-wrap:focus-within{border-color:#c8c4bc}
.brand-input-bare{border:none;outline:none;font-size:13px;font-family:inherit;color:#1a1a1a;background:transparent;width:100%}
.brand-input-bare::placeholder{color:#c8c8c8}
.gen-strip{background:#f5f0ff;border:1px solid #d8cced;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:14px;margin-bottom:22px}
.gen-icon{width:38px;height:38px;border-radius:10px;background:#871F7A;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.gen-icon i{font-size:16px;color:#fff}
.gen-body{flex:1;min-width:0}
.gen-title{font-size:13.5px;font-weight:600;color:#2d0a2a;margin-bottom:2px;letter-spacing:-0.01em}
.gen-sub{font-size:12px;color:#7a4a78;line-height:1.5}
.btn-gen-now{height:36px;padding:0 18px;background:#871F7A;color:#fff;border:none;border-radius:8px;font-size:12.5px;font-weight:600;font-family:inherit;cursor:pointer;display:flex;align-items:center;gap:6px;white-space:nowrap;flex-shrink:0;transition:background 0.1s}
.btn-gen-now:hover{background:#6d1864}
.btn-gen-now i{font-size:12px}
.website-row{display:flex;align-items:center;gap:8px;margin-bottom:16px;padding:10px 14px;background:#faf9f5;border:1px solid #e8e5de;border-radius:10px}
.website-icon{width:22px;height:22px;border-radius:5px;background:#f0eee5;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.website-icon i{font-size:11px;color:#888}
.website-url{font-size:13px;font-weight:500;color:#3E93E8;flex:1}
.website-meta{font-size:11px;color:#aaa;display:flex;align-items:center;gap:4px;white-space:nowrap}
.website-meta i{font-size:11px;color:#2FAF49;flex-shrink:0}
.btn-edit{height:26px;padding:0 12px;border:1px solid #e0ddd6;border-radius:6px;background:#fff;color:#555;font-size:11.5px;font-weight:500;font-family:inherit;cursor:pointer;transition:background 0.1s;white-space:nowrap;flex-shrink:0}
.btn-edit:hover{background:#f5f3ee}
.review-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px}
.review-card{border:1px solid #e8e5de;border-radius:10px;overflow:hidden}
.review-card.full-width{grid-column:1/-1}
.rc-head{display:flex;align-items:center;justify-content:space-between;padding:10px 13px;background:#faf9f5;border-bottom:0.5px solid #e8e5de}
.rc-label{font-size:9.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.09em;color:#aaa}
.rc-body{padding:12px 13px}
.rc-text{font-size:12.5px;color:#444;line-height:1.6}
.rc-tags{display:flex;flex-wrap:wrap;gap:5px}
.rc-tag{font-size:11.5px;background:#f2f0eb;border:0.5px solid #e0ddd6;border-radius:5px;padding:3px 9px;color:#555}
.rc-tag.green{background:#edf5ee;border-color:#c8e0cc;color:#2d6636}
.color-swatches-preview{display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:10px}
.color-swatch{display:flex;flex-direction:column;align-items:center;gap:4px}
.cs-circle{width:32px;height:32px;border-radius:50%}
.cs-hex{font-size:9px;color:#aaa;font-weight:500;letter-spacing:0.03em}
.footer-bar{display:flex;align-items:center;justify-content:space-between;padding:18px 28px;border-top:0.5px solid #eee;background:#fafaf8;gap:12px;position:sticky;bottom:0;z-index:8;box-shadow:0 -10px 24px rgba(20,20,20,.04)}
.btn-back{height:38px;padding:0 18px;background:transparent;color:#888;border:1px solid #e0ddd6;border-radius:8px;font-size:13px;font-weight:500;font-family:inherit;cursor:pointer;display:flex;align-items:center;gap:6px}
.btn-back:hover{background:#f0eee8}
.btn-next{height:38px;padding:0 22px;background:#1a1a1a;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;font-family:inherit;cursor:pointer;display:flex;align-items:center;gap:6px}
.btn-next:hover{background:#333}
.footer-center{display:flex;flex-direction:column;align-items:center;gap:4px}
.footer-step,.footer-note{font-size:11.5px;color:#bbb}
.saved-note{display:flex;align-items:center;gap:5px;font-size:11.5px;color:#2FAF49;font-weight:500}
.saved-note i{font-size:11px}
.btn-generate{height:40px;padding:0 22px;background:#871F7A;color:#fff;border:none;border-radius:9px;font-size:13px;font-weight:600;font-family:inherit;cursor:pointer;display:flex;align-items:center;gap:7px;white-space:nowrap;transition:background 0.1s}
.btn-generate:hover{background:#6d1864}
.btn-generate i{font-size:12px}
.footer-center{flex:1}
#prev-btn,#next-btn,#final-save-btn{display:inline-flex;align-items:center;justify-content:center;flex-shrink:0}
#final-save-btn.atlas-hidden{display:none!important}
.atlas-hidden{display:none!important}
.atlas-saving{opacity:.7;pointer-events:none}
@keyframes fadeIn{from{opacity:0;transform:translateY(-3px)}to{opacity:1;transform:translateY(0)}}
.anim-in{animation:fadeIn 0.18s ease forwards}
#hex-add-row{display:none;margin-top:10px;flex-direction:row;align-items:center;gap:8px}
.hex-preview{width:26px;height:26px;border-radius:6px;border:1px solid #e0ddd6;flex-shrink:0}
.hex-input{height:34px;width:110px;border:1px solid #e0ddd6;border-radius:7px;padding:0 10px;font-size:12.5px;font-family:inherit;color:#1a1a1a;background:#fff;outline:none}
.hex-input:focus{border-color:#c8c4bc}
.btn-add-hex{height:34px;padding:0 14px;border:1px solid #e0ddd6;border-radius:7px;background:#fff;color:#666;font-size:12px;font-weight:500;font-family:inherit;cursor:pointer}
.btn-add-hex:hover{background:#f5f3ee}
.btn-cancel-hex{height:34px;padding:0 12px;background:transparent;border:none;color:#aaa;font-size:12px;font-family:inherit;cursor:pointer}
@media (max-width: 991px){
  .ai-input-row,.section-title-row,.gen-strip,.footer-bar,.website-row{flex-direction:column;align-items:stretch}
  .review-grid{grid-template-columns:1fr}
  .footer-center{align-items:flex-start}
  #prev-btn,#next-btn,#final-save-btn{width:100%}
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

            <div class="wrap">
                <div class="shell">
                    <div class="hdr">
                        <div class="hdr-title" id="hdr-title">Tell us about your business</div>
                        <div class="hdr-sub" id="hdr-sub">This takes 2 minutes. Your answers power everything Atlas generates.</div>
                        <div class="steps">
                            <div class="step active" data-step="1"><div class="step-num">1</div><span>Business info</span></div>
                            <div class="step-connector"></div>
                            <div class="step" data-step="2"><div class="step-num">2</div><span>Brand guidelines</span></div>
                            <div class="step-connector"></div>
                            <div class="step" data-step="3"><div class="step-num">3</div><span>Review</span></div>
                        </div>
                    </div>

                    <form method="post" enctype="multipart/form-data" id="company-intelligence-form">
                        <input type="hidden" name="website_snapshot_json" id="website_snapshot_json" value="<?php echo !empty($websiteSnapshot) ? _esc(json_encode($websiteSnapshot), 0) : ''; ?>">
                        <input type="hidden" name="website_extracted_at" id="website_extracted_at" value="<?php _esc(!empty($social_profile['website_extracted_at']) ? $social_profile['website_extracted_at'] : '') ?>">
                        <textarea class="atlas-hidden" name="top_problems_solved" id="top_problems_solved"><?php _esc($topProblemsSolved) ?></textarea>
                        <textarea class="atlas-hidden" name="unique_selling_points" id="unique_selling_points"><?php _esc($uniqueSellingPoints) ?></textarea>
                        <textarea class="atlas-hidden" name="brand_colors" id="brand_colors"><?php _esc(implode("\n", $brandColors)) ?></textarea>
                        <textarea class="atlas-hidden" name="reference_brands" id="reference_brands"><?php _esc(implode("\n", $referenceBrands)) ?></textarea>
                        <textarea class="atlas-hidden" name="competitors" id="competitors"><?php _esc(implode("\n", $competitors)) ?></textarea>
                        <textarea class="atlas-hidden" name="tone_attributes" id="tone_attributes"><?php _esc(implode("\n", $toneAttributes)) ?></textarea>
                        <textarea class="atlas-hidden" name="target_audience" id="target_audience"><?php _esc($social_profile['target_audience']) ?></textarea>
                        <textarea class="atlas-hidden" name="brand_voice" id="brand_voice"><?php _esc($social_profile['brand_voice']) ?></textarea>
                        <textarea class="atlas-hidden" name="content_goals" id="content_goals"><?php _esc($social_profile['content_goals']) ?></textarea>
                        <textarea class="atlas-hidden" name="key_products" id="key_products"><?php _esc($social_profile['key_products']) ?></textarea>
                        <textarea class="atlas-hidden" name="differentiators" id="differentiators"><?php _esc($social_profile['differentiators']) ?></textarea>
                        <textarea class="atlas-hidden" name="competitor_notes" id="competitor_notes"><?php _esc($social_profile['competitor_notes']) ?></textarea>
                        <textarea class="atlas-hidden" name="visual_mood" id="visual_mood"><?php _esc(!empty($social_profile['visual_mood']) && is_array($social_profile['visual_mood']) ? implode("\n", $social_profile['visual_mood']) : '') ?></textarea>
                        <textarea class="atlas-hidden" name="existing_moodboard_images" id="existing_moodboard_images"><?php _esc(!empty($social_profile['moodboard_images']) && is_array($social_profile['moodboard_images']) ? implode("\n", $social_profile['moodboard_images']) : '') ?></textarea>
                        <input class="atlas-hidden" type="text" name="instagram_handle" id="instagram_handle" value="<?php _esc($social_profile['instagram_handle']) ?>">

                        <div class="body">
                            <div class="panel active" data-panel="1">
                                <div class="ai-zone">
                                    <div class="ai-badge"><i class="fa fa-star-o"></i> AI Extract</div>
                                    <div class="ai-zone-title">Let AI read your website and fill this in</div>
                                    <div class="ai-zone-sub">Paste your URL and Atlas will extract your ICP, problems, and USPs automatically. You can edit everything after.</div>
                                    <div class="ai-input-row">
                                        <input class="ai-input" type="text" placeholder="https://yourwebsite.com" id="company_website" name="company_website" value="<?php _esc($social_profile['company_website']) ?>">
                                        <button class="btn-extract" type="button" id="extract-btn">Extract with AI</button>
                                    </div>
                                    <div class="ai-note"><i class="fa fa-info-circle"></i> Your site is only read for extraction — never stored or shared.</div>
                                </div>

                                <div class="divider-label"><span>or fill in manually</span></div>

                                <div class="field-group">
                                    <div class="field-label">Company description</div>
                                    <div class="field-hint">What does your company do and who is it for? 2–4 sentences.</div>
                                    <textarea rows="3" name="company_description" id="company_description" placeholder="e.g. Gopal Metro is a music business coach helping independent musicians turn their audience into sustainable income…"><?php _esc($social_profile['company_description']) ?></textarea>
                                    <div class="ai-sug-wrap" id="wrap-company_description">
                                        <div class="ai-sug-full anim-in" id="full-company_description">
                                            <div class="ai-sug-icon"><i class="fa fa-star-o"></i></div>
                                            <div class="ai-sug-body">
                                                <div class="ai-sug-text"><strong><?php _esc($aiSuggestions['company_description']['title']) ?></strong> <?php _esc($aiSuggestions['company_description']['text']) ?></div>
                                                <div class="ai-sug-actions">
                                                    <button class="btn-apply" type="button" data-apply-field="company_description">Apply suggestion</button>
                                                </div>
                                            </div>
                                            <button class="btn-x" type="button" data-close-suggestion="company_description"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="field-group">
                                    <div class="field-label">Ideal customer profile (ICP)</div>
                                    <div class="field-hint">Who is your best customer? Think job title, situation, goals, pain points.</div>
                                    <textarea rows="3" name="ideal_customer_profile" id="ideal_customer_profile" placeholder="e.g. Independent musicians with 1K–25K followers who struggle to monetize their audience beyond streaming…"><?php _esc($social_profile['ideal_customer_profile']) ?></textarea>
                                    <div class="ai-sug-wrap" id="wrap-ideal_customer_profile">
                                        <div class="ai-sug-full anim-in" id="full-ideal_customer_profile">
                                            <div class="ai-sug-icon"><i class="fa fa-star-o"></i></div>
                                            <div class="ai-sug-body">
                                                <div class="ai-sug-text"><strong><?php _esc($aiSuggestions['ideal_customer_profile']['title']) ?></strong> <?php _esc($aiSuggestions['ideal_customer_profile']['text']) ?></div>
                                                <div class="ai-sug-actions">
                                                    <button class="btn-apply" type="button" data-apply-field="ideal_customer_profile">Apply suggestion</button>
                                                </div>
                                            </div>
                                            <button class="btn-x" type="button" data-close-suggestion="ideal_customer_profile"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="field-group">
                                    <div class="field-label">Top problems you solve <span class="field-badge">3–5</span></div>
                                    <div class="field-hint">Press Enter after each problem to add it as a tag.</div>
                                    <div class="tag-field" id="prob-field" data-target="top_problems_solved">
                                        <input class="tag-input-bare" id="prob-input" placeholder="Add a problem…">
                                    </div>
                                    <div class="ai-sug-wrap" id="wrap-top_problems_solved">
                                        <div class="ai-sug-full anim-in" id="full-top_problems_solved">
                                            <div class="ai-sug-icon"><i class="fa fa-star-o"></i></div>
                                            <div class="ai-sug-body">
                                                <div class="ai-sug-text"><strong><?php _esc($aiSuggestions['top_problems_solved']['title']) ?></strong> <?php _esc($aiSuggestions['top_problems_solved']['text']) ?></div>
                                                <div class="ai-sug-actions">
                                                    <button class="btn-apply" type="button" data-apply-field="top_problems_solved">Apply suggestion</button>
                                                </div>
                                            </div>
                                            <button class="btn-x" type="button" data-close-suggestion="top_problems_solved"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="field-group">
                                    <div class="field-label">Unique selling points (USPs) <span class="field-badge">3–5</span></div>
                                    <div class="field-hint">What makes you different or better than the alternatives?</div>
                                    <div class="tag-field" id="usp-field" data-target="unique_selling_points">
                                        <input class="tag-input-bare" id="usp-input" placeholder="Add a USP…">
                                    </div>
                                    <div class="ai-sug-wrap" id="wrap-unique_selling_points">
                                        <div class="ai-sug-full anim-in" id="full-unique_selling_points">
                                            <div class="ai-sug-icon"><i class="fa fa-star-o"></i></div>
                                            <div class="ai-sug-body">
                                                <div class="ai-sug-text"><strong><?php _esc($aiSuggestions['unique_selling_points']['title']) ?></strong> <?php _esc($aiSuggestions['unique_selling_points']['text']) ?></div>
                                                <div class="ai-sug-actions">
                                                    <button class="btn-apply" type="button" data-apply-field="unique_selling_points">Apply suggestion</button>
                                                </div>
                                            </div>
                                            <button class="btn-x" type="button" data-close-suggestion="unique_selling_points"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="divider-label"><span>optional</span></div>

                                <div class="field-group">
                                    <div class="field-label">Competitor websites <span class="field-badge">optional</span></div>
                                    <div class="field-hint">We'll analyse their positioning to sharpen yours. Add up to 5 URLs.</div>
                                    <div class="tag-field" id="comp-field" data-target="competitors">
                                        <input class="tag-input-bare" id="comp-input" placeholder="Add a competitor URL…">
                                    </div>
                                </div>

                                <div class="atlas-hidden">
                                    <input type="text" name="company_name" id="company_name" value="<?php _esc($social_profile['company_name']) ?>">
                                    <input type="text" name="company_industry" id="company_industry" value="<?php _esc($social_profile['company_industry']) ?>">
                                    <input type="text" name="founder_name" id="founder_name" value="<?php _esc($social_profile['founder_name']) ?>">
                                    <input type="text" name="founder_title" id="founder_title" value="<?php _esc($social_profile['founder_title']) ?>">
                                </div>
                            </div>

                            <div class="panel" data-panel="2">
                                <div class="section-block">
                                    <div class="section-num">01 — Brand colors</div>
                                    <div class="section-title-row">
                                        <div>
                                            <div class="section-title">Confirm your brand colors</div>
                                            <div class="section-sub">We extracted these from <?php _esc($brandColorSource) ?>. Select the ones to keep, remove the rest, or add new ones.</div>
                                        </div>
                                        <button class="btn-rescan" type="button" id="rescan-colors"><i class="fa fa-refresh"></i> Rescan</button>
                                    </div>
                                    <div class="color-zone">
                                        <div class="cz-top">
                                            <div class="cz-source">
                                                Extracted from
                                                <div class="cz-url"><i class="fa fa-check-circle-o"></i> <?php _esc($brandColorSource) ?></div>
                                            </div>
                                            <div class="cz-count" id="brand-colors-count"><?php echo count($brandColors); ?> colors found</div>
                                        </div>
                                        <div class="swatches-row" id="swatches-row"></div>
                                        <div id="hex-add-row">
                                            <div class="hex-preview" id="hex-preview" style="background:#000"></div>
                                            <input class="hex-input" id="hex-val" type="text" placeholder="#000000" maxlength="7">
                                            <button class="btn-add-hex" type="button" id="add-hex-btn">Add color</button>
                                            <button class="btn-cancel-hex" type="button" id="cancel-hex-btn">Cancel</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="section-block">
                                    <div class="section-num">02 — Logo <span class="opt-badge">optional</span></div>
                                    <div class="section-title">Upload your logo</div>
                                    <div class="section-sub">We'll use it to preview how your content will look before you generate.</div>
                                    <div id="logo-dropzone" class="logo-upload-area">
                                        <div class="lu-icon"><i class="fa fa-picture-o"></i></div>
                                        <div class="lu-title">Drop your logo here</div>
                                        <div class="lu-sub">SVG, PNG, or JPG — transparent background preferred</div>
                                        <label class="btn-browse">
                                            Browse files
                                            <input type="file" name="company_logo" id="company_logo" accept="image/*" style="display:none;">
                                        </label>
                                    </div>
                                    <div id="logo-uploaded" style="<?php echo !empty($currentLogo) ? '' : 'display:none'; ?>">
                                        <div class="logo-upload-area has-logo">
                                            <div class="logo-preview-strip">
                                                <div class="logo-thumb-dark"><?php if(!empty($currentLogo)){ ?><img src="<?php echo UPLOAD_URI . '/' . _esc($currentLogo, 0); ?>" alt="logo"><?php } ?></div>
                                                <div class="logo-thumb-light"><?php if(!empty($currentLogo)){ ?><img src="<?php echo UPLOAD_URI . '/' . _esc($currentLogo, 0); ?>" alt="logo"><?php } ?></div>
                                                <div class="logo-info">
                                                    <div class="logo-name" id="logo-file-name"><?php _esc(!empty($currentLogo) ? basename($currentLogo) : 'brand-logo'); ?></div>
                                                    <div class="logo-meta" id="logo-file-meta"><?php _e("Uploaded"); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="logo-colors-btn-wrap">
                                            <button class="btn-use-logo-colors" type="button" id="use-logo-colors"><i class="fa fa-check-circle-o"></i> Use logo colors for my brand</button>
                                        </div>
                                        <div id="logo-colors-applied" style="display:none" class="logo-colors-applied">
                                            <div class="lca-swatches" id="logo-colors-swatches"></div>
                                            <div class="lca-text">Logo colors applied to your brand palette</div>
                                            <button class="lca-undo" type="button" id="undo-logo-colors">Undo</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="section-block">
                                    <div class="section-num">03 — Tone of voice</div>
                                    <div class="section-title">How should your content sound?</div>
                                    <div class="section-sub" style="margin-bottom:14px">Pick all that apply.</div>
                                    <div class="tone-pills" id="tone-pills">
                                        <?php foreach ($allToneOptions as $option) { ?>
                                            <button class="tone-pill <?php echo in_array($option, $toneAttributes, true) ? 'selected' : ''; ?>" type="button" data-tone="<?php _esc($option) ?>"><?php _esc($option) ?></button>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="section-block">
                                    <div class="section-num">04 — Reference brands <span class="opt-badge">optional</span></div>
                                    <div class="section-title">Brands or accounts you admire</div>
                                    <div class="section-sub">We'll use their style as a reference point, not a template.</div>
                                    <div class="ref-brands-list" id="brand-tags"></div>
                                    <div class="brand-input-wrap">
                                        <input class="brand-input-bare" id="brand-input" placeholder="@handle, brand name, or website URL — press Enter to add">
                                    </div>
                                </div>
                            </div>

                            <div class="panel" data-panel="3">
                                <div class="gen-strip">
                                    <div class="gen-icon"><i class="fa fa-star-o"></i></div>
                                    <div class="gen-body">
                                        <div class="gen-title">Ready to generate your marketing statements</div>
                                        <div class="gen-sub">Atlas will use this profile to write your hooks, value propositions, and Instagram content.</div>
                                    </div>
                                    <button class="btn-gen-now" type="button" id="refresh-intelligence"><i class="fa fa-star-o"></i> Generate now</button>
                                </div>

                                <div class="website-row">
                                    <div class="website-icon"><i class="fa fa-globe"></i></div>
                                    <div class="website-url" id="review-website"><?php _esc(!empty($social_profile['company_website']) ? $social_profile['company_website'] : 'No website added yet.') ?></div>
                                    <div class="website-meta"><i class="fa fa-check-circle-o"></i> Scanned successfully<?php echo !empty($social_profile['website_extracted_at']) ? ' · ' . _esc($social_profile['website_extracted_at']) : ''; ?></div>
                                    <button class="btn-edit" type="button" data-jump-step="1">Edit</button>
                                </div>

                                <div class="review-grid">
                                    <div class="review-card full-width">
                                        <div class="rc-head"><span class="rc-label">Company description</span><button class="btn-edit" type="button" data-jump-step="1">Edit</button></div>
                                        <div class="rc-body"><div class="rc-text" id="review-company-description"><?php _esc(!empty($social_profile['company_description']) ? $social_profile['company_description'] : 'Not added yet.') ?></div></div>
                                    </div>

                                    <div class="review-card">
                                        <div class="rc-head"><span class="rc-label">Ideal customer (ICP)</span><button class="btn-edit" type="button" data-jump-step="1">Edit</button></div>
                                        <div class="rc-body"><div class="rc-text" id="review-icp"><?php _esc(!empty($social_profile['ideal_customer_profile']) ? $social_profile['ideal_customer_profile'] : 'Not added yet.') ?></div></div>
                                    </div>

                                    <div class="review-card">
                                        <div class="rc-head"><span class="rc-label">Problems solved</span><button class="btn-edit" type="button" data-jump-step="1">Edit</button></div>
                                        <div class="rc-body"><div class="rc-tags" id="review-problems"></div></div>
                                    </div>

                                    <div class="review-card">
                                        <div class="rc-head"><span class="rc-label">USPs</span><button class="btn-edit" type="button" data-jump-step="1">Edit</button></div>
                                        <div class="rc-body"><div class="rc-tags" id="review-usps"></div></div>
                                    </div>

                                    <div class="review-card">
                                        <div class="rc-head"><span class="rc-label">Competitors</span><button class="btn-edit" type="button" data-jump-step="1">Edit</button></div>
                                        <div class="rc-body">
                                            <div class="rc-tags" id="review-competitors"></div>
                                            <div style="display:flex;align-items:center;gap:5px;font-size:11.5px;color:#aaa;margin-top:8px"><i class="fa fa-check-circle-o" style="color:#2FAF49"></i> Competitor analysed · positioning gap found</div>
                                        </div>
                                    </div>

                                    <div class="review-card">
                                        <div class="rc-head"><span class="rc-label">Brand tone</span><button class="btn-edit" type="button" data-jump-step="2">Edit</button></div>
                                        <div class="rc-body"><div class="rc-tags" id="review-tone"></div></div>
                                    </div>

                                    <div class="review-card">
                                        <div class="rc-head"><span class="rc-label">Brand colors</span><button class="btn-edit" type="button" data-jump-step="2">Edit</button></div>
                                        <div class="rc-body">
                                            <div class="color-swatches-preview" id="review-colors-swatches"></div>
                                            <div style="font-size:11px;color:#aaa" id="review-colors-note"><?php echo count($brandColors); ?> colors confirmed from <?php _esc($brandColorSource) ?></div>
                                        </div>
                                    </div>

                                    <div class="review-card full-width">
                                        <div class="rc-head"><span class="rc-label">Atlas company intelligence</span></div>
                                        <div class="rc-body">
                                            <div class="rc-text"><strong>Company summary:</strong> <span id="review-company-summary"><?php _esc(!empty($company_intelligence['company_summary']) ? $company_intelligence['company_summary'] : 'Not generated yet.') ?></span></div>
                                            <div class="rc-text" style="margin-top:10px;"><strong>Market research:</strong> <span id="review-market-research"><?php _esc(!empty($company_intelligence['market_research']) ? $company_intelligence['market_research'] : 'Not generated yet.') ?></span></div>
                                            <div class="rc-text" style="margin-top:10px;"><strong>Competitive edges:</strong> <span id="review-competitive-edges"><?php _esc(!empty($company_intelligence['competitive_edges']) ? $company_intelligence['competitive_edges'] : 'Not generated yet.') ?></span></div>
                                        </div>
                                    </div>
                                </div>
                                <?php if(!empty($social_error)){ ?><div class="field-hint" style="margin-top:10px;color:#c05959;"><?php _esc($social_error) ?></div><?php } ?>
                            </div>
                        </div>

                        <div class="footer-bar">
                            <button class="btn-back" type="button" id="prev-btn"><i class="fa fa-arrow-left"></i> Back</button>
                            <div class="footer-center">
                                <div class="footer-step" id="footer-step">Step 1 of 3 · Progress saved automatically</div>
                                <div class="saved-note" id="saved-note"><i class="fa fa-check"></i> Not saved yet</div>
                            </div>
                            <button class="btn-next" type="button" id="next-btn">Save & continue <i class="fa fa-arrow-right"></i></button>
                            <button class="btn-generate atlas-hidden" type="submit" name="company-intelligence-submit" id="final-save-btn"><i class="fa fa-star-o"></i> Try to generate content</button>
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
    var stepMeta = {
        1: {title: 'Tell us about your business', sub: 'This takes 2 minutes. Your answers power everything Atlas generates.'},
        2: {title: "What's your visual direction?", sub: 'We pulled your brand colors from your website. Confirm them, add your logo, then set your tone and references.'},
        3: {title: 'Review your business profile', sub: 'Check everything looks right. Edit any section before generating.'}
    };
    var suggestionState = {
        company_description: 'full',
        ideal_customer_profile: 'full',
        top_problems_solved: 'full',
        unique_selling_points: 'full'
    };

    function parseLines(value) {
        if (!value) return [];
        return value.split(/\n+/).map(function (item) { return $.trim(item); }).filter(Boolean);
    }

    function escapeHtml(value) {
        return $('<div>').text(value || '').html();
    }

    function updateSaveState(message, ok) {
        $('#saved-note').html('<i class="fa ' + (ok ? 'fa-check' : 'fa-clock-o') + '"></i> ' + escapeHtml(message));
        $('#saved-note').css('color', ok ? '#2FAF49' : '#bbb');
    }

    function setHeader(step) {
        $('#hdr-title').text(stepMeta[step].title);
        $('#hdr-sub').text(stepMeta[step].sub);
        $('.step').removeClass('active done').each(function () {
            var num = parseInt($(this).data('step'), 10);
            if (num < step) $(this).addClass('done');
            if (num === step) $(this).addClass('active');
        });
        $('.panel').removeClass('active');
        $('.panel[data-panel="' + step + '"]').addClass('active');
        $('#footer-step').text('Step ' + step + ' of 3 · ' + (step === 2 ? 'Saves automatically' : 'Progress saved automatically'));
        $('#prev-btn').toggle(step > 1);
        $('#next-btn').toggle(step < 3);
        $('#final-save-btn').toggleClass('atlas-hidden', step !== 3);
        if (step === 3) updateReview();
        currentStep = step;
    }

    function syncHiddenFromTags(targetId, fieldId) {
        var values = [];
        $('#' + targetId + ' .tag').each(function () {
            values.push($(this).attr('data-value'));
        });
        $('#' + fieldId).val(values.join("\n"));
    }

    function makeTag(text, inputId, targetId, fieldId) {
        var $tag = $('<span class="tag" data-value="' + escapeHtml(text) + '"></span>');
        $tag.append(document.createTextNode(text));
        var $del = $('<button class="tag-del" type="button">×</button>');
        $del.on('click', function () {
            $tag.remove();
            syncHiddenFromTags(targetId, fieldId);
            updateReview();
        });
        $tag.append($del);
        $('#' + inputId).before($tag);
    }

    function initTagEditor(containerId, inputId, fieldId) {
        $('#' + containerId + ' .tag').remove();
        parseLines($('#' + fieldId).val()).forEach(function (item) {
            makeTag(item, inputId, containerId, fieldId);
        });
        $('#' + inputId).on('keydown', function (e) {
            if (e.key === 'Enter' && $.trim(this.value)) {
                e.preventDefault();
                makeTag($.trim(this.value), inputId, containerId, fieldId);
                this.value = '';
                syncHiddenFromTags(containerId, fieldId);
                updateReview();
            }
        });
    }

    function renderSuggestionState(field) {
        var state = suggestionState[field];
        var $wrap = $('#wrap-' + field);
        var $full = $('#full-' + field);
        $wrap.find('.ai-help-pill, .ai-applied-row').remove();
        if (state === 'full') {
            $full.show();
            return;
        }
        $full.hide();
        if (state === 'closed') {
            $wrap.append('<button class="ai-help-pill" type="button" data-open-suggestion="' + field + '"><i class="fa fa-star-o"></i> AI help available</button>');
        }
        if (state === 'applied') {
            $wrap.append('<div class="ai-applied-row"><i class="fa fa-check"></i><span>Suggestion applied</span><span style="color:#bbb;font-size:11px;margin-left:4px">·</span><button type="button" data-open-suggestion="' + field + '" style="font-size:11px;color:#aaa;background:none;border:none;cursor:pointer;font-family:inherit;padding:0;margin-left:2px">Undo / see more AI help</button></div>');
        }
    }

    function renderColorSwatches(colors) {
        var $row = $('#swatches-row');
        $row.empty();
        colors.forEach(function (color, index) {
            $row.append(
                '<div class="swatch-item selected" data-color="' + escapeHtml(color) + '">' +
                '<div class="swatch-circle" style="background:' + escapeHtml(color) + ';border:' + (/^#fff|#f/i.test(color) ? '1px solid #e0ddd6' : '2px solid transparent') + '"></div>' +
                '<div class="swatch-hex">' + escapeHtml(color) + '</div>' +
                '<div class="swatch-label">' + (index === 0 ? 'Primary' : (index === 1 ? 'Light' : (index === 2 ? 'Accent' : 'Brand'))) + '</div>' +
                '</div>'
            );
        });
        $row.append('<div style="display:flex;flex-direction:column;align-items:center;gap:5px"><div class="swatch-add" id="swatch-add-btn">+</div><div style="font-size:9.5px;color:#ccc">Add</div></div>');
        $('#brand-colors-count').text(colors.length + ' colors found');
    }

    function renderReferenceBrands(values) {
        var $wrap = $('#brand-tags');
        $wrap.empty();
        values.forEach(function (value) {
            var $item = $('<div class="ref-brand" data-value="' + escapeHtml(value) + '"><div class="ref-brand-dot"></div><span>' + escapeHtml(value) + '</span><button class="ref-brand-del" type="button">×</button></div>');
            $item.find('.ref-brand-del').on('click', function () {
                $item.remove();
                syncReferenceBrands();
            });
            $wrap.append($item);
        });
    }

    function syncReferenceBrands() {
        var values = [];
        $('#brand-tags .ref-brand').each(function () {
            values.push($(this).attr('data-value'));
        });
        $('#reference_brands').val(values.join("\n"));
        updateReview();
    }

    function syncTone() {
        var values = [];
        $('#tone-pills .tone-pill.selected').each(function () {
            values.push($(this).attr('data-tone'));
        });
        $('#tone_attributes').val(values.join("\n"));
        updateReview();
    }

    function updateReview() {
        var colors = parseLines($('#brand_colors').val());
        $('#review-website').text($('#company_website').val() || 'No website added yet.');
        $('#review-company-description').text($('#company_description').val() || 'Not added yet.');
        $('#review-icp').text($('#ideal_customer_profile').val() || 'Not added yet.');

        function renderTags(selector, items, green) {
            var $wrap = $(selector);
            $wrap.empty();
            if (!items.length) {
                $wrap.append('<div class="rc-text">Not added yet.</div>');
                return;
            }
            items.forEach(function (item) {
                $wrap.append('<span class="rc-tag' + (green ? ' green' : '') + '">' + escapeHtml(item) + '</span>');
            });
        }

        renderTags('#review-problems', parseLines($('#top_problems_solved').val()), true);
        renderTags('#review-usps', parseLines($('#unique_selling_points').val()), false);
        renderTags('#review-competitors', parseLines($('#competitors').val()), false);
        renderTags('#review-tone', parseLines($('#tone_attributes').val()), false);

        var $swatches = $('#review-colors-swatches');
        $swatches.empty();
        colors.forEach(function (color) {
            $swatches.append('<div class="color-swatch"><div class="cs-circle" style="background:' + escapeHtml(color) + ';border:1px solid rgba(0,0,0,.08)"></div><div class="cs-hex">' + escapeHtml(color) + '</div></div>');
        });
        $('#review-colors-note').text(colors.length ? (colors.length + ' colors confirmed from ' + ($('#company_website').val() || 'your brand profile')) : 'No colors confirmed yet.');
        $('#review-company-summary').text('<?php echo addslashes(!empty($company_intelligence['company_summary']) ? $company_intelligence['company_summary'] : 'Not generated yet.'); ?>');
        $('#review-market-research').text('<?php echo addslashes(!empty($company_intelligence['market_research']) ? $company_intelligence['market_research'] : 'Not generated yet.'); ?>');
        $('#review-competitive-edges').text('<?php echo addslashes(!empty($company_intelligence['competitive_edges']) ? $company_intelligence['competitive_edges'] : 'Not generated yet.'); ?>');
    }

    function validateStep(step) {
        if (step === 1) {
            if (!$.trim($('#company_description').val()) || !$.trim($('#ideal_customer_profile').val()) || !parseLines($('#top_problems_solved').val()).length || !parseLines($('#unique_selling_points').val()).length) {
                quick_alert('Please complete the key business information before continuing.', 'error');
                updateSaveState('Business info is incomplete', false);
                return false;
            }
        }
        if (step === 2) {
            if (!parseLines($('#brand_colors').val()).length || !parseLines($('#tone_attributes').val()).length) {
                quick_alert('Please complete your brand guidelines before continuing.', 'error');
                updateSaveState('Brand guidelines are incomplete', false);
                return false;
            }
        }
        return true;
    }

    function saveDraft(step, callback) {
        var formData = new FormData(document.getElementById('company-intelligence-form'));
        formData.append('step', step);
        updateSaveState('Saving...', false);
        $.ajax({
            url: ajaxurl + '?action=save_company_intelligence_draft',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false
        }).done(function (response) {
            response = typeof response === 'string' ? JSON.parse(response) : response;
            if (!response.success) {
                var message = response.error || 'Save failed.';
                quick_alert(message, 'error');
                updateSaveState(message, false);
                return;
            }
            updateSaveState(response.message || 'Saved successfully.', true);
            if (typeof callback === 'function') callback(response);
        }).fail(function () {
            quick_alert('Atlas could not save this step right now.', 'error');
            updateSaveState('Save failed.', false);
        });
    }

    function applySuggestion(field) {
        var $btn = $('[data-apply-field="' + field + '"]');
        $btn.addClass('atlas-saving').text('Applying...');
        $.post(ajaxurl + '?action=generate_company_intelligence_field', $('#company-intelligence-form').serialize() + '&field=' + encodeURIComponent(field), function (response) {
            response = typeof response === 'string' ? JSON.parse(response) : response;
            if (!response.success) {
                quick_alert(response.error || 'Atlas could not generate this field right now.', 'error');
                return;
            }
            var value = response.value;
            if (field === 'company_description' || field === 'ideal_customer_profile') {
                $('#' + field).val(value || '');
            } else if (field === 'top_problems_solved' || field === 'unique_selling_points') {
                $('#' + field).val((value || []).join("\n"));
                initTagEditor(field === 'top_problems_solved' ? 'prob-field' : 'usp-field', field === 'top_problems_solved' ? 'prob-input' : 'usp-input', field);
            }
            suggestionState[field] = 'applied';
            renderSuggestionState(field);
            updateReview();
        }).always(function () {
            $btn.removeClass('atlas-saving').text('Apply suggestion');
        });
    }

    $('#extract-btn').on('click', function () {
        var $btn = $(this);
        $btn.addClass('atlas-saving').text('Extracting...');
        $.post(ajaxurl + '?action=extract_company_profile', {website: $('#company_website').val()}, function (response) {
            response = typeof response === 'string' ? JSON.parse(response) : response;
            if (!response.success) {
                quick_alert(response.error || 'Unable to extract your website right now.', 'error');
                return;
            }
            var p = response.profile || {};
            $('#company_website').val(p.company_website || $('#company_website').val());
            $('#company_name').val(p.company_name || $('#company_name').val());
            $('#company_industry').val(p.company_industry || $('#company_industry').val());
            $('#company_description').val(p.company_description || $('#company_description').val());
            $('#ideal_customer_profile').val(p.ideal_customer_profile || $('#ideal_customer_profile').val());
            $('#website_snapshot_json').val(p.website_snapshot ? JSON.stringify(p.website_snapshot) : $('#website_snapshot_json').val());
            $('#website_extracted_at').val(p.website_extracted_at || $('#website_extracted_at').val());
            if (p.top_problems_solved) $('#top_problems_solved').val((p.top_problems_solved || []).join("\n"));
            if (p.unique_selling_points) $('#unique_selling_points').val((p.unique_selling_points || []).join("\n"));
            if (p.competitors) $('#competitors').val((p.competitors || []).join("\n"));
            if (p.reference_brands) $('#reference_brands').val((p.reference_brands || []).join("\n"));
            if (p.brand_colors) $('#brand_colors').val((p.brand_colors || []).join("\n"));
            if (p.tone_attributes) $('#tone_attributes').val((p.tone_attributes || []).join("\n"));
            initTagEditor('prob-field', 'prob-input', 'top_problems_solved');
            initTagEditor('usp-field', 'usp-input', 'unique_selling_points');
            initTagEditor('comp-field', 'comp-input', 'competitors');
            renderReferenceBrands(parseLines($('#reference_brands').val()));
            renderColorSwatches(parseLines($('#brand_colors').val()));
            $('#tone-pills .tone-pill').each(function () {
                $(this).toggleClass('selected', parseLines($('#tone_attributes').val()).indexOf($(this).attr('data-tone')) !== -1);
            });
            updateReview();
            quick_alert(response.message || 'Website extracted successfully.', 'success');
        }).always(function () {
            $btn.removeClass('atlas-saving').text('Extract with AI');
        });
    });

    $('#rescan-colors').on('click', function () {
        var $btn = $(this);
        $btn.addClass('atlas-saving').html('<i class="fa fa-refresh"></i> Scanning…');
        $.post(ajaxurl + '?action=generate_company_intelligence_field', $('#company-intelligence-form').serialize() + '&field=brand_colors', function (response) {
            response = typeof response === 'string' ? JSON.parse(response) : response;
            if (!response.success) {
                quick_alert(response.error || 'Atlas could not rescan your brand colors right now.', 'error');
                return;
            }
            $('#brand_colors').val((response.value || []).join("\n"));
            renderColorSwatches(parseLines($('#brand_colors').val()));
            updateReview();
        }).always(function () {
            $btn.removeClass('atlas-saving').html('<i class="fa fa-refresh"></i> Rescan');
        });
    });

    $(document).on('click', '[data-apply-field]', function () {
        applySuggestion($(this).attr('data-apply-field'));
    });
    $(document).on('click', '[data-close-suggestion]', function () {
        var field = $(this).attr('data-close-suggestion');
        suggestionState[field] = 'closed';
        renderSuggestionState(field);
    });
    $(document).on('click', '[data-open-suggestion]', function () {
        var field = $(this).attr('data-open-suggestion');
        suggestionState[field] = 'full';
        renderSuggestionState(field);
    });

    $('#tone-pills').on('click', '.tone-pill', function () {
        $(this).toggleClass('selected');
        syncTone();
    });

    $('#brand-input').on('keydown', function (e) {
        if (e.key === 'Enter' && $.trim(this.value)) {
            e.preventDefault();
            var values = parseLines($('#reference_brands').val());
            values.push($.trim(this.value));
            $('#reference_brands').val(values.join("\n"));
            renderReferenceBrands(values);
            this.value = '';
            syncReferenceBrands();
        }
    });

    $('#swatches-row').on('click', '.swatch-item', function () {
        $(this).toggleClass('selected');
    });
    $(document).on('click', '#swatch-add-btn', function () {
        $('#hex-add-row').css('display', 'flex');
        $('#hex-val').trigger('focus');
    });
    $('#hex-val').on('input', function () {
        if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
            $('#hex-preview').css('background', this.value);
        }
    });
    $('#cancel-hex-btn').on('click', function () {
        $('#hex-add-row').hide();
        $('#hex-val').val('');
        $('#hex-preview').css('background', '#000');
    });
    $('#add-hex-btn').on('click', function () {
        var v = $.trim($('#hex-val').val());
        if (!/^#[0-9A-Fa-f]{6}$/.test(v)) return;
        var values = parseLines($('#brand_colors').val());
        values.push(v);
        $('#brand_colors').val(values.join("\n"));
        renderColorSwatches(values);
        updateReview();
        $('#cancel-hex-btn').trigger('click');
    });

    $('#company_logo').on('change', function () {
        if (!this.files || !this.files[0]) return;
        var file = this.files[0];
        var url = URL.createObjectURL(file);
        $('#logo-dropzone').hide();
        $('#logo-uploaded').show();
        $('#logo-file-name').text(file.name);
        $('#logo-file-meta').text('Ready to upload');
        $('.logo-thumb-dark, .logo-thumb-light').html('<img src="' + url + '" alt="logo">');
    });
    $('#use-logo-colors').on('click', function () {
        var colors = parseLines($('#brand_colors').val()).slice(0, 2);
        var $wrap = $('#logo-colors-swatches');
        $wrap.empty();
        colors.forEach(function (color) {
            $wrap.append('<div class="lca-dot" style="background:' + escapeHtml(color) + ';border:1px solid rgba(0,0,0,.12)"></div>');
        });
        $('#logo-colors-btn-wrap').hide();
        $('#logo-colors-applied').show();
    });
    $('#undo-logo-colors').on('click', function () {
        $('#logo-colors-applied').hide();
        $('#logo-colors-btn-wrap').show();
    });

    $('#prev-btn').on('click', function () {
        if (currentStep > 1) setHeader(currentStep - 1);
    });
    $('#next-btn').on('click', function () {
        if (!validateStep(currentStep)) return;
        saveDraft(currentStep, function () {
            if (currentStep < 3) setHeader(currentStep + 1);
        });
    });
    $(document).on('click', '[data-jump-step]', function () {
        setHeader(parseInt($(this).attr('data-jump-step'), 10));
    });

    $('#refresh-intelligence').on('click', function () {
        var $btn = $(this);
        $btn.addClass('atlas-saving').html('<i class="fa fa-star-o"></i> Generating...');
        $.post(ajaxurl + '?action=refresh_company_intelligence', {}, function (response) {
            response = typeof response === 'string' ? JSON.parse(response) : response;
            if (!response.success) {
                quick_alert(response.error || 'Unable to refresh company intelligence right now.', 'error');
                return;
            }
            if (response.intelligence) {
                $('#review-company-summary').text(response.intelligence.company_summary || '');
                $('#review-market-research').text(response.intelligence.market_research || '');
                $('#review-competitive-edges').text(response.intelligence.competitive_edges || '');
            }
            quick_alert(response.message || 'Company intelligence refreshed successfully.', 'success');
        }).always(function () {
            $btn.removeClass('atlas-saving').html('<i class="fa fa-star-o"></i> Generate now');
        });
    });

    $('#company-intelligence-form').on('input change', 'textarea, input[type=text], input[type=file]', function () {
        updateSaveState('Unsaved changes', false);
        updateReview();
    });

    initTagEditor('prob-field', 'prob-input', 'top_problems_solved');
    initTagEditor('usp-field', 'usp-input', 'unique_selling_points');
    initTagEditor('comp-field', 'comp-input', 'competitors');
    renderReferenceBrands(parseLines($('#reference_brands').val()));
    renderColorSwatches(parseLines($('#brand_colors').val()));
    syncTone();
    updateReview();
    Object.keys(suggestionState).forEach(function (field) { renderSuggestionState(field); });
    setHeader(1);
});
</script>
<?php
overall_footer();
