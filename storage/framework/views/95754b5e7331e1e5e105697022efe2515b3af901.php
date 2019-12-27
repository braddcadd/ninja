<?php echo strip_tags(str_replace('</div>', "\n\n", $body)); ?>


<?php if(! $account->isPaid()): ?>
    <?php echo e(trans('texts.ninja_email_footer', ['site' => NINJA_WEB_URL . '?utm_source=email_footer'])); ?>

<?php endif; ?>
