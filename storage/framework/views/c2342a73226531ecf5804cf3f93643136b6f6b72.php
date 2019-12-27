<?php $__env->startSection('head'); ?>
    ##parent-placeholder-1a954628a960aaef81d7b2d4521929579f3541e6##

    <style type="text/css">
        .import-file {
            display: none;
        }
    </style>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

    <?php echo $__env->make('accounts.nav', ['selected' => ACCOUNT_IMPORT_EXPORT], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo trans('texts.import_data'); ?></h3>
  </div>
    <div class="panel-body">

        <?php echo Former::open_for_files('/import')
                ->onsubmit('return onFormSubmit(event)')
                ->addClass('warn-on-exit'); ?>


        <?php echo Former::select('source')
                ->onchange('setFileTypesVisible()')
                ->options(array_combine(\App\Services\ImportService::$sources, \App\Services\ImportService::$sources))
                ->style('width: 200px'); ?>


        <br/>
        <?php $__currentLoopData = \App\Services\ImportService::$entityTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entityType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo Former::file($entityType)
                    ->addGroupClass("import-file {$entityType}-file")
                    ->label(Utils::pluralizeEntityType($entityType)); ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <div id="jsonIncludes" style="display:none">
            <?php echo Former::checkboxes('json_include_radio')
                    ->label(trans('texts.include'))
                    ->checkboxes([
                        trans('texts.settings') => 'settings',
                        trans('texts.data') => 'data',
                    ]); ?>

        </div>

        <div id="notInovicePlaneImport">
            <?php echo Former::plaintext(' ')->help(trans('texts.use_english_version')); ?>

        </div>
        <div id="inovicePlaneImport" style="display:none">
            <?php echo Former::plaintext(' ')->help(trans('texts.invoiceplane_import', ['link' => link_to(INVOICEPLANE_IMPORT, 'turbo124/Plane2Ninja', ['target' => '_blank'])])); ?>

        </div>

        <br/>

        <?php echo Former::actions( Button::info(trans('texts.upload'))->withAttributes(['id' => 'uploadButton'])->submit()->large()->appendIcon(Icon::create('open'))); ?>

        <?php echo Former::close(); ?>


    </div>
</div>


<?php echo Former::open('/export'); ?>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo trans('texts.export_data'); ?></h3>
  </div>
    <div class="panel-body">
        <?php echo Former::select('format')
                ->onchange('setCheckboxesEnabled()')
                ->addOption('CSV', 'CSV')
                ->addOption('XLS', 'XLS')
                ->addOption('JSON', 'JSON')
                ->style('max-width: 200px')
                ->help('<br/>' . trans('texts.export_help') . (Utils::isSelfHost() ? '<b>' . trans('texts.selfhost_export_help') . '</b>' : '')); ?>



        <div id="csvIncludes">
            <?php echo Former::inline_radios('include_radio')
                    ->onchange('setCheckboxesEnabled()')
                    ->label(trans('texts.include'))
                    ->radios([
                        trans('texts.all') . ' &nbsp; ' => ['value' => 'all', 'name' => 'include'],
                        trans('texts.selected') => ['value' => 'selected', 'name' => 'include'],
                    ])->check('all'); ?>



            <div class="form-group entity-types">
                <label class="control-label col-lg-4 col-sm-4"></label>
                <div class="col-lg-2 col-sm-2">
                    <?php echo $__env->make('partials/checkbox', ['field' => 'clients'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php echo $__env->make('partials/checkbox', ['field' => 'contacts'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php echo $__env->make('partials/checkbox', ['field' => 'credits'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php echo $__env->make('partials/checkbox', ['field' => 'tasks'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div>
                <div class="col-lg-2 col-sm-2">
                    <?php echo $__env->make('partials/checkbox', ['field' => 'invoices'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php echo $__env->make('partials/checkbox', ['field' => 'quotes'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php echo $__env->make('partials/checkbox', ['field' => 'recurring'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php echo $__env->make('partials/checkbox', ['field' => 'payments'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div>
                <div class="col-lg-3 col-sm-3">
                    <?php echo $__env->make('partials/checkbox', ['field' => 'products'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php echo $__env->make('partials/checkbox', ['field' => 'expenses'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php echo $__env->make('partials/checkbox', ['field' => 'vendors'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php echo $__env->make('partials/checkbox', ['field' => 'vendor_contacts'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div>
            </div>
        </div><br/>

        <?php echo Former::actions( Button::primary(trans('texts.download'))->submit()->large()->appendIcon(Icon::create('download-alt'))); ?>

    </div>
</div>
<?php echo Former::close(); ?>



<script type="text/javascript">
  $(function() {
      setFileTypesVisible();
      setCheckboxesEnabled();
  });

  function onFormSubmit() {
      $('#uploadButton').attr('disabled', true);
      return true;
  }

  function setCheckboxesEnabled() {
      var $checkboxes = $('.entity-types input[type=checkbox]');
      var include = $('input[name=include]:checked').val()
      var format = $('#format').val();
      if (include === 'all') {
          $checkboxes.attr('disabled', true);
      } else {
          $checkboxes.removeAttr('disabled');
      }
      if (format === 'JSON') {
          $('#csvIncludes').hide();
      } else {
          $('#csvIncludes').show();
      }
  }

  function setFileTypesVisible() {
    var val = $('#source').val();
    if (val === 'JSON') {
        $('#jsonIncludes').show();
    } else {
        $('#jsonIncludes').hide();
    }
    <?php $__currentLoopData = \App\Services\ImportService::$entityTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entityType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        $('.<?php echo e($entityType); ?>-file').hide();
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php $__currentLoopData = \App\Services\ImportService::$sources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        if (val === '<?php echo e($source); ?>') {
            <?php $__currentLoopData = \App\Services\ImportService::$entityTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entityType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($source != IMPORT_WAVE && $entityType == ENTITY_PAYMENT): ?>
                    // do nothing
                <?php elseif(class_exists(\App\Services\ImportService::getTransformerClassName($source, $entityType))): ?>
                    $('.<?php echo e($entityType); ?>-file').show();
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        }
        <?php if($source === IMPORT_JSON): ?>
            if (val === '<?php echo e($source); ?>') {
                $('.JSON-file').show();
            }
        <?php endif; ?>
        if (val === '<?php echo e(IMPORT_JSON); ?>') {
            $('#uploadButton').show();
            $('#inovicePlaneImport').hide();
            $('#notInovicePlaneImport').hide();
        } else if (val === '<?php echo e(IMPORT_INVOICEPLANE); ?>') {
            $('#uploadButton').hide();
            $('#inovicePlaneImport').show();
            $('#notInovicePlaneImport').hide();
        } else {
            $('#uploadButton').show();
            $('#inovicePlaneImport').hide();
            $('#notInovicePlaneImport').show();
        }
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  }

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>