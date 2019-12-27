<?php echo trans('texts.confirmation_header'); ?>


<?php echo $invitationMessage . trans('texts.confirmation_message'); ?>

<?php echo URL::to("user/confirm/{$user->confirmation_code}"); ?>


<?php echo trans('texts.email_signature'); ?>

<?php echo trans('texts.email_from'); ?>

