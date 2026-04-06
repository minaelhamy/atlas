<?php $__env->startSection('content'); ?>
    <?php
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
    ?>
    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
        <h5 class="text-capitalize fw-600 color-changer text-dark fs-4"><?php echo e(trans('labels.users')); ?></h5>

        <div class="d-flex flex-wrap gap-2">
            <?php if(@helper::checkaddons('vendor_import')): ?>
                <a href="<?php echo e(URL::to('admin/users/import')); ?>" class="btn btn-secondary px-sm-4 d-flex">
                    <i class="fa-solid fa-file-import mx-1"></i><?php echo e(trans('labels.import')); ?></a>

                <?php if($getuserslist->count() > 0): ?>
                    <a href="<?php echo e(URL::to('admin/users/exportvendor')); ?>" class="btn btn-secondary px-sm-4 d-flex">
                        <i class="fa-solid fa-file-export mx-1"></i><?php echo e(trans('labels.export')); ?></a>
                <?php endif; ?>
            <?php endif; ?>

            <a href="<?php echo e(URL::to('admin/users/add')); ?>"
                class="btn btn-secondary px-sm-4 d-flex <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_vendors', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : ''); ?>">
                <i class="fa-regular fa-plus mx-1"></i><?php echo e(trans('labels.add')); ?></a>
        </div>
    </div>
    
    <div class="row row-cols-xxl-5 row-cols-xl-4 row-cols-lg-2 row-cols-md-2 row-cols-sm-1 row-cols-1 g-3 pb-3">
        <?php $__currentLoopData = $getuserslist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col">
                <div class="vendor_card card border bg-change rounded-4 h-100 box-shadow">
                    <div class="card-body p-3">
                        <div class="d-flex gap-3 mb-3 align-items-center border-bottom pb-2">
                            <div class="col-auto">
                                <img src="<?php echo e(helper::image_path($user->image)); ?>" class="img-fluid rounded hw-50"
                                    alt="" srcset="">
                            </div>
                            <div>
                                <p class="fs-6 fw-600 mt-1 color-changer">
                                    <?php echo e($user->name); ?>

                                </p>
                                <p class="fs-7 mt-1 color-changer">
                                    <?php echo e($user->mobile); ?>

                                </p>
                            </div>
                        </div>
                        <p class="fs-7 mt-1 color-changer">
                            <?php echo e(trans('labels.status')); ?> :
                            <?php if($user->is_available == 1): ?>
                                <a class="fw-500 text-success <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_vendors', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : ''); ?>"
                                    <?php if(env('Environment') == 'sendbox'): ?> onclick="myFunction()" <?php else: ?> onclick="statusupdate('<?php echo e(URL::to('admin/users/status-' . $user->id . '/2')); ?>')" <?php endif; ?>>
                                    <i class="fa-sharp fa-solid fa-check"></i> <?php echo e(trans('labels.active')); ?>

                                </a>
                            <?php else: ?>
                                <a class="fw-500 text-danger <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_vendors', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : ''); ?>"
                                    <?php if(env('Environment') == 'sendbox'): ?> onclick="myFunction()" <?php else: ?> onclick="statusupdate('<?php echo e(URL::to('admin/users/status-' . $user->id . '/1')); ?>')" <?php endif; ?>>
                                    <i class="fa-sharp fa-solid fa-xmark"></i> <?php echo e(trans('labels.inactive')); ?>

                                </a>
                            <?php endif; ?>
                        </p>
                        <p class="fs-7 mt-1 color-changer">
                            <?php echo e(trans('labels.login_type')); ?> :
                            <?php if($user->login_type == 'normal'): ?>
                                <?php echo e(trans('labels.normal')); ?>

                            <?php elseif($user->login_type == 'google'): ?>
                                <?php echo e(trans('labels.google')); ?>

                            <?php elseif($user->login_type == 'facebook'): ?>
                                <?php echo e(trans('labels.facebook')); ?>

                            <?php endif; ?>
                        </p>
                        <p class="fs-7 mt-1 color-changer">
                            <?php echo e(trans('labels.email')); ?> :
                            <?php echo e($user->email); ?>

                        </p>
                        <div class="d-flex flex-wrap justify-content-center mt-3 gap-2">
                            <a class="btn btn-sm btn-info hov <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_vendors', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : ''); ?>"
                                tooltip="<?php echo e(trans('labels.edit')); ?>"
                                href="<?php echo e(URL::to('admin/users/edit-' . $user->id)); ?>">
                                <i class="fa fa-pen-to-square"></i>
                            </a>
                            <a class="btn btn-sm btn-dark hov" tooltip="<?php echo e(trans('labels.login')); ?>"
                                href="<?php echo e(URL::to('admin/users/login-' . $user->id)); ?>">
                                <i class="fa-regular fa-arrow-right-to-bracket"></i>
                            </a>
                            <a class="btn btn-sm btn-secondary hov" tooltip="<?php echo e(trans('labels.view')); ?>"
                                href="<?php if($user->custom_domain == null): ?> <?php echo e(URL::to('/' . $user->slug)); ?> <?php else: ?> https://<?php echo e($user->custom_domain); ?> <?php endif; ?>"
                                target="_blank"><i class="fa-regular fa-eye"></i>
                            </a>
                            <button type="button" id="btn_password<?php echo e($user->id); ?>"
                                tooltip="<?php echo e(trans('labels.reset_password')); ?>" onclick="myfunction(<?php echo e($user->id); ?>)"
                                class="btn btn-sm btn-success hov <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_vendors', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : ''); ?>"
                                data-vendor_id="<?php echo e($user->id); ?>" data-type="1">
                                <i class="fa-light fa-key"></i>
                            </button>
                            <a href="javascript:void(0)" tooltip="<?php echo e(trans('labels.delete')); ?>"
                                <?php if(env('Environment') == 'sendbox'): ?> onclick="myFunction()" <?php else: ?> onclick="statusupdate('<?php echo e(URL::to('admin/users/delete-' . $user->id)); ?>')" <?php endif; ?>
                                class="btn btn-danger hov btn-sm <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_vendors', Auth::user()->role_id, $vendor_id, 'delete') == 1 ? '' : 'd-none') : ''); ?>">
                                <i class="fa-regular fa-trash"></i>
                            </a>
                            <?php if(@helper::checkaddons('store_clone')): ?>
                                <a href="<?php echo e(URL::to('admin/users/add-' . $user->id)); ?>"
                                    tooltip="<?php echo e(trans('labels.clone')); ?>"
                                    class="btn btn-warning hov btn-sm <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_vendors', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : ''); ?>">
                                    <i class="fa-regular fa-clone"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="changepasswordModal" tabindex="-1" aria-labelledby="changepasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="<?php echo e(URL::to('/admin/settings/change-password')); ?>" method="post" class="w-100">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header justify-content-between">
                        <h5 class="modal-title" id="changepasswordModalLabel">
                            <?php echo e(trans('labels.change_password')); ?>

                        </h5>
                        <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="card p-1 border-0">
                            <input type="hidden" class="form-control" name="modal_vendor_id" id="modal_vendor_id"
                                value="">
                            <input type="hidden" class="form-control" name="type" id="type" value="1">
                            <div class="form-group">
                                <label for="new_password" class="form-label"><?php echo e(trans('labels.new_password')); ?></label>
                                <input type="password" class="form-control" name="new_password" required
                                    placeholder="<?php echo e(trans('labels.new_password')); ?>">

                            </div>
                            <div class="form-group">
                                <label for="confirm_password"
                                    class="form-label"><?php echo e(trans('labels.confirm_password')); ?></label>
                                <input type="password" class="form-control" name="confirm_password" required
                                    placeholder="<?php echo e(trans('labels.confirm_password')); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-secondary px-sm-4"><?php echo e(trans('labels.save')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script>
        function myfunction(id) {
            $('#modal_vendor_id').val($('#btn_password' + id).attr("data-vendor_id"));
            $('#changepasswordModal').modal('show');
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/envato_storemart/storemart_4.5/Storemart_Addon/resources/views/admin/user/index.blade.php ENDPATH**/ ?>