<!DOCTYPE html>
<html lang="en" dir="<?php echo e(session()->get('direction') == 2 ? 'rtl' : 'ltr'); ?>"  class="light">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta property="og:title" content="<?php echo e(helper::appdata('')->meta_title); ?>" />
    <meta property="og:description" content="<?php echo e(helper::appdata('')->meta_description); ?>" />
    <meta property="og:image" content="<?php echo e(helper::image_path(helper::appdata('')->og_image)); ?>" />

    <script>
        const theme = localStorage.getItem('theme');
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.add('light');
        }
    </script>

    <link rel="icon" href="<?php echo e(helper::image_path(helper::appdata('')->favicon)); ?>" type="image" sizes="16x16">
    <title><?php echo e(helper::appdata('')->website_title); ?></title>
    <link rel="stylesheet" href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/bootstrap/bootstrap.min.css')); ?>">
    <link rel="stylesheet"
        href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/bootstrap/bootstrap-select.min.css')); ?>">
    <!--multi-selection css-->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/fontawesome/all.min.css')); ?>">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/toastr/toastr.min.css')); ?>">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/sweetalert/sweetalert2.min.css')); ?>">
    <!-- Sweetalert CSS -->
    <link rel="stylesheet" href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/style.css')); ?>"><!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/responsive.css')); ?>">
    <!-- Responsive CSS -->
    <link rel="stylesheet"
        href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/timepicker/jquery.timepicker.min.css')); ?>">
    <link rel="stylesheet"
        href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/datatables/dataTables.bootstrap5.min.css')); ?>">
    <link rel="stylesheet"
        href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/datatables/buttons.dataTables.min.css')); ?>">
    <!-- magnific-popup -->
    <link rel="stylesheet" href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/magnific/magnific-popup.min.css')); ?>">

    <style>
        :root {
            /* Color */
            --bs-primary: <?php echo e(helper::appdata('')->primary_color); ?>;
            --bs-secondary: <?php echo e(helper::appdata('')->secondary_color); ?>;
        }
    </style>
</head>

