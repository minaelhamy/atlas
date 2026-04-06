<table class="table table-striped table-bordered py-3 zero-configuration w-100">
    <thead>
        <tr class="text-capitalize fw-500 fs-15">
            <td><?php echo e(trans('labels.srno')); ?></td>
            <td><?php echo e(trans('labels.transaction_number')); ?></td>
            <td><?php echo e(trans('labels.plan_name')); ?></td>
            <td><?php echo e(trans('labels.total')); ?> <?php echo e(trans('labels.amount')); ?></td>
            <td><?php echo e(trans('labels.payment_type')); ?></td>
            <td><?php echo e(trans('labels.purchase_date')); ?></td>
            <td><?php echo e(trans('labels.expire_date')); ?></td>
            <td><?php echo e(trans('labels.status')); ?></td>
            <td><?php echo e(trans('labels.created_date')); ?></td>
            <td><?php echo e(trans('labels.updated_date')); ?></td>
            <td><?php echo e(trans('labels.action')); ?></td>
        </tr>
    </thead>
    <tbody>
        <?php
            if (Auth::user()->type == 4) {
                $vendor_id = Auth::user()->vendor_id;
            } else {
                $vendor_id = Auth::user()->id;
            }
            $i = 1;

        ?>
        <?php $__currentLoopData = $transaction; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="fs-7 align-middle">
                <td><?php echo $i++; ?></td>
                <td><?php echo e($transaction->transaction_number); ?></td>
                <td><?php echo e($transaction->plan_name); ?></td>
                <td><?php echo e(helper::currency_formate($transaction->grand_total, '')); ?></td>
                <td>
                    <?php if($transaction->payment_type != ''): ?>
                        <?php if($transaction->payment_type == 0): ?>
                            <?php echo e(trans('labels.manual')); ?>

                        <?php else: ?>
                            <?php echo e(@helper::getpayment($transaction->payment_type, 1)->payment_name); ?>

                        <?php endif; ?>
                    <?php elseif($transaction->amount == 0): ?>
                        -
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td>

                    <?php if($transaction->payment_type == 6 || $transaction->payment_type == 1): ?>
                        <?php if($transaction->status == 2): ?>
                            <span
                                class="badge bg-success"><?php echo e(helper::date_format($transaction->purchase_date, $vendor_id)); ?></span>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    <?php else: ?>
                        <span
                            class="badge bg-success"><?php echo e(helper::date_format($transaction->purchase_date, $vendor_id)); ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($transaction->payment_type == 6 || $transaction->payment_type == 1): ?>
                        <?php if($transaction->status == 2): ?>
                            <span
                                class="badge bg-danger"><?php echo e($transaction->expire_date != '' ? helper::date_format($transaction->expire_date, $vendor_id) : '-'); ?></span>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    <?php else: ?>
                        <span
                            class="badge bg-danger"><?php echo e($transaction->expire_date != '' ? helper::date_format($transaction->expire_date, $vendor_id) : '-'); ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($transaction->payment_type == 6 || $transaction->payment_type == 1): ?>
                        <?php if($transaction->status == 1): ?>
                            <span class="badge bg-warning"><?php echo e(trans('labels.pending')); ?></span>
                        <?php elseif($transaction->status == 2): ?>
                            <span class="badge bg-success"><?php echo e(trans('labels.accepted')); ?></span>
                        <?php elseif($transaction->status == 3): ?>
                            <span class="badge bg-danger"><?php echo e(trans('labels.rejected')); ?></span>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td><?php echo e(helper::date_format($transaction->created_at, $vendor_id)); ?><br>
                    <?php echo e(helper::time_format($transaction->created_at, $vendor_id)); ?>

                </td>
                <td><?php echo e(helper::date_format($transaction->updated_at, $vendor_id)); ?><br>
                    <?php echo e(helper::time_format($transaction->updated_at, $vendor_id)); ?>

                </td>
                <td>
                    <div>
                        <a class="btn btn-sm btn-secondary hov" tooltip="<?php echo e(trans('labels.view')); ?>"
                            href="<?php echo e(URL::to('admin/transaction/plandetails-' . $transaction->id)); ?>"><i
                                class="fa-regular fa-eye"></i></a>
                    </div>
                </td>

            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/envato_storemart/storemart_4.5/Storemart_Addon/resources/views/admin/dashboard/admintransaction.blade.php ENDPATH**/ ?>