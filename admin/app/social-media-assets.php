<?php
include '../includes.php';

social_media_bootstrap();

$message = '';
$error = '';
$editAsset = null;

if (!empty($_GET['edit'])) {
    $editAsset = social_media_get_asset((int) $_GET['edit']);
}

if (!empty($_GET['reanalyze'])) {
    social_media_refresh_asset_analysis((int) $_GET['reanalyze'], true);
    $message = __('Asset reanalyzed successfully.');
    if (!empty($_GET['edit']) && (int) $_GET['edit'] === (int) $_GET['reanalyze']) {
        $editAsset = social_media_get_asset((int) $_GET['edit']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['delete_asset_id'])) {
    if (social_media_delete_asset((int) $_POST['delete_asset_id'])) {
        $message = __('Asset deleted successfully.');
        $editAsset = null;
    } else {
        $error = __('Asset not found.');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['delete_asset_id'])) {
    $payload = [
        'id' => !empty($_POST['asset_id']) ? (int) $_POST['asset_id'] : null,
        'title' => validate_input($_POST['title']),
        'asset_type' => validate_input($_POST['asset_type']),
        'post_type' => validate_input($_POST['post_type']),
        'tags' => validate_input($_POST['tags']),
        'text_position' => validate_input($_POST['text_position']),
        'render_preset' => validate_input($_POST['render_preset']),
        'manifest_json' => isset($_POST['manifest_json']) ? trim($_POST['manifest_json']) : '',
        'status' => !empty($_POST['status']) ? 1 : 0,
    ];

    $result = social_media_save_asset($payload);
    if ($result['success']) {
        $message = !empty($payload['id']) ? __('Asset updated successfully.') : __('Asset uploaded successfully.');
        $editAsset = social_media_get_asset($result['id']);
    } else {
        $error = $result['error'];
    }
}

$page_title = __('Social Media Assets');
$assets = social_media_get_assets(['status' => 'all']);
$ffmpegReady = social_media_ffmpeg_path() !== '';
$fontCatalog = social_media_get_available_fonts();

include '../header.php'; ?>
<div class="page-body-wrapper">
    <?php include '../sidebar.php'; ?>
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-lg-6 main-header">
                        <h2><?php _esc($page_title) ?></h2>
                        <h6 class="mb-0"><?php _e('admin panel') ?></h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <?php if ($message) { ?>
                <div class="alert alert-success"><?php _esc($message) ?></div>
            <?php } ?>
            <?php if ($error) { ?>
                <div class="alert alert-danger"><?php _esc($error) ?></div>
            <?php } ?>
            <div class="row">
                <div class="col-xl-4">
                    <div class="quick-card card">
                        <div class="card-header">
                            <h5><?php _e('Upload Asset') ?></h5>
                            <span><?php _e('Use mostly empty backgrounds for posts/carousels and clean motion backgrounds for reels. Assets with baked-in text will be treated as low-priority references.') ?></span>
                        </div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data">
                                <input type="hidden" name="asset_id" value="<?php echo !empty($editAsset['id']) ? (int) $editAsset['id'] : 0; ?>">
                                <div class="form-group">
                                    <label><?php _e('Title') ?></label>
                                    <input type="text" name="title" class="form-control" value="<?php echo !empty($editAsset['title']) ? _esc($editAsset['title'], 0) : ''; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label><?php _e('Asset Type') ?></label>
                                    <select name="asset_type" class="form-control">
                                        <option value="image" <?php echo (!empty($editAsset['asset_type']) && $editAsset['asset_type'] === 'image') ? 'selected' : ''; ?>><?php _e('Image') ?></option>
                                        <option value="video" <?php echo (!empty($editAsset['asset_type']) && $editAsset['asset_type'] === 'video') ? 'selected' : ''; ?>><?php _e('Video') ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><?php _e('Post Type') ?></label>
                                    <select name="post_type" class="form-control">
                                        <option value="all" <?php echo (!empty($editAsset['post_type']) && $editAsset['post_type'] === 'all') ? 'selected' : ''; ?>><?php _e('All') ?></option>
                                        <option value="post" <?php echo (!empty($editAsset['post_type']) && $editAsset['post_type'] === 'post') ? 'selected' : ''; ?>><?php _e('Post') ?></option>
                                        <option value="carousel" <?php echo (!empty($editAsset['post_type']) && $editAsset['post_type'] === 'carousel') ? 'selected' : ''; ?>><?php _e('Carousel') ?></option>
                                        <option value="reel" <?php echo (!empty($editAsset['post_type']) && $editAsset['post_type'] === 'reel') ? 'selected' : ''; ?>><?php _e('Reel') ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><?php _e('Tags') ?></label>
                                    <input type="text" name="tags" class="form-control" value="<?php echo !empty($editAsset['tags']) ? _esc($editAsset['tags'], 0) : ''; ?>" placeholder="<?php _e('luxury, startup, clean, dark, fintech') ?>">
                                </div>
                                <div class="form-group">
                                    <label><?php _e('Text Position') ?></label>
                                    <select name="text_position" class="form-control">
                                        <option value="center" <?php echo (empty($editAsset['text_position']) || $editAsset['text_position'] === 'center') ? 'selected' : ''; ?>><?php _e('Center') ?></option>
                                        <option value="top" <?php echo (!empty($editAsset['text_position']) && $editAsset['text_position'] === 'top') ? 'selected' : ''; ?>><?php _e('Top') ?></option>
                                        <option value="bottom" <?php echo (!empty($editAsset['text_position']) && $editAsset['text_position'] === 'bottom') ? 'selected' : ''; ?>><?php _e('Bottom') ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><?php _e('Render Preset') ?></label>
                                    <select name="render_preset" class="form-control">
                                        <option value="auto" <?php echo (empty($editAsset['render_preset']) || $editAsset['render_preset'] === 'auto') ? 'selected' : ''; ?>><?php _e('Auto Analyze') ?></option>
                                        <option value="manual" <?php echo (!empty($editAsset['render_preset']) && $editAsset['render_preset'] === 'manual') ? 'selected' : ''; ?>><?php _e('Manual Manifest') ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><?php _e('Primary File') ?></label>
                                    <input type="file" name="asset_file" class="form-control" <?php echo empty($editAsset['id']) ? 'required' : ''; ?>>
                                </div>
                                <div class="form-group">
                                    <label><?php _e('Preview Image') ?></label>
                                    <input type="file" name="asset_preview" class="form-control">
                                    <small class="form-text text-muted"><?php _e('Optional for video assets. Used for reel covers inside the generator.') ?></small>
                                </div>
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="asset_status" name="status" <?php echo (!isset($editAsset['status']) || !empty($editAsset['status'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="asset_status"><?php _e('Active') ?></label>
                                </div>
                                <?php if (!empty($editAsset['preview_name'])) { ?>
                                    <div class="form-group">
                                        <img src="<?php echo $config['site_url'] . 'storage/social_assets/' . $editAsset['preview_name']; ?>" alt="" style="max-width: 120px; border-radius: 8px;">
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label><?php _e('Template Manifest JSON') ?></label>
                                    <textarea name="manifest_json" class="form-control" rows="12"><?php echo !empty($editAsset['manifest_json']) ? _esc($editAsset['manifest_json']) : ''; ?></textarea>
                                    <small class="form-text text-muted"><?php _e('Leave empty to use the auto-generated manifest from asset analysis.') ?></small>
                                </div>
                                <button type="submit" class="btn btn-primary"><?php echo !empty($editAsset['id']) ? __('Update Asset') : __('Upload Asset'); ?></button>
                                <?php if (!empty($editAsset['id'])) { ?>
                                    <a href="<?php echo ADMINURL; ?>app/social-media-assets.php" class="btn btn-light"><?php _e('Cancel') ?></a>
                                    <a href="<?php echo ADMINURL; ?>app/social-media-assets.php?edit=<?php echo (int) $editAsset['id']; ?>&reanalyze=<?php echo (int) $editAsset['id']; ?>" class="btn btn-warning"><?php _e('Reanalyze') ?></a>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <?php if (!empty($editAsset['analysis'])) { ?>
                        <div class="quick-card card">
                            <div class="card-header">
                                <h5><?php _e('Asset Analysis') ?></h5>
                                <span><?php _e('Auto-detected safe zones, brightness, clutter, OCR text, and recommended text color.') ?></span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong><?php _e('Best Text Zone') ?>:</strong> <?php _esc($editAsset['analysis']['best_text_zone']) ?></p>
                                        <p><strong><?php _e('Suggested Text Color') ?>:</strong> <?php _esc($editAsset['analysis']['suggested_text_color']) ?></p>
                                        <p><strong><?php _e('Overlay Opacity') ?>:</strong> <?php _esc($editAsset['analysis']['overlay_opacity']) ?></p>
                                        <p><strong><?php _e('Source Dimensions') ?>:</strong> <?php _esc($editAsset['analysis']['source_width'] . 'x' . $editAsset['analysis']['source_height']) ?></p>
                                        <p><strong><?php _e('Background Tone') ?>:</strong> <?php _esc($editAsset['analysis']['background_tone']) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong><?php _e('Brightness') ?>:</strong> <?php _esc(json_encode($editAsset['analysis']['brightness'])) ?></p>
                                        <p><strong><?php _e('Clutter') ?>:</strong> <?php _esc(json_encode($editAsset['analysis']['clutter'])) ?></p>
                                        <p><strong><?php _e('Template Kind') ?>:</strong> <?php _esc($editAsset['analysis']['template_kind']) ?></p>
                                        <p><strong><?php _e('Empty Layout Score') ?>:</strong> <?php _esc($editAsset['analysis']['empty_layout_score']) ?></p>
                                    </div>
                                </div>
                                <?php if (!empty($editAsset['analysis']['dominant_colors'])) { ?>
                                    <p><strong><?php _e('Dominant Colors') ?>:</strong> <?php _esc(implode(', ', $editAsset['analysis']['dominant_colors'])) ?></p>
                                <?php } ?>
                                <?php if (!empty($editAsset['analysis']['ocr_text'])) { ?>
                                    <div class="alert alert-light"><?php _esc($editAsset['analysis']['ocr_text']) ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="quick-card card">
                        <div class="card-header">
                            <h5><?php _e('Asset Library') ?></h5>
                            <span><?php _e('The generator scores these assets using post type match, tag overlap, and the template manifest generated from analysis.') ?></span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th><?php _e('Preview') ?></th>
                                        <th><?php _e('Title') ?></th>
                                        <th><?php _e('Type') ?></th>
                                        <th><?php _e('Post Type') ?></th>
                                            <th><?php _e('Tags') ?></th>
                                            <th><?php _e('Best Zone') ?></th>
                                            <th><?php _e('Tone') ?></th>
                                            <th><?php _e('Kind') ?></th>
                                            <th><?php _e('Status') ?></th>
                                            <th><?php _e('Action') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($assets as $asset) { ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($asset['preview_name'])) { ?>
                                                    <img src="<?php echo $config['site_url'] . 'storage/social_assets/' . $asset['preview_name']; ?>" alt="" style="width: 70px; border-radius: 8px;">
                                                <?php } else { ?>
                                                    <span class="badge badge-secondary"><?php _e('No Preview') ?></span>
                                                <?php } ?>
                                            </td>
                                            <td><?php _esc($asset['title']) ?></td>
                                            <td><?php _esc(ucfirst($asset['asset_type'])) ?></td>
                                            <td><?php _esc(ucfirst($asset['post_type'])) ?></td>
                                            <td><?php _esc($asset['tags']) ?></td>
                                            <td><?php _esc(!empty($asset['analysis']['best_text_zone']) ? ucfirst($asset['analysis']['best_text_zone']) : '-') ?></td>
                                            <td><?php _esc(!empty($asset['analysis']['background_tone']) ? ucfirst($asset['analysis']['background_tone']) : '-') ?></td>
                                            <td><?php _esc(!empty($asset['analysis']['template_kind']) ? ucfirst($asset['analysis']['template_kind']) : '-') ?></td>
                                            <td><?php echo !empty($asset['status']) ? '<span class="badge badge-success">' . __('Active') . '</span>' : '<span class="badge badge-secondary">' . __('Inactive') . '</span>'; ?></td>
                                            <td>
                                                <a href="<?php echo ADMINURL; ?>app/social-media-assets.php?edit=<?php echo (int) $asset['id']; ?>" class="btn btn-sm btn-primary"><?php _e('Edit') ?></a>
                                                <a href="<?php echo ADMINURL; ?>app/social-media-assets.php?reanalyze=<?php echo (int) $asset['id']; ?>" class="btn btn-sm btn-warning"><?php _e('Reanalyze') ?></a>
                                                <form method="post" style="display:inline-block;" onsubmit="return confirm('<?php echo escape(__('Delete this asset?')); ?>')">
                                                    <input type="hidden" name="delete_asset_id" value="<?php echo (int) $asset['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger"><?php _e('Delete') ?></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="quick-card card">
                        <div class="card-header">
                            <h5><?php _e('Font Library') ?></h5>
                            <span><?php _e('These are the renderable social fonts Atlas sends to the AI. The model can only choose from this approved list.') ?></span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($fontCatalog as $key => $font) { ?>
                                    <div class="col-md-6 mb-2">
                                        <strong><?php _esc($font['label']) ?></strong>
                                        <div><code><?php _esc($key) ?></code></div>
                                        <small><?php _esc($font['style']) ?></small>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="quick-card card">
                        <div class="card-header">
                            <h5><?php _e('How Selection Works') ?></h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><?php _e('1. OpenAI creates 3 posts, 3 carousels, and 3 reels with hooks, captions, keywords, font keys, text sizes, alignment, and palette instructions based on company context, competitors, and recent agent history.') ?></p>
                            <p class="mb-2"><?php _e('2. The generator filters assets by post type and asset type, then scores tag overlap, background tone, and whether the asset is a clean background or a text-heavy reference.') ?></p>
                            <p class="mb-2"><?php _e('3. Atlas renders the final cover automatically: logo, headline, supporting hook, brand line, and CTA are placed using the selected fonts and design palette.') ?></p>
                            <p class="mb-0"><?php echo $ffmpegReady ? __('4. ffmpeg is available, so reel outputs can be rendered as real MP4 files when a video asset is selected.') : __('4. ffmpeg is not available in this environment, so reels will fall back to branded covers plus script metadata.'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var QuickMenu = {"page":"social-media-assets", "subpage":"social-media-assets"};
    </script>
<?php include '../footer.php'; ?>
