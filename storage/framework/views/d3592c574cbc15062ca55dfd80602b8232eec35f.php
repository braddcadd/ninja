<?php $__env->startSection('onReady'); ?>
	$('input#name').focus();
<?php $__env->stopSection(); ?>

<?php $__env->startSection('head'); ?>
	<?php if(config('ninja.google_maps_api_key')): ?>
		<?php echo $__env->make('partials.google_geocode', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php if($errors->first('contacts')): ?>
    <div class="alert alert-danger"><?php echo e(trans($errors->first('contacts'))); ?></div>
<?php endif; ?>

<div class="row">

	<?php echo Former::open($url)
            ->autocomplete('off')
            ->rules(
                ['email' => 'email']
            )->addClass('col-md-12 warn-on-exit')
            ->method($method); ?>


    <?php echo $__env->make('partials.autocomplete_fix', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

	<?php if($client): ?>
		<?php echo Former::populate($client); ?>

		<?php echo Former::populateField('task_rate', floatval($client->task_rate) ? Utils::roundSignificant($client->task_rate) : ''); ?>

		<?php echo Former::populateField('show_tasks_in_portal', intval($client->show_tasks_in_portal)); ?>

		<?php echo Former::populateField('send_reminders', intval($client->send_reminders)); ?>

        <?php echo Former::hidden('public_id'); ?>

	<?php else: ?>
		<?php echo Former::populateField('invoice_number_counter', 1); ?>

		<?php echo Former::populateField('quote_number_counter', 1); ?>

		<?php echo Former::populateField('send_reminders', 1); ?>

		<?php if($account->client_number_counter): ?>
			<?php echo Former::populateField('id_number', $account->getNextNumber()); ?>

		<?php endif; ?>
	<?php endif; ?>

	<div class="row">
		<div class="col-md-6">


        <div class="panel panel-default" style="min-height: 380px">
          <div class="panel-heading">
            <h3 class="panel-title"><?php echo trans('texts.details'); ?></h3>
          </div>
            <div class="panel-body">

			<?php echo Former::text('name')->data_bind("attr { placeholder: placeholderName }"); ?>

			<?php echo Former::text('id_number')->placeholder($account->clientNumbersEnabled() ? $account->getNextNumber() : ' '); ?>

            <?php echo Former::text('vat_number'); ?>

            <?php echo Former::text('website'); ?>

			<?php echo Former::text('work_phone'); ?>



			<?php echo $__env->make('partials/custom_fields', ['entityType' => ENTITY_CLIENT], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

			<?php if($account->usesClientInvoiceCounter()): ?>
				<?php echo Former::text('invoice_number_counter')->label('invoice_counter'); ?>


				<?php if(! $account->share_counter): ?>
					<?php echo Former::text('quote_number_counter')->label('quote_counter'); ?>

				<?php endif; ?>
			<?php endif; ?>
            </div>
        </div>

		<div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><?php echo trans('texts.address'); ?></h3>
          </div>
            <div class="panel-body">

				<div role="tabpanel">
					<ul class="nav nav-tabs" role="tablist" style="border: none">
						<li role="presentation" class="active">
							<a href="#billing_address" aria-controls="billing_address" role="tab" data-toggle="tab"><?php echo e(trans('texts.billing_address')); ?></a>
						</li>
						<li role="presentation">
							<a href="#shipping_address" aria-controls="shipping_address" role="tab" data-toggle="tab"><?php echo e(trans('texts.shipping_address')); ?></a>
						</li>
					</ul>
				</div>
				<div class="tab-content" style="padding-top:24px;">
					<div role="tabpanel" class="tab-pane active" id="billing_address">
						<?php echo Former::text('address1'); ?>

						<?php echo Former::text('address2'); ?>

						<?php echo Former::text('city'); ?>

						<?php echo Former::text('state'); ?>

						<?php echo Former::text('postal_code')
								->oninput(config('ninja.google_maps_api_key') ? 'lookupPostalCode()' : ''); ?>

						<?php echo Former::select('country_id')->addOption('','')
							->autocomplete('off')
							->fromQuery($countries, 'name', 'id'); ?>


						<div class="form-group" id="copyShippingDiv" style="display:none;">
							<label for="city" class="control-label col-lg-4 col-sm-4"></label>
							<div class="col-lg-8 col-sm-8">
								<?php echo Button::normal(trans('texts.copy_shipping'))->small(); ?>

							</div>
						</div>

					</div>
					<div role="tabpanel" class="tab-pane" id="shipping_address">
						<?php echo Former::text('shipping_address1')->label('address1'); ?>

						<?php echo Former::text('shipping_address2')->label('address2'); ?>

						<?php echo Former::text('shipping_city')->label('city'); ?>

						<?php echo Former::text('shipping_state')->label('state'); ?>

						<?php echo Former::text('shipping_postal_code')
								->oninput(config('ninja.google_maps_api_key') ? 'lookupPostalCode(true)' : '')
								->label('postal_code'); ?>

						<?php echo Former::select('shipping_country_id')->addOption('','')
							->autocomplete('off')
							->fromQuery($countries, 'name', 'id')->label('country_id'); ?>


						<div class="form-group" id="copyBillingDiv" style="display:none;">
							<label for="city" class="control-label col-lg-4 col-sm-4"></label>
							<div class="col-lg-8 col-sm-8">
								<?php echo Button::normal(trans('texts.copy_billing'))->small(); ?>

							</div>
						</div>
					</div>
				</div>

        </div>
        </div>
		</div>
		<div class="col-md-6">


        <div class="panel panel-default" style="min-height: 380px">
          <div class="panel-heading">
            <h3 class="panel-title"><?php echo trans('texts.contacts'); ?></h3>
          </div>
            <div class="panel-body">

			<div data-bind='template: { foreach: contacts,
		                            beforeRemove: hideContact,
		                            afterAdd: showContact }'>
				<?php echo Former::hidden('public_id')->data_bind("value: public_id, valueUpdate: 'afterkeydown',
                        attr: {name: 'contacts[' + \$index() + '][public_id]'}"); ?>

				<?php echo Former::text('first_name')->data_bind("value: first_name, valueUpdate: 'afterkeydown',
                        attr: {name: 'contacts[' + \$index() + '][first_name]'}"); ?>

				<?php echo Former::text('last_name')->data_bind("value: last_name, valueUpdate: 'afterkeydown',
                        attr: {name: 'contacts[' + \$index() + '][last_name]'}"); ?>

				<?php echo Former::text('email')->data_bind("value: email, valueUpdate: 'afterkeydown',
                        attr: {name: 'contacts[' + \$index() + '][email]', id:'email'+\$index()}"); ?>

				<?php echo Former::text('phone')->data_bind("value: phone, valueUpdate: 'afterkeydown',
                        attr: {name: 'contacts[' + \$index() + '][phone]'}"); ?>

				<?php if($account->hasFeature(FEATURE_CLIENT_PORTAL_PASSWORD) && $account->enable_portal_password): ?>
					<?php echo Former::password('password')->data_bind("value: password()?'-%unchanged%-':'', valueUpdate: 'afterkeydown',
						attr: {name: 'contacts[' + \$index() + '][password]'}")->autocomplete('new-password')->data_lpignore('true'); ?>

			    <?php endif; ?>

				<?php if(Auth::user()->hasFeature(FEATURE_INVOICE_SETTINGS)): ?>
					<?php if($account->customLabel('contact1')): ?>
						<?php echo $__env->make('partials.custom_field', [
							'field' => 'custom_contact1',
							'label' => $account->customLabel('contact1'),
							'databind' => "value: custom_value1, valueUpdate: 'afterkeydown',
									attr: {name: 'contacts[' + \$index() + '][custom_value1]'}",
						], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
					<?php endif; ?>
					<?php if($account->customLabel('contact2')): ?>
						<?php echo $__env->make('partials.custom_field', [
							'field' => 'custom_contact2',
							'label' => $account->customLabel('contact2'),
							'databind' => "value: custom_value2, valueUpdate: 'afterkeydown',
									attr: {name: 'contacts[' + \$index() + '][custom_value2]'}",
						], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
					<?php endif; ?>
				<?php endif; ?>

				<div class="form-group">
					<div class="col-lg-8 col-lg-offset-4 bold">
						<span class="redlink bold" data-bind="visible: $parent.contacts().length > 1">
							<?php echo link_to('#', trans('texts.remove_contact').' -', array('data-bind'=>'click: $parent.removeContact')); ?>

						</span>
						<span data-bind="visible: $index() === ($parent.contacts().length - 1)" class="pull-right greenlink bold">
							<?php echo link_to('#', trans('texts.add_contact').' +', array('onclick'=>'return addContact()')); ?>

						</span>
					</div>
				</div>
			</div>
            </div>
            </div>


        <div class="panel panel-default" style="min-height:505px">
          <div class="panel-heading">
            <h3 class="panel-title"><?php echo trans('texts.additional_info'); ?></h3>
          </div>
            <div class="panel-body">

				<div role="tabpanel">
					<ul class="nav nav-tabs" role="tablist" style="border: none">
						<li role="presentation" class="active">
							<a href="#settings" aria-controls="settings" role="tab" data-toggle="tab"><?php echo e(trans('texts.settings')); ?></a>
						</li>
						<li role="presentation">
							<a href="#notes" aria-controls="notes" role="tab" data-toggle="tab"><?php echo e(trans('texts.notes')); ?></a>
						</li>
						<?php if(Utils::isPaidPro()): ?>
							<li role="presentation">
	                            <a href="#messages" aria-controls="messages" role="tab" data-toggle="tab"><?php echo e(trans('texts.messages')); ?></a>
	                        </li>
						<?php endif; ?>
						<li role="presentation">
							<a href="#classify" aria-controls="classify" role="tab" data-toggle="tab"><?php echo e(trans('texts.classify')); ?></a>
						</li>
					</ul>
				</div>
				<div class="tab-content" style="padding-top:24px;">
					<div role="tabpanel" class="tab-pane active" id="settings">
						<?php echo Former::select('currency_id')->addOption('','')
			                ->placeholder($account->currency ? $account->currency->getTranslatedName() : '')
			                ->fromQuery($currencies, 'name', 'id'); ?>

			            <?php echo Former::select('language_id')->addOption('','')
			                ->placeholder($account->language ? trans('texts.lang_'.$account->language->name) : '')
			                ->fromQuery($languages, 'name', 'id'); ?>

						<?php echo Former::select('payment_terms')->addOption('','')
							->fromQuery(\App\Models\PaymentTerm::getSelectOptions(), 'name', 'num_days')
							->placeholder($account->present()->paymentTerms)
			                ->help(trans('texts.payment_terms_help') . ' | ' . link_to('/settings/payment_terms', trans('texts.customize_options'))); ?>

						<?php if($account->isModuleEnabled(ENTITY_TASK)): ?>
							<?php echo Former::text('task_rate')
									->placeholder($account->present()->taskRate)
									->help('task_rate_help'); ?>

							<?php echo Former::checkbox('show_tasks_in_portal')
						        ->text(trans('texts.show_tasks_in_portal'))
								->label('client_portal')
						        ->value(1); ?>

						<?php endif; ?>
						<?php if($account->hasReminders()): ?>
							<?php echo Former::checkbox('send_reminders')
								->text('send_client_reminders')
								->label('reminders')
								->value(1); ?>

						<?php endif; ?>
					</div>
					<div role="tabpanel" class="tab-pane" id="notes">
						<?php echo Former::textarea('public_notes')->rows(6); ?>

						<?php echo Former::textarea('private_notes')->rows(6); ?>

					</div>
					<?php if(Utils::isPaidPro()): ?>
						<div role="tabpanel" class="tab-pane" id="messages">
							<?php $__currentLoopData = App\Models\Account::$customMessageTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php echo Former::textarea('custom_messages[' . $type . ']')
										->placeholder($account->customMessage($type))
										->label($type); ?>

							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</div>
					<?php endif; ?>
					<div role="tabpanel" class="tab-pane" id="classify">
						<?php echo Former::select('size_id')->addOption('','')
							->fromQuery($sizes, 'name', 'id'); ?>

						<?php echo Former::select('industry_id')->addOption('','')
							->fromQuery($industries, 'name', 'id'); ?>

					</div>
				</div>
		</div>
		</div>


		<?php if(Auth::user()->account->isNinjaAccount()): ?>
		<div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><?php echo trans('texts.pro_plan_product'); ?></h3>
          </div>
            <div class="panel-body">

				<?php if(isset($planDetails)): ?>
					<?php echo Former::populateField('plan', $planDetails['plan']); ?>

					<?php echo Former::populateField('plan_term', $planDetails['term']); ?>

					<?php echo Former::populateField('plan_price', $planDetails['plan_price']); ?>

					<?php if(!empty($planDetails['paid'])): ?>
						<?php echo Former::populateField('plan_paid', $planDetails['paid']->format('Y-m-d')); ?>

					<?php endif; ?>
					<?php if(!empty($planDetails['expires'])): ?>
						<?php echo Former::populateField('plan_expires', $planDetails['expires']->format('Y-m-d')); ?>

					<?php endif; ?>
					<?php if(!empty($planDetails['started'])): ?>
						<?php echo Former::populateField('plan_started', $planDetails['started']->format('Y-m-d')); ?>

					<?php endif; ?>
				<?php endif; ?>
				<?php echo Former::select('plan')
							->addOption(trans('texts.plan_free'), PLAN_FREE)
							->addOption(trans('texts.plan_pro'), PLAN_PRO)
							->addOption(trans('texts.plan_enterprise'), PLAN_ENTERPRISE); ?>

				<?php echo Former::select('plan_term')
							->addOption()
							->addOption(trans('texts.plan_term_yearly'), PLAN_TERM_YEARLY)
							->addOption(trans('texts.plan_term_monthly'), PLAN_TERM_MONTHLY); ?>

				<?php echo Former::text('plan_price'); ?>

				<?php echo Former::text('plan_started')
                            ->data_date_format('yyyy-mm-dd')
                            ->addGroupClass('plan_start_date')
                            ->append('<i class="glyphicon glyphicon-calendar"></i>'); ?>

                <?php echo Former::text('plan_paid')
                            ->data_date_format('yyyy-mm-dd')
                            ->addGroupClass('plan_paid_date')
                            ->append('<i class="glyphicon glyphicon-calendar"></i>'); ?>

				<?php echo Former::text('plan_expires')
                            ->data_date_format('yyyy-mm-dd')
                            ->addGroupClass('plan_expire_date')
                            ->append('<i class="glyphicon glyphicon-calendar"></i>'); ?>

                <script type="text/javascript">
                    $(function() {
                        $('#plan_started, #plan_paid, #plan_expires').datepicker();
                    });
                </script>

            </div>
            </div>
			<?php endif; ?>


		</div>
	</div>


	<?php echo Former::hidden('data')->data_bind("value: ko.toJSON(model)"); ?>


	<script type="text/javascript">

	$(function() {
		$('#country_id, #shipping_country_id').combobox();

		// show/hide copy buttons if address is set
		$('#billing_address').change(function() {
			$('#copyBillingDiv').toggle(isAddressSet());
		});
		$('#shipping_address').change(function() {
			$('#copyShippingDiv').toggle(isAddressSet(true));
		});

		// button handles to copy the address
		$('#copyBillingDiv button').click(function() {
			copyAddress();
			$('#copyBillingDiv').hide();
		});
		$('#copyShippingDiv button').click(function() {
			copyAddress(true);
			$('#copyShippingDiv').hide();
		});

		// show/hide buttons based on loaded values
		if (<?php echo e($client && $client->hasAddress() ? 'true' : 'false'); ?>) {
			$('#copyBillingDiv').show();
		}
		if (<?php echo e($client && $client->hasAddress(true) ? 'true' : 'false'); ?>) {
			$('#copyShippingDiv').show();
		}
	});

	function copyAddress(shipping) {
		var fields = [
			'address1',
			'address2',
			'city',
			'state',
			'postal_code',
			'country_id',
		]
		for (var i=0; i<fields.length; i++) {
			var field1 = fields[i];
			var field2 = 'shipping_' + field1;
			if (shipping) {
				$('#' + field1).val($('#' + field2).val());
			} else {
				$('#' + field2).val($('#' + field1).val());
			}
		}
		$('#country_id').combobox('refresh');
		$('#shipping_country_id').combobox('refresh');
	}

	function isAddressSet(shipping) {
		var fields = [
			'address1',
			'address2',
			'city',
			'state',
			'postal_code',
			'country_id',
		]
		for (var i=0; i<fields.length; i++) {
			var field = fields[i];
			if (shipping) {
				field = 'shipping_' + field;
			}
			if ($('#' + field).val()) {
				return true;
			}
		}
		return false;
	}

	function ContactModel(data) {
		var self = this;
		self.public_id = ko.observable('');
		self.first_name = ko.observable('');
		self.last_name = ko.observable('');
		self.email = ko.observable('');
		self.phone = ko.observable('');
		self.password = ko.observable('');
		self.custom_value1 = ko.observable('');
		self.custom_value2 = ko.observable('');

		if (data) {
			ko.mapping.fromJS(data, {}, this);
		}
	}

	function ClientModel(data) {
		var self = this;

        self.contacts = ko.observableArray();

		self.mapping = {
		    'contacts': {
		    	create: function(options) {
		    		return new ContactModel(options.data);
		    	}
		    }
		}

		if (data) {
			ko.mapping.fromJS(data, self.mapping, this);
		} else {
			self.contacts.push(new ContactModel());
		}

		self.placeholderName = ko.computed(function() {
			if (self.contacts().length == 0) return '';
			var contact = self.contacts()[0];
			if (contact.first_name() || contact.last_name()) {
				return (contact.first_name() || '') + ' ' + (contact.last_name() || '');
			} else {
				return contact.email();
			}
		});
	}

    <?php if($data): ?>
        window.model = new ClientModel(<?php echo $data; ?>);
    <?php else: ?>
	    window.model = new ClientModel(<?php echo $client; ?>);
    <?php endif; ?>

	model.showContact = function(elem) { if (elem.nodeType === 1) $(elem).hide().slideDown() }
	model.hideContact = function(elem) { if (elem.nodeType === 1) $(elem).slideUp(function() { $(elem).remove(); }) }


	ko.applyBindings(model);

	function addContact() {
		model.contacts.push(new ContactModel());
		return false;
	}

	model.removeContact = function() {
		model.contacts.remove(this);
	}


	</script>
	<?php if(Auth::user()->canCreateOrEdit(ENTITY_CLIENT)): ?>
	<center class="buttons">
    	<?php echo Button::normal(trans('texts.cancel'))->large()->asLinkTo(URL::to('/clients/' . ($client ? $client->public_id : '')))->appendIcon(Icon::create('remove-circle')); ?>

        <?php echo Button::success(trans('texts.save'))->submit()->large()->appendIcon(Icon::create('floppy-disk')); ?>

	</center>
	<?php endif; ?>
	<?php echo Former::close(); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>