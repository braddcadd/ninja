<?php $__env->startSection('content'); ?>

	<?php echo Former::open($url)
            ->addClass('col-lg-10 col-lg-offset-1 warn-on-exit main-form')
			->autocomplete('off')
            ->method($method)
            ->rules([
                'name' => 'required',
				'client_id' => 'required',
            ]); ?>


    <?php if($project): ?>
        <?php echo Former::populate($project); ?>

		<?php echo Former::populateField('task_rate', floatval($project->task_rate) ? Utils::roundSignificant($project->task_rate) : ''); ?>

		<?php echo Former::populateField('budgeted_hours', floatval($project->budgeted_hours) ? $project->budgeted_hours : ''); ?>

    <?php endif; ?>

    <span style="display:none">
        <?php echo Former::text('public_id'); ?>

		<?php echo Former::text('action'); ?>

    </span>

	<div class="row">
        <div class="col-lg-10 col-lg-offset-1">

            <div class="panel panel-default">
            <div class="panel-body">

				<?php if($project): ?>
					<?php echo Former::plaintext('client_name')
							->value($project->client ? $project->client->present()->link : ''); ?>

				<?php else: ?>
					<?php echo Former::select('client_id')
							->addOption('', '')
							->label(trans('texts.client'))
							->addGroupClass('client-select'); ?>

				<?php endif; ?>

                <?php echo Former::text('name'); ?>


				<?php echo Former::text('due_date')
	                        ->data_date_format(Session::get(SESSION_DATE_PICKER_FORMAT, DEFAULT_DATE_PICKER_FORMAT))
	                        ->addGroupClass('due_date')
	                        ->append('<i class="glyphicon glyphicon-calendar"></i>'); ?>


				<?php echo Former::text('budgeted_hours'); ?>


				<?php echo Former::text('task_rate')
						->placeholder($project && $project->client->task_rate ? $project->client->present()->taskRate : $account->present()->taskRate)
				 		->help('task_rate_help'); ?>


				<?php echo $__env->make('partials/custom_fields', ['entityType' => ENTITY_PROJECT], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

				<?php echo Former::textarea('private_notes')->rows(4); ?>


            </div>
            </div>

        </div>
    </div>

	<?php if(Auth::user()->canCreateOrEdit(ENTITY_PROJECT)): ?>
	<center class="buttons">
        <?php echo Button::normal(trans('texts.cancel'))->large()->asLinkTo(HTMLUtils::previousUrl('/projects'))->appendIcon(Icon::create('remove-circle')); ?>

        <?php echo Button::success(trans('texts.save'))->submit()->large()->appendIcon(Icon::create('floppy-disk')); ?>


		<?php if(false && $project && Auth::user()->can('createEntity', ENTITY_TASK)): ?>
			<?php echo DropdownButton::normal(trans('texts.more_actions'))
				  ->withContents([
					  [
						  'url' => url('/tasks/create/' . ($project->client ? $project->client->public_id : '0'). '/' . $project->public_id),
						  'label' => trans('texts.new_task'),
					  ],
					  [
						  'url' => 'javascript:submitAction("invoice")',
						  'label' => trans('texts.invoice_project'),
					  ],
				  	  \DropdownButton::DIVIDER,
					  [
						  'url' => 'javascript:submitAction("archive")',
						  'label' => trans('texts.archive_project')
					  ],
					  [
						  'url' => 'javascript:onDeleteClick()',
						  'label' => trans('texts.delete_project')
					  ],
				  ])
				  ->large(); ?>

		<?php endif; ?>
	</center>
	<?php endif; ?>

	<?php echo Former::close(); ?>


    <script>

		var clients = <?php echo $clients; ?>;
		var clientMap = {};

		function submitAction(action) {
            $('#action').val(action);
            $('.main-form').submit();
        }

		function onDeleteClick() {
            sweetConfirm(function() {
                submitAction('delete');
            });
        }

        $(function() {
			var $clientSelect = $('select#client_id');
            for (var i=0; i<clients.length; i++) {
                var client = clients[i];
								clientMap[client.public_id] = client;
                var clientName = getClientDisplayName(client);
                if (!clientName) {
                    continue;
                }
                $clientSelect.append(new Option(clientName, client.public_id));
            }
			<?php if($clientPublicId): ?>
				$clientSelect.val(<?php echo e($clientPublicId); ?>);
			<?php endif; ?>

			$clientSelect.combobox({highlighter: comboboxHighlighter}).change(function() {
				var client = clientMap[$('#client_id').val()];
				if (client && parseFloat(client.task_rate)) {
					var rate = client.task_rate;
				} else {
					var rate = <?php echo e($account->present()->taskRate ?: 0); ?>;
				}
				$('#task_rate').attr('placeholder', roundSignificant(rate, true));
			});

			$('#due_date').datepicker('update', '<?php echo e($project ? Utils::fromSqlDate($project->due_date) : ''); ?>');

			<?php if($clientPublicId): ?>
				$('#name').focus();
			<?php else: ?>
				$('.client-select input.form-control').focus();
			<?php endif; ?>
        });

    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>