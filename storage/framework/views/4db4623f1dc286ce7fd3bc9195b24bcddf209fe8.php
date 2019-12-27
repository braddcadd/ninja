<?php $__env->startSection('content'); ?>
    <tr>
        <td bgcolor="#F4F5F5" style="border-collapse: collapse;">&nbsp;</td>
    </tr>
    <tr>
        <td style="border-collapse: collapse;">
            <table cellpadding="10" cellspacing="0" border="0" bgcolor="#2F2C2B" width="600" align="center" class="header">
                <tr>
                    <td class="logo" style="border-collapse: collapse; vertical-align: middle; padding-left:34px; padding-top:20px; padding-bottom:12px" valign="middle">
                        <img src="<?php echo e(isset($message) ? $message->embed(public_path('images/invoiceninja-logo.png')) : 'cid:invoiceninja-logo.png'); ?>" alt="Invoice Ninja" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="content" style="border-collapse: collapse;">
            <div style="font-size: 18px; margin: 42px 40px 42px; padding: 0;">
                <?php echo $__env->yieldContent('body'); ?>
            </div>
        </td>
    </tr>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
    <p style="color: #A7A6A6; font-size: 13px; line-height: 18px; margin: 0 0 7px; padding: 0;">
        <a href="<?php echo e(SOCIAL_LINK_FACEBOOK); ?>" style="color: #A7A6A6; text-decoration: none; font-weight: bold; font-size: 10px;"><img src="<?php echo e(isset($message) ? $message->embed(public_path('images/emails/icon-facebook.png')) : 'cid:icon-facebook.png'); ?>" alt="Facebook" /></a>
        <a href="<?php echo e(SOCIAL_LINK_TWITTER); ?>" style="color: #A7A6A6; text-decoration: none; font-weight: bold; font-size: 10px;"><img src="<?php echo e(isset($message) ? $message->embed(public_path('images/emails/icon-twitter.png')) : 'cid:icon-twitter.png'); ?>" alt="Twitter" /></a>
        <a href="<?php echo e(SOCIAL_LINK_GITHUB); ?>" style="color: #A7A6A6; text-decoration: none; font-weight: bold; font-size: 10px;"><img src="<?php echo e(isset($message) ? $message->embed(public_path('images/emails/icon-github.png')) : 'cid:icon-github.png'); ?>" alt="GitHub" /></a>
    </p>

    <p style="color: #A7A6A6; font-size: 13px; line-height: 18px; margin: 0 0 7px; padding: 0;">
        &#9400; <?php echo e(date('Y')); ?> Invoice Ninja<br />
        <strong><a href="<?php echo e(URL::to('/settings/notifications')); ?>" style="color: #A7A6A6; text-decoration: none; font-weight: bold; font-size: 10px;"><?php echo e(strtoupper(trans('texts.email_preferences'))); ?></a></strong>
    </p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>