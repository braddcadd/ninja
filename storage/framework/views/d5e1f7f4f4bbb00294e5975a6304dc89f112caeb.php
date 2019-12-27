<?php $__env->startSection('onReady'); ?>
	$('input#name').focus();
<?php $__env->stopSection(); ?>

<?php $__env->startSection('head'); ?>
	<?php if(config('ninja.google_maps_api_key')): ?>
		<?php echo $__env->make('partials.google_geocode', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php if($errors->first('vendor_contacts')): ?>
    <div class="alert alert-danger"><?php echo e(trans($errors->first('vendor_contacts'))); ?></div>
<?php endif; ?>

<div class="row">

	<?php echo Former::open($url)
            ->autocomplete('off')
            ->rules([
                'namey' => 'required',
                'email' => 'email'
            ])->addClass('col-md-12 warn-on-exit')
            ->method($method); ?>


    <?php echo $__env->make('partials.autocomplete_fix', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

	<?php if($vendor): ?>
		<?php echo Former::populate($vendor); ?>

        <?php echo Former::hidden('public_id'); ?>

	<?php endif; ?>

	<div class="row">
		<div class="col-md-6">


        <div class="panel panel-default" style="min-height: 380px">
          <div class="panel-heading">
            <h3 class="panel-title"><?php echo trans('texts.organization'); ?></h3>
          </div>
            <div class="panel-body">

			<?php echo Former::text('name')->data_bind("attr { placeholder: placeholderName }"); ?>

			<?php echo Former::text('id_number'); ?>

                        <?php echo Former::text('vat_number'); ?>

                        <?php echo Former::text('website'); ?>

			<?php echo Former::text('work_phone'); ?>


			<?php echo $__env->make('partials/custom_fields', ['entityType' => ENTITY_VENDOR], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            </div>
            </div>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><?php echo trans('texts.address'); ?></h3>
          </div>
            <div class="panel-body" id="billing_address">

			<?php echo Former::text('address1'); ?>

			<?php echo Former::text('address2'); ?>

			<?php echo Former::text('city'); ?>

			<?php echo Former::text('state'); ?>


			<?php echo Former::text('postal_code')
					->oninput(config('ninja.google_maps_api_key') ? 'lookupPostalCode()' : ''); ?>

			<?php echo Former::select('country_id')->addOption('','')
				->autocomplete('off')
				->fromQuery($countries, 'name', 'id'); ?>


        </div>
        </div>
		</div>
		<div class="col-md-6">


        <div class="panel panel-default" style="min-height: 380px">
          <div class="panel-heading">
            <h3 class="panel-title"><?php echo trans('texts.contacts'); ?></h3>
          </div>
            <div class="panel-body">

			<div data-bind='template: { foreach: vendor_contacts,
		                            beforeRemove: hideContact,
		                            afterAdd: showContact }'>
				<?php echo Former::hidden('public_id')->data_bind("value: public_id, valueUpdate: 'afterkeydown',
                        attr: {name: 'vendor_contacts[' + \$index() + '][public_id]'}"); ?>

				<?php echo Former::text('first_name')->data_bind("value: first_name, valueUpdate: 'afterkeydown',
                        attr: {name: 'vendor_contacts[' + \$index() + '][first_name]'}"); ?>

				<?php echo Former::text('last_name')->data_bind("value: last_name, valueUpdate: 'afterkeydown',
                        attr: {name: 'vendor_contacts[' + \$index() + '][last_name]'}"); ?>

				<?php echo Former::text('email')->data_bind("value: email, valueUpdate: 'afterkeydown',
                        attr: {name: 'vendor_contacts[' + \$index() + '][email]', id:'email'+\$index()}"); ?>

				<?php echo Former::text('phone')->data_bind("value: phone, valueUpdate: 'afterkeydown',
                        attr: {name: 'vendor_contacts[' + \$index() + '][phone]'}"); ?>


				<div class="form-group">
					<div class="col-lg-8 col-lg-offset-4 bold">
						<span class="redlink bold" data-bind="visible: $parent.vendor_contacts().length > 1">
							<?php echo link_to('#', trans('texts.remove_contact').' -', array('data-bind'=>'click: $parent.removeContact')); ?>

						</span>
						<span data-bind="visible: $index() === ($parent.vendor_contacts().length - 1)" class="pull-right greenlink bold">
							<?php echo link_to('#', trans('texts.add_contact').' +', array('onclick'=>'return addContact()')); ?>

						</span>
					</div>
				</div>
			</div>
            </div>
            </div>


        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><?php echo trans('texts.additional_info'); ?></h3>
          </div>
            <div class="panel-body">

            <?php echo Former::select('currency_id')->addOption('','')
                ->placeholder($account->currency ? $account->currency->getTranslatedName() : '')
                ->fromQuery($currencies, 'name', 'id'); ?>

			<?php echo Former::textarea('private_notes')->rows(6); ?>


            </div>
            </div>

		</div>
	</div>


	<?php echo Former::hidden('data')->data_bind("value: ko.toJSON(model)"); ?>


	<script type="text/javascript">

	$(function() {
		$('#country_id').combobox();
	});

	function VendorContactModel(data) {
		var self = this;
		self.public_id = ko.observable('');
		self.first_name = ko.observable('');
		self.last_name = ko.observable('');
		self.email = ko.observable('');
		self.phone = ko.observable('');

		if (data) {
			ko.mapping.fromJS(data, {}, this);
		}
	}

	function VendorModel(data) {
		var self = this;

        self.vendor_contacts = ko.observableArray();

		self.mapping = {
		    'vendor_contacts': {
		    	create: function(options) {
		    		return new VendorContactModel(options.data);
		    	}
		    }
		}

		if (data) {
			ko.mapping.fromJS(data, self.mapping, this);
		} else {
			self.vendor_contacts.push(new VendorContactModel());
		}

		self.placeholderName = ko.computed(function() {
			if (self.vendor_contacts().length == 0) return '';
			var contact = self.vendor_contacts()[0];
			if (contact.first_name() || contact.last_name()) {
				return (contact.first_name() || '') + ' ' + (contact.last_name() || '');
			} else {
				return contact.email();
			}
		});
	}

    <?php if($data): ?>
        window.model = new VendorModel(<?php echo $data; ?>);
    <?php else: ?>
	    window.model = new VendorModel(<?php echo $vendor; ?>);
    <?php endif; ?>

	model.showContact = function(elem) { if (elem.nodeType === 1) $(elem).hide().slideDown() }
	model.hideContact = function(elem) { if (elem.nodeType === 1) $(elem).slideUp(function() { $(elem).remove(); }) }


	ko.applyBindings(model);

	function addContact() {
		model.vendor_contacts.push(new VendorContactModel());
		return false;
	}

	model.removeContact = function() {
		model.vendor_contacts.remove(this);
	}


	</script>
	<?php if(Auth::user()->canCreateOrEdit(ENTITY_VENDOR)): ?>
	<center class="buttons">
    	<?php echo Button::normal(trans('texts.cancel'))->large()->asLinkTo(URL::to('/vendors/' . ($vendor ? $vendor->public_id : '')))->appendIcon(Icon::create('remove-circle')); ?>

        <?php echo Button::success(trans('texts.save'))->submit()->large()->appendIcon(Icon::create('floppy-disk')); ?>

	</center>
	<?php endif; ?>
	<?php echo Former::close(); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>