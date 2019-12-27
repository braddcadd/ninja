<?php $__env->startSection('body'); ?>
    <div>
        <?php echo e(trans('texts.reset_password')); ?>

    </div>
    &nbsp;
    <div>
        <center>
            <?php echo $__env->make('partials.email_button', [
                'link' => URL::to(SITE_URL . "/password/reset/{$token}"),
                'field' => 'reset',
                'color' => '#36c157',
            ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </center>
    </div>
    &nbsp;
    <div>
        <?php echo e(trans('texts.email_signature')); ?><br/>
        <?php echo e(trans('texts.email_from')); ?>

    </div>
    &nbsp;
    <div>
        <?php echo e(trans('texts.reset_password_footer', ['email' => env('CONTACT_EMAIL', CONTACT_EMAIL)])); ?>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.master_user', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>