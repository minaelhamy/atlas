<?php $__env->startSection('content'); ?>
    <?php
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $module = 'role_how_it_works';
    ?>

    <?php echo $__env->make('admin.breadcrumb.breadcrumb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row mt-3">

        <div class="col-12">

            <div class="card border-0 mb-3 box-shadow">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-striped table-bordered py-3 zero-configuration w-100 dataTable no-footer">

                            <thead>

                                <tr class="text-capitalize fw-500 fs-15">

                                    <td></td>

                                    <?php if(@helper::checkaddons('bulk_delete')): ?>
                                        <?php if($datas->count() > 0): ?>
                                            <td> <input type="checkbox" id="selectAll" class="form-check-input checkbox-style"></td>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <td><?php echo e(trans('labels.srno')); ?></td>

                                    <td><?php echo e(trans('labels.image')); ?></td>

                                    <td><?php echo e(trans('labels.title')); ?></td>

                                    <td><?php echo e(trans('labels.description')); ?></td>

                                    <td><?php echo e(trans('labels.created_date')); ?></td>

                                    <td><?php echo e(trans('labels.updated_date')); ?></td>

                                    <td><?php echo e(trans('labels.action')); ?></td>

                                </tr>

                            </thead>

                            <tbody id="tabledetails" data-url="<?php echo e(url('admin/how_it_works/reorder_how_work')); ?>">

                                <?php

                                    $i = 1;

                                ?>

                                <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="fs-7 row1 align-middle" id="dataid<?php echo e($data->id); ?>"
                                        data-id="<?php echo e($data->id); ?>">

                                        <td><a tooltip="<?php echo e(trans('labels.move')); ?>"><i
                                                    class="fa-light fa-up-down-left-right mx-2"></i></a></td>

                                        <?php if(@helper::checkaddons('bulk_delete')): ?>
                                            <td><input type="checkbox" class="row-checkbox form-check-input checkbox-style" value="<?php echo e($data->id); ?>"></td>
                                        <?php endif; ?>
                                        
                                        <td>

                                            <?php

                                                echo $i++;

                                            ?></td>

                                        <td> <img src="<?php echo e(helper::image_path($data->image)); ?>"
                                                class="img-fluid rounded hight-50 object-fit-cover" alt=""></td>

                                        <td><?php echo e($data->title); ?></td>

                                        <td><?php echo e($data->description); ?></td>

                                        <td><?php echo e(helper::date_formate($data->created_at, $vendor_id)); ?><br>
                                            <?php echo e(helper::time_format($data->created_at, $vendor_id)); ?>

                                        </td>

                                        <td><?php echo e(helper::date_formate($data->updated_at, $vendor_id)); ?><br>
                                            <?php echo e(helper::time_format($data->updated_at, $vendor_id)); ?>

                                        </td>

                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="<?php echo e(URL::to('/admin/how_it_works/edit-' . $data->id)); ?>"
                                                    class="btn btn-info btn-sm hov <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_how_it_works', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : ''); ?>"
                                                    tooltip="<?php echo e(trans('labels.edit')); ?>">
                                                    <i class="fa-regular fa-pen-to-square"></i></a>

                                                <a href="javascript:void(0)"
                                                    <?php if(env('Environment') == 'sendbox'): ?> onclick="myFunction()" <?php else: ?> onclick="statusupdate('<?php echo e(URL::to('admin/how_it_works/delete-' . $data->id)); ?>')" <?php endif; ?>
                                                    class="btn btn-danger btn-sm hov <?php echo e(Auth::user()->type == 4 ? (helper::check_access('role_how_it_works', Auth::user()->role_id, $vendor_id, 'delete') == 1 ? '' : 'd-none') : ''); ?>"
                                                    tooltip="<?php echo e(trans('labels.delete')); ?>">
                                                    <i class="fa-regular fa-trash"></i></a>
                                            </div>

                                        </td>

                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/envato_bookingdo/BookingDo_Addon_v4.3/resources/views/admin/howwork/index.blade.php ENDPATH**/ ?>