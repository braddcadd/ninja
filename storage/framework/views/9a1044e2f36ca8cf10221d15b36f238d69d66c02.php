<?php echo e(trans('texts.reset_password')); ?>


<?php echo URL::to(SITE_URL . "/password/reset/{$token}"); ?>


<?php echo e(trans('texts.email_signature')); ?>

<?php echo e(trans('texts.email_from')); ?>


<?php echo e(trans('texts.reset_password_footer', ['email' => env('CONTACT_EMAIL', CONTACT_EMAIL)])); ?>

