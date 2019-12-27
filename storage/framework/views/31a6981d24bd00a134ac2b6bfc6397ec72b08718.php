<?php $__env->startSection('content'); ?>
	##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##
    <?php echo $__env->make('accounts.nav', ['selected' => ACCOUNT_USER_MANAGEMENT, 'advanced' => true], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <?php if(Utils::hasFeature(FEATURE_USERS)): ?>
        <?php if(Auth::user()->caddAddUsers()): ?>
            <div class="pull-right">
                <?php echo Button::primary(trans('texts.add_user'))->asLinkTo(URL::to('/users/create'))->appendIcon(Icon::create('plus-sign')); ?>

            </div>
        <?php endif; ?>
    <?php elseif(Utils::isTrial()): ?>
        <div class="alert alert-warning"><?php echo trans('texts.add_users_not_supported'); ?></div>
    <?php endif; ?>

    <label for="trashed" style="font-weight:normal; margin-left: 10px;">
        <input id="trashed" type="checkbox" onclick="setTrashVisible()"
            <?php echo Session::get('entity_state_filter:user', STATUS_ACTIVE) != 'active' ? 'checked' : ''; ?>/> <?php echo trans('texts.show_archived_users'); ?>

    </label>

  <?php echo $__env->make('partials.bulk_form', ['entityType' => ENTITY_USER], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  <?php echo Datatable::table()
      ->addColumn(
        trans('texts.name'),
        trans('texts.email'),
        trans('texts.user_state'),
        trans('texts.action'))
      ->setUrl(url('api/users/'))
      ->setOptions('sPaginationType', 'bootstrap')
      ->setOptions('bFilter', false)
      ->setOptions('bAutoWidth', false)
      ->setOptions('aoColumns', [[ "sWidth"=> "20%" ], [ "sWidth"=> "45%" ], ["sWidth"=> "20%"], ["sWidth"=> "15%" ]])
      ->setOptions('aoColumnDefs', [['bSortable'=>false, 'aTargets'=>[3]]])
      ->render('datatable'); ?>


  <script>

    window.onDatatableReady = actionListHandler;

    function setTrashVisible() {
        var checked = $('#trashed').is(':checked');
        var url = '<?php echo e(URL::to('set_entity_filter/user')); ?>' + (checked ? '/active,archived' : '/active');

        $.get(url, function(data) {
            refreshDatatable();
        })
    }

  </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>