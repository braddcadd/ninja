<?php echo trans('texts.email_salutation', ['name' => $userName]); ?>


<?php echo trans("texts.notification_{$entityType}_sent", ['amount' => $invoiceAmount, 'client' => $clientName, 'invoice' => $invoiceNumber]); ?>


<?php echo trans('texts.email_signature'); ?>

<?php echo trans('texts.email_from'); ?>


<?php echo trans('texts.user_email_footer', ['link' => URL::to('/settings/notifications')]); ?>

