<!DOCTYPE html>

<html lang="en" dir="<?php echo e(session()->get('direction') == 2 ? 'rtl' : 'ltr'); ?>" class="light">

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

    <title><?php echo e(helper::appdata('')->web_title); ?></title>

    <?php if(Auth::user()->type == 4): ?>
        <link rel="icon" type="image" sizes="16x16"
            href="<?php echo e(helper::image_path(@helper::appdata('')->favicon)); ?>"><!-- Favicon icon -->
    <?php else: ?>
        <link rel="icon" type="image" sizes="16x16"
            href="<?php echo e(helper::image_path(@helper::appdata('')->favicon)); ?>"><!-- Favicon icon -->
    <?php endif; ?>
    <link rel="stylesheet" href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/bootstrap/bootstrap.min.css')); ?>">
    <link rel="stylesheet"
        href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/bootstrap/bootstrap-select.min.css')); ?>">
    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/fontawesome/all.min.css')); ?>">
    <!-- FontAwesome CSS -->

    <link rel="stylesheet" href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/toastr/toastr.min.css')); ?>">

    <!-- Toastr CSS -->

    <link rel="stylesheet" href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/sweetalert/sweetalert2.min.css')); ?>">

    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/chartjs/chart_3.9.1.min.js')); ?>"></script>

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

    <link rel="stylesheet" href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/fullcalendar.css')); ?>">
    <style>
        :root {
            /* Color */
            --bs-primary: <?php echo e(helper::appdata('')->primary_color); ?>;
            --bs-secondary: <?php echo e(helper::appdata('')->secondary_color); ?>;
        }
    </style>
</head>

