<?php $__env->startSection('content'); ?>
    <tr>
        <td bgcolor="#F4F5F5" style="border-collapse: collapse;">&nbsp;</td>
    </tr>
    <tr>
        <td style="border-collapse: collapse;">
            <table cellpadding="10" cellspacing="0" border="0" bgcolor="#2F2C2B" width="600" align="center" class="header">
                <tr>
                    <td class="logo" style="border-collapse: collapse; vertical-align: middle; padding-left:34px; padding-top:20px; padding-bottom:12px" valign="middle">
                        <?php if(Utils::isNinja() || ! Utils::isWhiteLabel()): ?>
                            <img src="<?php echo e(isset($message) ? $message->embed(public_path('images/invoiceninja-logo.png')) : 'cid:invoiceninja-logo.png'); ?>" alt="Invoice Ninja" />
                        <?php endif; ?>
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

<?php echo $__env->make('emails.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>