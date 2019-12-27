<?php $__env->startSection('content'); ?>
##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

<?php echo $__env->make('accounts.nav', ['selected' => ACCOUNT_MANAGEMENT], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<div class="row">
	<div class="col-md-12">
		<?php echo Former::open('settings/change_plan')->addClass('change-plan'); ?>

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo trans('texts.plan_status'); ?></h3>
			</div>
			<div class="panel-body">
				<?php if(Auth::user()->primaryAccount()->id != Auth::user()->account->id): ?>
					<center style="font-size:16px;color:#888888;">
						<?php echo e(trans('texts.switch_to_primary', ['name' => Auth::user()->primaryAccount()->getDisplayName()])); ?>

					</center>
				<?php else: ?>
					<div class="form-group">
						<label class="col-sm-4 control-label"><?php echo e(trans('texts.plan')); ?></label>
						<div class="col-sm-8">
							<p class="form-control-static">
								<?php if($planDetails && $planDetails['active']): ?>
									<?php echo e(trans('texts.plan_'.$planDetails['plan'])); ?>

									<?php if($planDetails['trial']): ?>
										(<?php echo e(trans('texts.plan_trial')); ?>)
									<?php elseif($planDetails['expires']): ?>
										(<?php echo e(trans('texts.plan_term_'.$planDetails['term'].'ly')); ?>)
									<?php endif; ?>
	                                <?php if($planDetails['plan'] == PLAN_ENTERPRISE): ?>
	                                    <?php echo e(trans('texts.min_to_max_users', ['min' => Utils::getMinNumUsers($planDetails['num_users']), 'max' => $planDetails['num_users']])); ?>

	                                <?php endif; ?>
									<?php if($portalLink): ?>
										- <?php echo e(link_to($portalLink, trans('texts.view_client_portal'), ['target' => '_blank'])); ?>

									<?php endif; ?>
								<?php elseif(Utils::isNinjaProd()): ?>
									<?php echo e(trans('texts.plan_free')); ?>

								<?php else: ?>
									<?php echo e(trans('texts.plan_free_self_hosted')); ?>

								<?php endif; ?>
							</p>
						</div>
					</div>
					<?php if($planDetails && $planDetails['active']): ?>
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<?php echo e(trans('texts.renews')); ?>

							</label>
							<div class="col-sm-8">
								<p class="form-control-static">
									<?php if($planDetails['expires'] === false): ?>
										<?php echo e(trans('texts.never')); ?>

									<?php else: ?>
										<?php echo e(Utils::dateToString($planDetails['expires'])); ?>

									<?php endif; ?>
								</p>
							</div>
						</div>

						<?php if($account->company->hasActiveDiscount()): ?>
							<?php echo Former::plaintext('discount')
									->value($account->company->present()->discountMessage); ?>

						<?php endif; ?>

						<?php if(Utils::isNinjaProd() && Auth::user()->confirmed): ?>
							<?php echo Former::actions( Button::info(trans('texts.plan_change'))->large()->withAttributes(['onclick' => 'showChangePlan()'])->appendIcon(Icon::create('edit'))); ?>

						<?php endif; ?>
					<?php else: ?>
						<?php if($planDetails): ?>
							<div class="form-group">
								<label class="col-sm-4 control-label">
									<?php if($planDetails['trial']): ?>
										<?php echo e(trans('texts.trial_expired', ['plan'=>trans('texts.plan_'.$planDetails['plan'])])); ?>

									<?php else: ?>
										<?php echo e(trans('texts.plan_expired', ['plan'=>trans('texts.plan_'.$planDetails['plan'])])); ?>

									<?php endif; ?>
								</label>
								<div class="col-sm-8">
									<p class="form-control-static">
										<?php echo e(Utils::dateToString($planDetails['expires'])); ?>

									</p>
								</div>
							</div>
						<?php endif; ?>
						<?php if(Utils::isNinjaProd()): ?>
							<?php if(Auth::user()->confirmed): ?>
						   		<?php echo Former::actions( Button::success(trans('texts.plan_upgrade'))->large()->withAttributes(['onclick' => 'showChangePlan()'])->appendIcon(Icon::create('plus-sign'))); ?>

							<?php endif; ?>
						<?php elseif(!$account->hasFeature(FEATURE_WHITE_LABEL)): ?>
						   <?php echo Former::actions( Button::success(trans('texts.white_label_button'))->large()->withAttributes(['onclick' => 'loadImages("#whiteLabelModal");$("#whiteLabelModal").modal("show");'])->appendIcon(Icon::create('plus-sign'))); ?>

						<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>

				<?php if(Auth::user()->created_at->diffInMonths() >= 3): ?>
					<?php echo Former::plaintext(' ')->help(trans('texts.review_app_help', ['link' => link_to('http://www.capterra.com/p/145215/Invoice-Ninja', trans('texts.writing_a_review'), ['target' => '_blank'])])); ?>

				<?php endif; ?>
			</div>
		</div>
		<?php if(Utils::isNinjaProd()): ?>
			<div class="modal fade" id="changePlanModel" tabindex="-1" role="dialog" aria-labelledby="changePlanModelLabel" aria-hidden="true">
				<div class="modal-dialog" style="min-width:150px">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="changePlanModelLabel">
								<?php if($planDetails && $planDetails['active']): ?>
									<?php echo trans('texts.plan_change'); ?>

								<?php else: ?>
									<?php echo trans('texts.plan_upgrade'); ?>

								<?php endif; ?>
							</h4>
						</div>
						<div class="container" style="width: 100%; padding-bottom: 0px !important">
			            <div class="panel panel-default">
			            <div class="panel-body">

							<?php if($planDetails && $planDetails['active']): ?>
    							<?php echo Former::select('plan')
                                    ->onchange('onPlanChange()')
                                    ->addOption(trans('texts.plan_free'), PLAN_FREE)
    								->addOption(trans('texts.plan_pro'), PLAN_PRO)
                                    ->addOption(trans('texts.plan_enterprise'), PLAN_ENTERPRISE); ?>

							<?php else: ?>
    							<?php echo Former::select('plan')
                                    ->onchange('onPlanChange()')
                                    ->addOption(trans('texts.plan_pro'), PLAN_PRO)
    								->addOption(trans('texts.plan_enterprise'), PLAN_ENTERPRISE); ?>

							<?php endif; ?>

                            <div id="numUsersDiv">
                                <?php echo Former::select('num_users')
                                    ->label(trans('texts.users'))
                                    ->addOption('1 to 2', 2)
    								->addOption('3 to 5', 5)
                                    ->addOption('6 to 10', 10)
									->addOption('11 to 20', 20); ?>

                            </div>

							<?php echo Former::select('plan_term')
								->addOption(trans('texts.plan_term_monthly'), PLAN_TERM_MONTHLY)
                                ->addOption(trans('texts.plan_term_yearly'), PLAN_TERM_YEARLY)
								->inlineHelp(trans('texts.enterprise_plan_features', ['link' => link_to(NINJA_WEB_URL . '/plans-pricing', trans('texts.click_here'), ['target' => '_blank'])])); ?>


							<?php echo Former::plaintext(' ')
								->inlineHelp($account->company->present()->promoMessage); ?>


						</div>
						</div>
						</div>
						<div class="modal-footer">
                            <?php if(Utils::isPro()): ?>
                                <div class="pull-left" style="padding-top: 8px;color:#888888">
                                    <?php echo e(trans('texts.changes_take_effect_immediately')); ?>

                                </div>
                            <?php endif; ?>
							<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(trans('texts.go_back')); ?></button>
							<?php if($planDetails && $planDetails['active']): ?>
								<button type="button" class="btn btn-primary" id="changePlanButton" onclick="confirmChangePlan()"><?php echo e(trans('texts.plan_change')); ?></button>
							<?php else: ?>
								<button type="button" class="btn btn-success" id="changePlanButton" onclick="confirmChangePlan()"><?php echo e(trans('texts.plan_upgrade')); ?></button>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php echo Former::close(); ?>



		<?php echo Former::open('settings/account_management'); ?>

		<?php echo Former::populateField('live_preview', intval($account->live_preview)); ?>

		<?php echo Former::populateField('realtime_preview', intval($account->realtime_preview)); ?>

		<?php echo Former::populateField('force_pdfjs', intval(Auth::user()->force_pdfjs)); ?>


		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo trans('texts.modules'); ?></h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="modules" class="control-label col-lg-4 col-sm-4"></label>
					<div class="col-lg-8 col-sm-8">
						<?php $__currentLoopData = \App\Models\Account::$modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entityType => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<div class="checkbox">
							<label for="modules_<?php echo e($value); ?>">
								<input name="modules[]" id="modules_<?php echo e($value); ?>" type="checkbox" <?php echo e(Auth::user()->account->isModuleEnabled($entityType) ? 'checked="checked"' : ''); ?> value="<?php echo e($value); ?>"><?php echo e(trans("texts.module_{$entityType}")); ?>

							</label>
						</div>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php if(Utils::isSelfHost()): ?>
							<?php $__currentLoopData = Module::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<?php echo e(($value->boot())); ?>

							<div class="checkbox">
								<label for="custom_modules_<?php echo e($value); ?>">
									<input name="custom_modules[]" id="custom_modules_<?php echo e($value); ?>" type="checkbox" <?php echo e($value->enabled() ? 'checked="checked"' : ''); ?> value="<?php echo e($value); ?>"><?php echo e(mtrans($value, $value->getLowerName())); ?>

								</label>
							</div>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php endif; ?>
					</div>
				</div>
				<div class="form-group">
					<label for="modules" class="control-label col-lg-4 col-sm-4"></label>
					<div class="col-lg-8 col-sm-8">
						<?php echo Button::success(trans('texts.save'))->submit()->large()->appendIcon(Icon::create('floppy-disk')); ?>

					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo trans('texts.pdf_settings'); ?></h3>
			</div>
			<div class="panel-body">

				<?php echo Former::checkbox('live_preview')
						->text(trans('texts.enable'))
						->help(trans('texts.live_preview_help') . '<br/>' . trans('texts.recommend_on'))
						->value(1); ?>


				<?php echo Former::checkbox('realtime_preview')
						->text(trans('texts.enable'))
						->help(trans('texts.realtime_preview_help'))
						->value(1); ?>


				<?php echo Former::checkbox('force_pdfjs')
						->text(trans('texts.enable'))
						->value(1)
						->help(trans('texts.force_pdfjs_help', [
							'chrome_link' => link_to(CHROME_PDF_HELP_URL, 'Chrome', ['target' => '_blank']),
							'firefox_link' => link_to(FIREFOX_PDF_HELP_URL, 'Firefox', ['target' => '_blank']),
						])  . '<br/>' . trans('texts.recommend_off')); ?>


				<div class="form-group">
					<label for="modules" class="control-label col-lg-4 col-sm-4"></label>
					<div class="col-lg-8 col-sm-8">
						<?php echo Button::success(trans('texts.save'))->submit()->large()->appendIcon(Icon::create('floppy-disk')); ?>

					</div>
				</div>
			</div>
		</div>

		<?php echo Former::close(); ?>


		<?php if(! Auth::user()->account->isNinjaOrLicenseAccount()): ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo trans('texts.delete_data'); ?></h3>
				</div>
				<div class="panel-body">
					<?php echo Former::open('settings/purge_data')->addClass('purge-data'); ?>

					<?php echo Former::actions(
							Button::danger(trans('texts.purge_data'))
								->withAttributes(['onclick' => 'showPurgeConfirm()'])
								->appendIcon(Icon::create('trash'))
								->large()
							); ?>

					<div class="form-group">
						<div class="col-lg-8 col-sm-8 col-lg-offset-4 col-sm-offset-4">
							<span class="help-block"><?php echo e(trans('texts.purge_data_help')); ?></span>
						</div>
					</div>
					<br/>
					<div class="modal fade" id="confirmPurgeModal" tabindex="-1" role="dialog" aria-labelledby="confirmPurgeModalLabel" aria-hidden="true">
						<div class="modal-dialog" style="min-width:150px">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title" id="confirmPurgeModalLabel"><?php echo trans('texts.purge_data'); ?></h4>
								</div>
								<div class="container" style="width: 100%; padding-bottom: 0px !important">
				                <div class="panel panel-default">
				                <div class="panel-body">
									<p><b><?php echo e(trans('texts.purge_data_message')); ?></b></p>
									<br/>
									<p><?php echo e(trans('texts.mobile_refresh_warning')); ?></p>
									<br/>
								</div>
								</div>
								</div>
								<div class="modal-footer" style="margin-top: 2px">
									<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(trans('texts.go_back')); ?></button>
									<button type="button" class="btn btn-danger" id="purgeButton" onclick="confirmPurge()"><?php echo e(trans('texts.purge_data')); ?></button>
								</div>
							</div>
						</div>
					</div>
					<?php echo Former::close(); ?>


					<?php if(! $account->hasMultipleAccounts() || $account->getPrimaryAccount()->id != $account->id): ?>
						<?php echo Former::open('settings/cancel_account')->addClass('cancel-account'); ?>

						<?php echo Former::actions( Button::danger($account->hasMultipleAccounts() ? trans('texts.delete_company') : trans('texts.cancel_account'))->large()->withAttributes(['onclick' => 'showCancelConfirm()'])->appendIcon(Icon::create('trash'))); ?>

						<div class="form-group">
							<div class="col-lg-8 col-sm-8 col-lg-offset-4 col-sm-offset-4">
								<span class="help-block"><?php echo e($account->hasMultipleAccounts() ? trans('texts.delete_company_help') : trans('texts.cancel_account_help')); ?></span>
							</div>
						</div>
						<div class="modal fade" id="confirmCancelModal" tabindex="-1" role="dialog" aria-labelledby="confirmCancelModalLabel" aria-hidden="true">
							<div class="modal-dialog" style="min-width:150px">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title" id="confirmCancelModalLabel"><?php echo e($account->hasMultipleAccounts() ? trans('texts.delete_company') : trans('texts.cancel_account')); ?></h4>
									</div>
									<div class="container" style="width: 100%; padding-bottom: 0px !important">
					                <div class="panel panel-default">
					                <div class="panel-body">
										<p><b><?php echo e($account->hasMultipleAccounts() ? trans('texts.delete_company_message') : trans('texts.cancel_account_message')); ?></b></p><br/>
										<?php if($account->getPrimaryAccount()->id == $account->id): ?>
											<p><?php echo Former::textarea('reason')
														->placeholder(trans('texts.reason_for_canceling'))
														->raw()
														->rows(4); ?></p>
										<?php endif; ?>
										<br/>
									</div>
									</div>
									</div>
									<div class="modal-footer" style="margin-top: 2px">
										<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(trans('texts.go_back')); ?></button>
										<button type="button" class="btn btn-danger" id="deleteButton" onclick="confirmCancel()"><?php echo e($account->hasMultipleAccounts() ? trans('texts.delete_company') : trans('texts.cancel_account')); ?></button>
									</div>
								</div>
							</div>
						</div>
					<?php elseif($account->hasMultipleAccounts()): ?>
						<div class="form-group">
							<div class="col-lg-8 col-sm-8 col-lg-offset-4 col-sm-offset-4">
								<span class="help-block"><?php echo e(trans('texts.unable_to_delete_primary')); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php echo Former::close(); ?>

				</div>
			</div>
		<?php endif; ?>

	</div>
</div>

<script type="text/javascript">

	// show plan popupl when clicking 'Upgrade' in navbar
	function showUpgradeModal() {
		showChangePlan();
	}

	function showChangePlan() {
		$('#changePlanModel').modal('show');
	}

	function confirmChangePlan() {
		$('form.change-plan').submit();
	}

	function showCancelConfirm() {
		$('#confirmCancelModal').modal('show');
	}

	function showPurgeConfirm() {
		$('#confirmPurgeModal').modal('show');
	}

	function confirmCancel() {
		$('#deleteButton').prop('disabled', true);
		$('form.cancel-account').submit();
	}

	function confirmPurge() {
		$('#purgeButton').prop('disabled', true);
		$('form.purge-data').submit();
	}

    function onPlanChange() {
        if ($('#plan').val() == '<?php echo e(PLAN_ENTERPRISE); ?>') {
            $('#numUsersDiv').show();
        } else {
            $('#numUsersDiv').hide();
        }
    }

    function updateCheckboxes() {
        var checked = $('#live_preview').is(':checked');
        $('#realtime_preview').prop('disabled', ! checked);
    }

  	jQuery(document).ready(function($){
		function updatePlanModal() {
			var plan = $('#plan').val();
            var numUsers = $('#num_users').val();
	 		$('#plan_term').closest('.form-group').toggle(plan!='free');

			if(plan=='<?php echo e(PLAN_PRO); ?>'){
				$('#plan_term option[value=month]').text(<?php echo json_encode(trans('texts.plan_price_monthly', ['price'=>PLAN_PRICE_PRO_MONTHLY])); ?>);
				$('#plan_term option[value=year]').text(<?php echo json_encode(trans('texts.plan_price_yearly', ['price'=>PLAN_PRICE_PRO_MONTHLY * 10])); ?>);
			} else if(plan=='<?php echo e(PLAN_ENTERPRISE); ?>') {
                if (numUsers == 2) {
                    $('#plan_term option[value=month]').text(<?php echo json_encode(trans('texts.plan_price_monthly', ['price'=>PLAN_PRICE_ENTERPRISE_MONTHLY_2])); ?>);
                    $('#plan_term option[value=year]').text(<?php echo json_encode(trans('texts.plan_price_yearly', ['price'=>PLAN_PRICE_ENTERPRISE_MONTHLY_2 * 10])); ?>);
                } else if (numUsers == 5) {
                    $('#plan_term option[value=month]').text(<?php echo json_encode(trans('texts.plan_price_monthly', ['price'=>PLAN_PRICE_ENTERPRISE_MONTHLY_5])); ?>);
                    $('#plan_term option[value=year]').text(<?php echo json_encode(trans('texts.plan_price_yearly', ['price'=>PLAN_PRICE_ENTERPRISE_MONTHLY_5 * 10])); ?>);
				} else if (numUsers == 10) {
                    $('#plan_term option[value=month]').text(<?php echo json_encode(trans('texts.plan_price_monthly', ['price'=>PLAN_PRICE_ENTERPRISE_MONTHLY_10])); ?>);
                    $('#plan_term option[value=year]').text(<?php echo json_encode(trans('texts.plan_price_yearly', ['price'=>PLAN_PRICE_ENTERPRISE_MONTHLY_10 * 10])); ?>);
				} else {
					$('#plan_term option[value=month]').text(<?php echo json_encode(trans('texts.plan_price_monthly', ['price'=>PLAN_PRICE_ENTERPRISE_MONTHLY_20])); ?>);
					$('#plan_term option[value=year]').text(<?php echo json_encode(trans('texts.plan_price_yearly', ['price'=>PLAN_PRICE_ENTERPRISE_MONTHLY_20 * 10])); ?>);
				}
			}
  	  	}
		$('#plan_term, #plan, #num_users').change(updatePlanModal);
	  	updatePlanModal();
        onPlanChange();

        $('#live_preview').change(updateCheckboxes);
        updateCheckboxes();

		if(window.location.hash) {
			var hash = window.location.hash;
			$(hash).modal('toggle');
	  	}

        <?php if(Request::input('upgrade')): ?>
          showChangePlan();
        <?php endif; ?>
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>