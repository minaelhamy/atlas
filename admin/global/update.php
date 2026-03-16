<?php
include '../includes.php';

$page_title = __('Deployment');
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
            <div class="quick-card card">
                <div class="card-header">
                    <h5><?php _e('Git-based Deployments') ?></h5>
                </div>
                <div class="card-body">
                    <p><?php _e('Atlas no longer uses the vendor updater or purchase-code verification flow. Deploy changes through your Git repository and hosting automation.') ?></p>
                    <ul class="mb-0">
                        <li><?php _e('Keep server-only secrets in a .env file on the host.') ?></li>
                        <li><?php _e('Push code changes to GitHub.') ?></li>
                        <li><?php _e('Run the repository deployment workflow to publish to Namecheap.') ?></li>
                        <li><?php _e('Leave uploads, caches, and runtime files out of version control.') ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php
include '../footer.php';
