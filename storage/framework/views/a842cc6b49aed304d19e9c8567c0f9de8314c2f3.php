<?php $__env->startSection('content'); ?>


	<?php echo Former::open($url)->addClass('col-lg-10 col-lg-offset-1 warn-on-exit')->method($method)->rules(array(
			'client_id' => 'required',
  		'amount' => 'required',
	)); ?>


	<?php if($credit): ?>
      <?php echo Former::populate($credit); ?>

      <div style="display:none">
          <?php echo Former::text('public_id'); ?>

      </div>
	<?php endif; ?>

	<div class="row">
        <div class="col-lg-10 col-lg-offset-1">

            <div class="panel panel-default">
            <div class="panel-body">

			<?php if($credit): ?>
				<?php echo Former::plaintext()->label('client')->value($client->present()->link); ?>

			<?php else: ?>
				<?php echo Former::select('client_id')
						->label('client')
						->addOption('', '')
						->addGroupClass('client-select'); ?>

			<?php endif; ?>

			<?php echo Former::text('amount'); ?>


			<?php if($credit): ?>
				<?php echo Former::text('balance'); ?>

			<?php endif; ?>

			<?php echo Former::text('credit_date')
                        ->data_date_format(Session::get(SESSION_DATE_PICKER_FORMAT, DEFAULT_DATE_PICKER_FORMAT))
                        ->addGroupClass('credit_date')
                        ->append('<i class="glyphicon glyphicon-calendar"></i>'); ?>


			<?php echo Former::textarea('public_notes')->rows(4); ?>

			<?php echo Former::textarea('private_notes')->rows(4); ?>


            </div>
            </div>

        </div>
    </div>

	<?php if(Auth::user()->canCreateOrEdit(ENTITY_CREDIT, $credit)): ?>
	<center class="buttons">
        <?php echo Button::normal(trans('texts.cancel'))->large()->asLinkTo(HTMLUtils::previousUrl('/credits'))->appendIcon(Icon::create('remove-circle')); ?>

        <?php echo Button::success(trans('texts.save'))->submit()->large()->appendIcon(Icon::create('floppy-disk')); ?>

	</center>
	<?php endif; ?>
	<?php echo Former::close(); ?>


	<script type="text/javascript">


	var clients = <?php echo $clients ?: 'false'; ?>;

	$(function() {

		<?php if( ! $credit): ?>
			var $clientSelect = $('select#client_id');
			for (var i=0; i<clients.length; i++) {
				var client = clients[i];
	            var clientName = getClientDisplayName(client);
	            if (!clientName) {
	                continue;
	            }
				$clientSelect.append(new Option(clientName, client.public_id));
			}

			if (<?php echo e($clientPublicId ? 'true' : 'false'); ?>) {
				$clientSelect.val(<?php echo e($clientPublicId); ?>);
			}

			$clientSelect.combobox({highlighter: comboboxHighlighter});
		<?php endif; ?>

		$('#currency_id').combobox();
		$('#credit_date').datepicker('update', '<?php echo e($credit ? $credit->credit_date : 'new Date()'); ?>');

        <?php if(!$clientPublicId): ?>
            $('.client-select input.form-control').focus();
        <?php else: ?>
            $('#amount').focus();
        <?php endif; ?>

        $('.credit_date .input-group-addon').click(function() {
            toggleDatePicker('credit_date');
        });
	});

	</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>