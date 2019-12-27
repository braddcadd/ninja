<div style="display:none">
    <?php echo Former::open($entityType . 's/bulk')->addClass("bulk-form bulk-{$entityType}-form"); ?>

    <?php echo Former::text('bulk_action')->addClass('bulk-action'); ?>

    <?php echo Former::text('bulk_public_id')->addClass('bulk-public-id'); ?>

    <?php echo Former::close(); ?>

</div>

<script type="text/javascript">
    function submitForm_<?php echo e($entityType); ?>(action, id) {
        if (action == 'delete') {
            if (!confirm(<?php echo json_encode(trans("texts.are_you_sure")); ?>)) {
                return;
            }
        }

        <?php if(in_array($entityType, [ENTITY_ACCOUNT_GATEWAY])): ?>
            if (action == 'archive') {
                if (!confirm(<?php echo json_encode(trans("texts.are_you_sure")); ?>)) {
                    return;
                }
            }
        <?php endif; ?>

        $('.bulk-public-id').val(id);
        $('.bulk-action').val(action);
        $('form.bulk-<?php echo e($entityType); ?>-form').submit();
    }
</script>