<body>
    <?php
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
    ?>
    <main>
        <div class="wrapper">
            <?php echo $__env->make('admin.layout.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="content-wrapper">
                <?php echo $__env->make('admin.layout.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="<?php echo e(session()->get('direction') == 2 ? 'main-content-rtl' : 'main-content'); ?>">
                    <div class="page-content">
                        <div class="container-fluid">
                            <div class="row">
                                <?php if(env('Environment') == 'sendbox'): ?>
                                    <div class="alert alert-warning mt-3" role="alert">
                                        <p>According to Envato's license policy, an extended license is required for
                                            SaaS usage. <a class="btn btn-primary px-sm-4 btn-sm ms-2 active"
                                                href="https://1.envato.market/Yg7YmB" target="_blank">Buy Now
                                            </a></p>
                                    </div>
                                <?php endif; ?>
                                <div class="col-12 ml-sm-auto">
                                    <?php if(env('Environment') == 'live'): ?>
                                        <?php if(request()->is('admin/custom_domain')): ?>
                                            <div class="alert alert-warning" role="alert">
                                                <?php echo e(trans('messages.custom_domain_message')); ?>

                                            </div>
                                        <?php endif; ?>
                                        <?php if(request()->is('admin/apps')): ?>
                                            <div class="alert alert-warning" role="alert">
                                                <?php echo e(trans('messages.addon_message')); ?>

                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if(Auth::user()->type == 2): ?>
                                        <?php
                                        $checkplan = helper::checkplan(Auth::user()->id, '');
                                        $plan = json_decode(json_encode($checkplan));
                                        ?>
                                        <?php if(@$plan->original->status == '2' && @$plan->original->showclick != 2): ?>
                                            <div class="alert alert-warning" role="alert">
                                                <?php echo e(@$plan->original->message); ?><?php echo e(empty($plan->original->expdate) ? '' : ':' . $plan->original->expdate); ?>

                                                <?php if(@$plan->original->showclick == 1): ?>
                                                    <u><a
                                                            href="<?php echo e(URL::to('/admin/plan')); ?>"><?php echo e(trans('labels.click_here')); ?></a></u>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!--Modal: order-modal-->
                            <div class="modal fade" id="order-modal" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-notify modal-info" role="document">
                                    <div class="modal-content text-center">
                                        <div class="modal-header d-flex justify-content-center">
                                            <p class="heading color-changer"><?php echo e(trans('messages.be_up_to_date')); ?></p>
                                        </div>
                                        <div class="modal-body color-changer"><i
                                                class="fa fa-bell fa-4x animated rotateIn mb-4"></i>
                                            <p><?php echo e(trans('messages.new_order_arrive')); ?></p>
                                        </div>
                                        <div class="modal-footer flex-center">
                                            <a role="button" class="btn btn-secondary waves-effect"
                                                onClick="window.location.reload();"
                                                data-bs-dismiss="modal"><?php echo e(trans('labels.okay')); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php echo $__env->yieldContent('content'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!----- theme sidebar ----->
            <div class="offcanvas <?php echo e(session()->get('direction') == 2 ? 'offcanvas-start' : 'offcanvas-end '); ?>"
                data-bs-scroll="true" tabindex="-1" id="themelabel" aria-labelledby="offcanvasWithBothOptionsLabel">

                <div class="offcanvas-header justify-content-between">
                    <h5 class="offcanvas-title color-changer" id="offcanvasWithBothOptionsLabel">All theme</h5>
                    <button type="button" class="bg-transparent border-0 m-0" data-bs-dismiss="offcanvas"
                        aria-label="Close">
                        <i class="fa-regular fa-xmark fs-4 color-changer"></i>
                    </button>
                </div>

                <div class="offcanvas-body">
                    <p class="color-changer">Try scrolling the rest of the page to see this option in action.</p>
                </div>

            </div>

            <footer class="py-3 text-center bg-white fixed-bottom border-top">
                <span><?php echo e(helper::appdata('')->copyright); ?></span>
            </footer>
        </div>

        <!--theme image Modal -->
        <div class="modal fade" id="themeinfo" tabindex="-1" aria-labelledby="themeinfoLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header justify-content-between">
                        <h5 class="modal-title text-dark color-changer" id="themeinfoLabel"></h5>
                        <button type="button" class="bg-transparent border-0" data-bs-dismiss="modal">
                            <i class="fa-regular fa-xmark fs-4 color-changer"></i>
                        </button>
                    </div>
                    <div class="modal-body" id="theme_modalbody">
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/jquery/jquery.min.js')); ?>"></script><!-- jQuery JS -->
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/bootstrap/bootstrap.bundle.min.js')); ?>"></script><!-- Bootstrap JS -->
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/bootstrap/bootstrap-select.min.js')); ?>"></script><!-- Bootstrap multi-select JS -->
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/toastr/toastr.min.js')); ?>"></script><!-- Toastr JS -->
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/sweetalert/sweetalert2.min.js')); ?>"></script><!-- Sweetalert JS -->
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/chartjs/chart_3.9.1.min.js')); ?>"></script>
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/datatables/jquery.dataTables.min.js')); ?>"></script><!-- Datatables JS -->
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/datatables/dataTables.bootstrap5.min.js')); ?>"></script><!-- Datatables Bootstrap5 JS -->
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/datatables/dataTables.buttons.min.js')); ?>"></script><!-- Datatables Buttons JS -->
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/datatables/jszip.min.js')); ?>"></script><!-- Datatables Excel Buttons JS -->
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/datatables/pdfmake.min.js')); ?>"></script><!-- Datatables Make PDF Buttons JS -->
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/datatables/vfs_fonts.js')); ?>"></script><!-- Datatables Export PDF Buttons JS -->
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/datatables/buttons.html5.min.js')); ?>"></script><!-- Datatables Buttons HTML5 JS -->
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/magnific/magnific-popup.min.js')); ?>"></script><!-- magnific-popup js -->

    <script>
        var are_you_sure = "<?php echo e(trans('messages.are_you_sure')); ?>";
        var yes = "<?php echo e(trans('messages.yes')); ?>";
        var no = "<?php echo e(trans('messages.no')); ?>";
        var cancel = "<?php echo e(trans('labels.cancel')); ?>";
        let wrong = "<?php echo e(trans('messages.wrong')); ?>";
        let env = "<?php echo e(env('Environment')); ?>";
        var time_format = "<?php echo e(helper::appdata($vendor_id)->time_format); ?>";
        // pdf and excel file name table wise
        var filename = "";
        var title = "";
        if ("<?php echo e(Auth::user()->type == 2); ?>" && "<?php echo e(request()->is('admin/dashboard')); ?>") {
            filename = "trans('labels.processing_orders')";
            title = "trans('labels.processing_orders')";
        }
        if ("<?php echo e(Auth::user()->type == 1); ?>" && "<?php echo e(request()->is('admin/dashboard')); ?>") {
            filename = "trans('labels.today_transaction')";
            title = "trans('labels.today_transaction')";
        }
        if ("<?php echo e(request()->is('admin/orders*')); ?>" || "<?php echo e(request()->is('admin/report')); ?>") {
            filename = "<?php echo e(trans('labels.orders')); ?>";
            title = "<?php echo e(trans('labels.orders')); ?>";
        }
        if ("<?php echo e(request()->is('admin/customers*')); ?>") {
            filename = "<?php echo e(trans('labels.customers')); ?>";
            title = "<?php echo e(trans('labels.customers')); ?>";
        }
        if ("<?php echo e(request()->is('admin/users*')); ?>") {
            filename = "<?php echo e(trans('labels.users')); ?>";
            title = "<?php echo e(trans('labels.users')); ?>";
        }
        if ("<?php echo e(request()->is('admin/countries*')); ?>") {
            filename = "<?php echo e(trans('labels.cities')); ?>";
            title = "<?php echo e(trans('labels.cities')); ?>";
        }
        if ("<?php echo e(request()->is('admin/cities*')); ?>") {
            filename = "<?php echo e(trans('labels.areas')); ?>";
            title = "<?php echo e(trans('labels.areas')); ?>";
        }
        if ("<?php echo e(request()->is('admin/how_it_works*')); ?>") {
            filename = "<?php echo e(trans('labels.how_it_works')); ?>";
            title = "<?php echo e(trans('labels.how_it_works')); ?>";
        }
        if ("<?php echo e(request()->is('admin/themes*')); ?>") {
            filename = "<?php echo e(trans('labels.theme_images')); ?>";
            title = "<?php echo e(trans('labels.theme_images')); ?>";
        }
        if ("<?php echo e(request()->is('admin/features*')); ?>") {
            filename = "<?php echo e(trans('labels.features')); ?>";
            title = "<?php echo e(trans('labels.features')); ?>";
        }
        if ("<?php echo e(request()->is('admin/promotionalbanners*')); ?>") {
            filename = "<?php echo e(trans('labels.promotional_banners')); ?>";
            title = "<?php echo e(trans('labels.promotional_banners')); ?>";
        }
        if ("<?php echo e(request()->is('admin/transaction')); ?>") {
            filename = "<?php echo e(trans('labels.transactions')); ?>";
            title = "<?php echo e(trans('labels.transactions')); ?>";
        }
        if ("<?php echo e(request()->is('admin/shipping-area')); ?>") {
            filename = "<?php echo e(trans('labels.shipping_area')); ?>";
            title = "<?php echo e(trans('labels.shipping_area')); ?>";
        }
        if ("<?php echo e(request()->is('admin/blogs')); ?>") {
            filename = "<?php echo e(trans('labels.blogs')); ?>";
            title = "<?php echo e(trans('labels.blogs')); ?>";
        }
        if ("<?php echo e(request()->is('admin/testimonials')); ?>") {
            filename = "<?php echo e(trans('labels.testimonials')); ?>";
            title = "<?php echo e(trans('labels.testimonials')); ?>";
        }
        if ("<?php echo e(request()->is('admin/faqs')); ?>") {
            filename = "<?php echo e(trans('labels.faqs')); ?>";
            title = "<?php echo e(trans('labels.faqs')); ?>";
        }
        if ("<?php echo e(request()->is('admin/categories')); ?>") {
            filename = "<?php echo e(trans('labels.categories')); ?>";
            title = "<?php echo e(trans('labels.categories')); ?>";
        }
        if ("<?php echo e(request()->is('admin/products')); ?>") {
            filename = "<?php echo e(trans('labels.products')); ?>";
            title = "<?php echo e(trans('labels.products')); ?>";
        }
        if ("<?php echo e(request()->is('admin/sliders')); ?>") {
            filename = "<?php echo e(trans('labels.sliders')); ?>";
            title = "<?php echo e(trans('labels.sliders')); ?>";
        }
        if ("<?php echo e(request()->is('admin/banner')); ?>") {
            filename = "<?php echo e(trans('labels.banners')); ?>";
            title = "<?php echo e(trans('labels.banners')); ?>";
        }
        if ("<?php echo e(request()->is('admin/coupons')); ?>") {
            filename = "<?php echo e(trans('labels.coupons')); ?>";
            title = "<?php echo e(trans('labels.coupons')); ?>";
        }
        if ("<?php echo e(request()->is('admin/roles')); ?>") {
            filename = "<?php echo e(trans('labels.roles')); ?>";
            title = "<?php echo e(trans('labels.roles')); ?>";
        }
        if ("<?php echo e(request()->is('admin/employees')); ?>") {
            filename = "<?php echo e(trans('labels.employees')); ?>";
            title = "<?php echo e(trans('labels.employees')); ?>";
        }
        if ("<?php echo e(request()->is('admin/subscribers')); ?>") {
            filename = "<?php echo e(trans('labels.subscribers')); ?>";
            title = "<?php echo e(trans('labels.subscribers')); ?>";
        }
        if ("<?php echo e(request()->is('admin/inquiries')); ?>") {
            filename = "<?php echo e(trans('labels.inquiries')); ?>";
            title = "<?php echo e(trans('labels.inquiries')); ?>";
        }
        if ("<?php echo e(request()->is('admin/language-settings')); ?>") {
            filename = "<?php echo e(trans('labels.language-settings')); ?>";
            title = "<?php echo e(trans('labels.language-settings')); ?>";
        }
        if ("<?php echo e(request()->is('admin/store_categories')); ?>") {
            filename = "<?php echo e(trans('labels.store_categories')); ?>";
            title = "<?php echo e(trans('labels.store_categories')); ?>";
        }

        toastr.options = {
            "closeButton": true,
            "positionClass": "toast-top-right",
        }
        <?php if(Session::has('success')): ?>
            toastr.success("<?php echo e(session('success')); ?>", "Success");
        <?php endif; ?>
        <?php if(Session::has('error')): ?>
            toastr.error("<?php echo e(session('error')); ?>", "Error");
        <?php endif; ?>

        <?php if(Auth::user()->type == 2): ?>
            // New Notification
            var noticount = 0;
            var notificationurl = "<?php echo e(URL::to('/admin/getorder')); ?>";
            var vendoraudio =
                "<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/notification/' . helper::appdata(Auth::user()->id)->notification_sound)); ?>";
        <?php endif; ?>
    </script>
    <?php if(@helper::checkaddons('notification')): ?>
        <?php if(Auth::user()->type == 2): ?>
            <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/sound.js')); ?>"></script>
        <?php endif; ?>
    <?php endif; ?>
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/jquery/jquery-ui.min.js')); ?>"></script>
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/common.js')); ?>"></script><!-- Common JS -->
    <?php echo $__env->yieldContent('scripts'); ?>
</body>

</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/envato_storemart/storemart_4.5/Storemart_Addon/resources/views/admin/layout/default.blade.php ENDPATH**/ ?>