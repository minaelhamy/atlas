<!DOCTYPE html>

<html lang="en" dir="<?php echo e(session()->get('direction') == 2 ? 'rtl' : 'ltr'); ?>">



<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width,initial-scale=1">

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />

    <meta property="og:title" content="<?php echo e(helper::appdata('')->meta_title); ?>" />

    <meta property="og:description" content="<?php echo e(helper::appdata('')->meta_description); ?>" />

    <meta property="og:image" content='<?php echo e(helper::image_path(helper::appdata('')->og_image)); ?>' />

    <title><?php echo e(helper::appdata('')->web_title); ?></title>

    <link rel="icon" type="image" sizes="16x16" href="<?php echo e(helper::image_path(helper::appdata('')->favicon)); ?>">

    <!-- Favicon icon -->

    <link rel="stylesheet" href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/bootstrap/bootstrap.min.css')); ?>">

    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/fontawesome/all.min.css')); ?>">

    <!-- FontAwesome CSS -->

    <link rel="stylesheet" href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/toastr/toastr.min.css')); ?>">

    <!-- FontAwesome CSS -->

    <link rel="stylesheet" href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/style.css')); ?>"><!-- Custom CSS -->

    <link rel="stylesheet" href="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/css/responsive.css')); ?>">
    <style>
        :root {
            /* Color */
            --bs-primary: <?php echo e(helper::appdata('')->primary_color); ?>;
            --bs-secondary: <?php echo e(helper::appdata('')->secondary_color); ?>;
        }
    </style>
    <!-- Responsive CSS -->
    <!-- IF VERSION 2  -->
    <?php if(helper::appdata('')->recaptcha_version == 'v2'): ?>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    <?php endif; ?>
    <!-- IF VERSION 3  -->
    <?php if(helper::appdata('')->recaptcha_version == 'v3'): ?>
        <?php echo RecaptchaV3::initJs(); ?>

    <?php endif; ?>
</head>



<body class="light">
    <main>

        <?php echo $__env->yieldContent('content'); ?>

    </main>

    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/jquery/jquery.min.js')); ?>"></script><!-- jQuery JS -->

    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/bootstrap/bootstrap.bundle.min.js')); ?>"></script><!-- Bootstrap JS -->

    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/toastr/toastr.min.js')); ?>"></script><!-- Toastr JS -->





    <script>
        
        toastr.options = {

            "closeButton": true,

        }

        <?php if(Session::has('success')): ?>

            toastr.success("<?php echo e(session('success')); ?>", "Success");
        <?php endif; ?>

        <?php if(Session::has('error')): ?>

            toastr.error("<?php echo e(session('error')); ?>", "Error");
        <?php endif; ?>

        function myFunction() {
            "use strict";
            toastr.error("This operation was not performed due to demo mode");
            return false;
        }
    </script>

    <script src="<?php echo e(url(env('ASSETPATHURL') . 'admin-assets/js/auth_default.js')); ?>"></script>

    <?php echo $__env->yieldContent('scripts'); ?>

</body>



</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/envato_bookingdo/BookingDo_Addon_v4.3/resources/views/admin/layout/auth_default.blade.php ENDPATH**/ ?>