<?php if(Auth::user()->hasFeature(FEATURE_INVOICE_SETTINGS)): ?>
    <?php if($customLabel = $account->customLabel($entityType . '1')): ?>
        <?php echo $__env->make('partials.custom_field', [
            'field' => 'custom_value1',
            'label' => $customLabel
        ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>
    <?php if($customLabel = $account->customLabel($entityType . '2')): ?>
        <?php echo $__env->make('partials.custom_field', [
            'field' => 'custom_value2',
            'label' => $customLabel
        ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>
<?php endif; ?>
