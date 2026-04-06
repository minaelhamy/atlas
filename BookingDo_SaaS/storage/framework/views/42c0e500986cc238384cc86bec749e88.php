<?php $__env->startSection('content'); ?>
<div class="mb-3 card rgb-success-light rounded">
    <div class="card-body py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div class="mb-sm-0 mb-2">
                <h5 class="card-title mb-1 fw-bold color-changer">Visit our store to purchase addons</h5>
                <p class="color-changer fw-medium">Install our addons to unlock premium features</p>
            </div>
            <a href="https://store.paponapps.co.in/products?category=bookingdo-saas" target="_blank" class="btn btn-primary fiexd-color col-sm-auto col-12">Visit Our Store</a>
        </div>
    </div>
</div>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h5 class="text-capitalize fw-600 text-dark fs-4 mb-sm-0 mb-2 color-changer"><?php echo e(trans('labels.addons_manager')); ?></h5>
    <div class="d-inline-flex col-sm-auto col-12">
        <a href="<?php echo e(URL::to('admin/createsystem-addons')); ?>" class="btn btn-secondary px-sm-4 w-100 justify-content-center d-flex">
            <i class="fa-regular fa-plus mx-1"></i><?php echo e(trans('labels.install_update_addons')); ?></a>
    </div>
</div>
<div class="search_row pb-3">
    <div class="card border-0 box-shadow h-100">
        <div class="card-body">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="installed-tab" data-bs-toggle="tab" href="#installed" role="tab" aria-controls="installed" aria-selected="true"><?php echo e(trans('labels.installed_addons')); ?> (<?php echo e(count($addons)); ?>)</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="installed" role="tabpanel" aria-labelledby="installed-tab">
                    <div class="row row-cols-xxl-5 row-cols-xl-4 row-cols-lg-2 row-cols-md-2 row-cols-sm-2 row-cols-1 g-3 pt-3">
                    <?php if(count($addons) > 0): ?>
                        <?php $__currentLoopData = $addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col d-flex">
                                <div class="card addons_card h-100 w-100 overflow-hidden">
                                    <img class="img-fluid" src='<?php echo asset('storage/app/public/addons/' . $addon->image); ?>' alt="">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <?php echo e($addon->name); ?>

                                        </h6>
                                    </div>
                                    <div class="card-footer bg-transparent d-flex flex-wrap justify-content-between aling-items-center">
                                        <p class="card-text fs-7 d-inline align-content-center"><small class="text-muted fs-7"><?php echo e(date('d M Y', strtotime($addon->created_at))); ?></small></p>
                                        <?php if($addon->activated == 1): ?>
                                            <a <?php if(env('Environment') == 'sendbox'): ?> onclick="myFunction()" <?php else: ?> onclick="statusupdate('<?php echo e(URL::to('admin/systemaddons/status-' . $addon->id . '/2')); ?>')" <?php endif; ?> class="btn btn-sm btn-primary px-sm-4 float-end <?php echo e(session()->get('direction') == 2 ? 'float-start' : 'float-end'); ?>"><?php echo e(trans('labels.activated')); ?></a>
                                        <?php else: ?>
                                            <a <?php if(env('Environment') == 'sendbox'): ?> onclick="myFunction()" <?php else: ?> onclick="statusupdate('<?php echo e(URL::to('admin/systemaddons/status-' . $addon->id . '/1')); ?>')" <?php endif; ?> class="btn px-sm-4 btn-sm btn-danger <?php echo e(session()->get('direction') == 2 ? 'float-start' : 'float-end'); ?>"><?php echo e(trans('labels.deactivated')); ?></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- End Col -->
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="col col-md-12 text-center text-muted mt-4">
                            <h4><?php echo e(trans('labels.no_addon_installed')); ?></h4>
                            <a href="https://store.paponapps.co.in/products?category=bookingdo-saas" target="_blank" class="btn btn-success mt-4">Visit Our Store</a>
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/envato_bookingdo/BookingDo_Addon_v4.3/resources/views/admin/apps/index.blade.php ENDPATH**/ ?>