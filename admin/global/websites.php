<?php
include '../includes.php';

$page_title = __('Websites');
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
                <div class="card-body">
                    <div class="dataTables_wrapper">
                        <table class="table table-striped" id="ajax_datatable" data-jsonfile="websites.php" data-order-col="6">
                            <thead>
                            <tr>
                                <th><?php _e('Owner') ?></th>
                                <th><?php _e('Website') ?></th>
                                <th><?php _e('Type') ?></th>
                                <th><?php _e('Requests') ?></th>
                                <th><?php _e('Posted Earnings') ?></th>
                                <th><?php _e('Status') ?></th>
                                <th><?php _e('Updated') ?></th>
                                <th width="20" class="no-sort" data-priority="1"></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var QuickMenu = {"page":"websites"};
    </script>

<?php ob_start() ?>
<?php
$footer_content = ob_get_clean();

include '../footer.php';