<body class="">
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
                                    <div class="col-12">
                                        <div class="alert alert-warning mt-3" role="alert">
                                            <p class="d-flex flex-wrap align-items-center gap-2">According to Envato's
                                                license policy, an extended license is required for SaaS usage. <a
                                                    class="btn btn-primary px-sm-4 btn-sm active"
                                                    href="https://1.envato.market/KeRzWn" target="_blank">Buy Now
                                                </a></p>
                                        </div>
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

                                        <?php if(@$plan->original->status == '2'): ?>

                                            <div class="alert alert-warning" role="alert">

                                                <?php echo e(@$plan->original->message); ?><?php echo e(empty($plan->original->expdate) ? '' : ':' . $plan->original->expdate); ?>


                                                <?php if(@$plan->original->showclick == 1): ?>
                                                    <u><a href="<?php echo e(URL::to('/admin/plan')); ?>"
                                                            class="text-danger fw-500"><?php echo e(trans('labels.click_here')); ?></a></u>
                                                <?php endif; ?>

                                            </div>

                                        <?php endif; ?>

                                    <?php endif; ?>

                                </div>

                            </div>
                            <?php echo $__env->yieldContent('content'); ?>

                        </div>

                    </div>

                </div>



                <!--Modal: booking-modal-->

                <div class="modal fade" id="booking-modal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">

                    <div class="modal-dialog modal-fullscreen-md-down modal-notify modal-info" role="document">

                        <div class="modal-content text-center">
                            <div class="modal-body">
                                <img src="<?php echo e(url('storage/app/public/admin-assets/images/icons-bell.gif')); ?>"
                                    class="mb-4 icon-bell" alt="">
                                <p class="heading fs-5 fw-600 color-changer"><?php echo e(trans('messages.be_up_to_date')); ?></p>
                                <p class="fs-16 fw-500 color-changer"><?php echo e(trans('messages.new_booking_arrive')); ?></p>
                            </div>

                            <div class="modal-footer flex-center">

                                <a role="button" class="btn btn-primary m-0 waves-effect"
                                    onClick="window.location.reload();"
                                    data-bs-dismiss="modal"><?php echo e(trans('labels.okay')); ?></a>

                            </div>

                        </div>

                    </div>

                </div>

                <!--Modal: order-modal-->

                <div class="modal fade" id="order-modal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">

                    <div class="modal-dialog modal-fullscreen-md-down modal-notify modal-info" role="document">

                        <div class="modal-content text-center">
                            <div class="modal-body">
                                <img src="<?php echo e(url('storage/app/public/admin-assets/images/icons-bell.gif')); ?>"
                                    class="mb-4 icon-bell" alt="">
                                <p class="heading fs-5 fw-600 color-changer"><?php echo e(trans('messages.be_up_to_date')); ?></p>
                                <p class="fs-16 fw-500 color-changer"><?php echo e(trans('messages.new_order_arrive')); ?></p>
                            </div>

                            <div class="modal-footer flex-center">

                                <a role="button" class="btn btn-primary m-0 waves-effect"
                                    onClick="window.location.reload();"
                                    data-bs-dismiss="modal"><?php echo e(trans('labels.okay')); ?></a>

                            </div>

                        </div>

                    </div>

                </div>

                <!--Modal: modalPush-->

            </div>

            <?php echo $__env->make('admin.layout.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        </div>
        <!--theme image Modal -->
        <div class="modal fade" id="themeinfo" tabindex="-1" aria-labelledby="themeinfoLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header justify-content-between">
                        <h5 class="modal-title color-changer" id="themeinfoLabel"></h5>
                        <button type="button" class="bg-transparent border-0 color-changer" data-bs-dismiss="modal"
                            aria-label="Close">
                            <i class="fa-regular fa-xmark fs-4"></i>
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
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/bootstrap/bootstrap-select.min.js')); ?>"></script><!-- Bootstrap JS -->
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/toastr/toastr.min.js')); ?>"></script><!-- Toastr JS -->

    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/sweetalert/sweetalert2.min.js')); ?>"></script><!-- Sweetalert JS -->

    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/datatables/jquery.dataTables.min.js')); ?>"></script><!-- Datatables JS -->

    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/datatables/dataTables.bootstrap5.min.js')); ?>"></script><!-- Datatables Bootstrap5 JS -->

    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/datatables/dataTables.buttons.min.js')); ?>"></script><!-- Datatables Buttons JS -->

    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/datatables/jszip.min.js')); ?>"></script><!-- Datatables Excel Buttons JS -->

    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/datatables/pdfmake.min.js')); ?>"></script><!-- Datatables Make PDF Buttons JS -->

    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/datatables/vfs_fonts.js')); ?>"></script><!-- Datatables Export PDF Buttons JS -->

    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/datatables/buttons.html5.min.js')); ?>"></script><!-- Datatables Buttons HTML5 JS -->
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'front/js/jquery.number.min.js')); ?>"></script>

    <script>
        var are_you_sure = "<?php echo e(trans('messages.are_you_sure')); ?>";
        var yes = "<?php echo e(trans('messages.yes')); ?>";
        var no = "<?php echo e(trans('messages.no')); ?>";
        var wrong = "<?php echo e(trans('messages.wrong')); ?>";
        var time_format = "<?php echo e(helper::appdata($vendor_id)->time_format); ?>";
        toastr.options = {
            "closeButton": true,
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
            var bookingnotificationurl = "<?php echo e(URL::to('/admin/getbooking')); ?>";
            var ordernotificationurl = "<?php echo e(URL::to('/admin/getorder')); ?>";
            var vendoraudio =
                "<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/notification/' . helper::appdata(Auth::user()->id)->notification_sound)); ?>";
        <?php endif; ?>

        function currency_formate(price) {
            var price = price * <?php echo e(@helper::currencyinfo($vendor_id)->exchange_rate); ?>;
            if ("<?php echo e(@helper::currencyinfo($vendor_id)->currency_position); ?>" == "1") {

                if ("<?php echo e(helper::currencyinfo($vendor_id)->decimal_separator); ?>" == 1) {
                    var oldprice = $.number(price, "<?php echo e(helper::currencyinfo($vendor_id)->currency_formate); ?>");
                    if ("<?php echo e(@helper::currencyinfo($vendor_id)->currency_space); ?>" == 1) {
                        newprice = "<?php echo e(@helper::currencyinfo($vendor_id)->currency); ?>" + ' ' + oldprice;
                    } else {
                        newprice = "<?php echo e(@helper::currencyinfo($vendor_id)->currency); ?>" + oldprice;
                    }

                } else {
                    var oldprice = $.number(price, "<?php echo e(helper::currencyinfo($vendor_id)->currency_formate); ?>", ',', '.');
                    if ("<?php echo e(@helper::currencyinfo($vendor_id)->currency_space); ?>" == 1) {
                        newprice = "<?php echo e(@helper::currencyinfo($vendor_id)->currency); ?>" + ' ' + oldprice;
                    } else {

                        newprice = "<?php echo e(@helper::currencyinfo($vendor_id)->currency); ?>" + oldprice;
                    }
                }
                return newprice;
            } else {
                if ("<?php echo e(helper::currencyinfo($vendor_id)->decimal_separator); ?>" == 1) {
                    var oldprice = $.number(price, "<?php echo e(helper::currencyinfo($vendor_id)->currency_formate); ?>");
                    if ("<?php echo e(@helper::currencyinfo($vendor_id)->currency_space); ?>" == 1) {
                        newprice = oldprice + ' ' + "<?php echo e(@helper::currencyinfo($vendor_id)->currency); ?>";
                    } else {
                        newprice = oldprice + "<?php echo e(@helper::currencyinfo($vendor_id)->currency); ?>";
                    }

                } else {
                    var oldprice = $.number(price, "<?php echo e(helper::currencyinfo($vendor_id)->currency_formate); ?>", ',', '.');
                    if ("<?php echo e(@helper::currencyinfo($vendor_id)->currency_space); ?>" == 1) {
                        newprice = oldprice + ' ' + "<?php echo e(@helper::currencyinfo($vendor_id)->currency); ?>";
                    } else {
                        newprice = oldprice + "<?php echo e(@helper::currencyinfo($vendor_id)->currency); ?>";
                    }
                }
                return newprice;
            }
        }
    </script>
    <?php if(@helper::checkaddons('notification')): ?>
        <?php if(Auth::user()->type == 2): ?>
            <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/sound.js')); ?>"></script>
        <?php endif; ?>
    <?php endif; ?>
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/jquery/jquery-ui.min.js')); ?>"></script>
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/common.js')); ?>"></script><!-- Common JS -->
    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/calendar/moment.min.js')); ?>"></script>
    <?php echo $__env->yieldContent('scripts'); ?>

</body>

</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/envato_bookingdo/BookingDo_Addon_v4.3/resources/views/admin/layout/default.blade.php ENDPATH**/ ?>