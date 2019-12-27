<?php $__env->startSection('content'); ?> 
    ##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

    <?php echo $__env->make('accounts.nav', ['selected' => ACCOUNT_SYSTEM_SETTINGS], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <div class="row">
        <div class="col-md-12">
            <?php echo Former::open('/update_setup')
                ->addClass('warn-on-exit')
                ->autocomplete('off')
                ->rules([
                    'app[url]' => 'required',
                    //'database[default]' => 'required',
                    'database[type][host]' => 'required',
                    'database[type][database]' => 'required',
                    'database[type][username]' => 'required',
                    'database[type][password]' => 'required',
                ]); ?>



            <?php echo $__env->make('partials.system_settings', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        </div>
    </div>

    <center>
        <?php echo Button::success(trans('texts.save'))->submit()->large()->appendIcon(Icon::create('floppy-disk')); ?>

    </center>

    <?php echo Former::close(); ?>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('onReady'); ?>
    $('#app\\[url\\]').focus();
<?php $__env->stopSection(); ?>
<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>