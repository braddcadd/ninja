<?php $__env->startSection('markup'); ?>
    <?php if($account->emailMarkupEnabled()): ?>
        <?php echo $__env->make('emails.partials.user_view_action', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <div>
        <?php echo e(trans('texts.email_salutation', ['name' => $userName])); ?>

    </div>
    &nbsp;
    <div>
        <?php echo e(trans("texts.notification_{$entityType}_sent", ['amount' => $invoiceAmount, 'client' => $clientName, 'invoice' => $invoiceNumber])); ?>

    </div>
    &nbsp;
    <div>
        <center>
            <?php echo $__env->make('partials.email_button', [
                'link' => $invoiceLink,
                'field' => "view_{$entityType}",
                'color' => '#0b4d78',
            ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </center>
    </div>
    &nbsp;
    <div>
        <?php echo e(trans('texts.email_signature')); ?> <br/>
        <?php echo e(trans('texts.email_from')); ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('emails.master_user', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>