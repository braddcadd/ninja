<!DOCTYPE html>
<html lang="<?php echo e(App::getLocale()); ?>">
<head>
  <meta charset="utf-8">
</head>
<body>
    <?php if($account->emailMarkupEnabled()): ?>
        <?php echo $__env->make('emails.partials.client_view_action', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>
    <?php echo $body; ?>

    <?php if(! $account->isPaid()): ?>
        <br/>
        <?php echo trans('texts.ninja_email_footer', ['site' => link_to(NINJA_WEB_URL . '?utm_source=email_footer', APP_NAME)]); ?>

    <?php endif; ?>
</body>
</html>
