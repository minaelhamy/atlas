<!-- For Large Devices -->
<nav class="sidebar sidebar-lg">
    <div class="d-flex justify-content-center align-items-center mb-3 border-bottom border-white">
        <div class="navbar-header-logoc pb-2">
            <?php if(Auth::user()->type == 1): ?>
                <a href="" class="text-white fs-4"><?php echo e(trans('labels.admin_title')); ?></a>
            <?php elseif(Auth::user()->type == 2 || Auth::user()->type == 4): ?>
                <a href="" class="text-white fs-4"><?php echo e(trans('labels.vendor_title')); ?></a>
            <?php endif; ?>
        </div>
    </div>
    <?php echo $__env->make('admin.layout.sidebarcommon', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</nav>
<!-- For Small Devices -->
<nav class="collapse collapse-horizontal sidebar sidebar-md" id="sidebarcollapse">
    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-white">
        <div class="pb-2">
            <?php if(Auth::user()->type == 1): ?>
                <a href="" class="text-white fs-4"><?php echo e(trans('labels.admin_title')); ?></a>
            <?php elseif(Auth::user()->type == 2 || Auth::user()->type == 4): ?>
                <a href="" class="text-white fs-4"><?php echo e(trans('labels.vendor_title')); ?></a>
            <?php endif; ?>
        </div>
        <button class="btn text-white" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarcollapse"
            aria-expanded="false" aria-controls="sidebarcollapse"><i class="fa-light fa-xmark"></i></button>
    </div>
    <?php echo $__env->make('admin.layout.sidebarcommon', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</nav>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/envato_storemart/storemart_4.5/Storemart_Addon/resources/views/admin/layout/sidebar.blade.php ENDPATH**/ ?>