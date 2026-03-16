<?php
include '../includes.php';

social_media_bootstrap();

$message = '';
$error = '';
$editAsset = null;

if (!empty($_GET['edit'])) {
    $editAsset = social_media_get_asset((int) $_GET['edit']);
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
                            <span><?php _e('Use images for posts and carousels, and videos plus optional previews for reels.') ?></span>
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
                                <button type="submit" class="btn btn-primary"><?php echo !empty($editAsset['id']) ? __('Update Asset') : __('Upload Asset'); ?></button>
                                <?php if (!empty($editAsset['id'])) { ?>
                                    <a href="<?php echo ADMINURL; ?>app/social-media-assets.php" class="btn btn-light"><?php _e('Cancel') ?></a>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="quick-card card">
                        <div class="card-header">
                            <h5><?php _e('Asset Library') ?></h5>
                            <span><?php _e('The generator scores these assets using post type match and tag overlap with generated keywords.') ?></span>
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
                                            <td><?php echo !empty($asset['status']) ? '<span class="badge badge-success">' . __('Active') . '</span>' : '<span class="badge badge-secondary">' . __('Inactive') . '</span>'; ?></td>
                                            <td>
                                                <a href="<?php echo ADMINURL; ?>app/social-media-assets.php?edit=<?php echo (int) $asset['id']; ?>" class="btn btn-sm btn-primary"><?php _e('Edit') ?></a>
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
                            <h5><?php _e('How Selection Works') ?></h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><?php _e('1. OpenAI creates 3 posts, 3 carousels, and 3 reels with hooks, captions, and keywords based on company context, competitors, and recent agent history.') ?></p>
                            <p class="mb-2"><?php _e('2. The generator filters assets by post type and asset type, then scores tag matches against the generated keywords and company industry.') ?></p>
                            <p class="mb-2"><?php _e('3. Atlas renders the final cover automatically: logo, headline, supporting hook, and CTA are placed on top of the selected asset.') ?></p>
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
