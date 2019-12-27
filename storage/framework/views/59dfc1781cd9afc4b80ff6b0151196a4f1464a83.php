<?php $__env->startSection('content'); ?>
	##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

	<style type="text/css">

	#logo {
		padding-top: 6px;
	}

	</style>

	<?php echo Former::open_for_files()
            ->addClass('warn-on-exit')
            ->autocomplete('on')
            ->rules([
                'name' => 'required',
            ]); ?>


	<?php echo e(Former::populate($account)); ?>

	<?php echo e(Former::populateField('task_rate', floatval($account->task_rate) ? Utils::roundSignificant($account->task_rate) : '')); ?>

    <?php echo e(Former::populateField('valid_until_days', intval($account->valid_until_days) ? $account->valid_until_days : '')); ?>


    <?php echo $__env->make('accounts.nav', ['selected' => ACCOUNT_COMPANY_DETAILS], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

	<div class="row">
		<div class="col-md-12">

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><?php echo trans('texts.details'); ?></h3>
          </div>
            <div class="panel-body form-padding-right">

                <?php echo Former::text('name'); ?>

                <?php echo Former::text('id_number'); ?>

                <?php echo Former::text('vat_number'); ?>

                <?php echo Former::text('website'); ?>

				<?php if(auth()->user()->registered): ?>
                	<?php echo Former::text('work_email'); ?>

				<?php endif; ?>
                <?php echo Former::text('work_phone'); ?>

                <?php echo Former::file('logo')->max(2, 'MB')->accept('image')->inlineHelp(trans('texts.logo_help')); ?>



                <?php if($account->hasLogo()): ?>
                <div class="form-group">
                    <div class="col-lg-4 col-sm-4"></div>
                    <div class="col-lg-8 col-sm-8">
                        <a href="<?php echo e($account->getLogoUrl(true)); ?>" target="_blank">
                            <?php echo HTML::image($account->getLogoUrl(true), 'Logo', ['style' => 'max-width:300px']); ?>

                        </a> &nbsp;
                        <a href="#" onclick="deleteLogo()"><?php echo e(trans('texts.remove_logo')); ?></a>
                    </div>
                </div>
                <?php endif; ?>


                <?php echo Former::select('size_id')
                        ->addOption('','')
                        ->fromQuery($sizes, 'name', 'id'); ?>


                <?php echo Former::select('industry_id')
                        ->addOption('','')
                        ->fromQuery($industries, 'name', 'id')
                        ->help('texts.industry_help'); ?>


            </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><?php echo trans('texts.address'); ?></h3>
          </div>
            <div class="panel-body form-padding-right">

            <?php echo Former::text('address1')->autocomplete('address-line1'); ?>

            <?php echo Former::text('address2')->autocomplete('address-line2'); ?>

            <?php echo Former::text('city')->autocomplete('address-level2'); ?>

            <?php echo Former::text('state')->autocomplete('address-level1'); ?>

            <?php echo Former::text('postal_code')->autocomplete('postal-code'); ?>

            <?php echo Former::select('country_id')
                    ->addOption('','')
                    ->fromQuery($countries, 'name', 'id'); ?>


            </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><?php echo trans('texts.defaults'); ?></h3>
          </div>
            <div class="panel-body form-padding-right">

                <?php echo Former::select('payment_type_id')
                        ->addOption('','')
                        ->fromQuery($paymentTypes, 'name', 'id')
                        ->help(trans('texts.payment_type_help')); ?>


                <?php echo Former::select('payment_terms')
                        ->addOption('','')
                        ->fromQuery(\App\Models\PaymentTerm::getSelectOptions(), 'name', 'num_days')
                        ->help(trans('texts.payment_terms_help') . ' | ' . link_to('/settings/payment_terms', trans('texts.customize_options'))); ?>


				<?php if($account->isModuleEnabled(ENTITY_TASK)): ?>
					<?php echo Former::text('task_rate')
					 		->help('task_rate_help'); ?>

				<?php endif; ?>

                <?php echo Former::text('valid_until_days')
                                ->label(trans('texts.valid_until_days'))
                                ->help(trans('texts.valid_until_days_help')); ?>


            </div>
        </div>
        </div>


	</div>

	<center>
        <?php echo Button::success(trans('texts.save'))->submit()->large()->appendIcon(Icon::create('floppy-disk')); ?>

	</center>

    <?php echo Former::close(); ?>


	<?php echo Form::open(['url' => 'remove_logo', 'class' => 'removeLogoForm']); ?>

	<?php echo Form::close(); ?>



	<script type="text/javascript">

        $(function() {
            $('#country_id').combobox();
        });

        function deleteLogo() {
            sweetConfirm(function() {
                $('.removeLogoForm').submit();
            });
        }

	</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>