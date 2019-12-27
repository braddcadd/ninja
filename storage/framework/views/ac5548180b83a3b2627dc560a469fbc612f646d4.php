<?php $__env->startSection('head_css'); ?>
	##parent-placeholder-65e7fa855b4f81a209a50c6e440870f25d0240e1##

	<link href="<?php echo e(asset('css/lightbox.css')); ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo e(asset('css/quill.snow.css')); ?>" rel="stylesheet" type="text/css"/>

	<style type="text/css">

		.tt-menu {
			width: 350px !important;
		}

        select.tax-select {
            width: 50%;
            float: left;
        }

        #scrollable-dropdown-menu .tt-menu {
            max-height: 150px;
            width: 300px;
            overflow-y: auto;
            overflow-x: hidden;
        }

		.signature-wrapper .tooltip-inner {
			width: 600px;
			max-width: 600px;
			padding: 20px;
		}

		.subtotals-table {
			min-width: 340px;
		}

		.subtotals-table tr {
			border-bottom: solid #CCCCCC 1px;
		}

		.subtotals-table td {
			padding-top: 20px;
			padding-bottom: 12px;
		}

		.subtotals-table input {
			float: right;
			text-align: right;
			max-width: 150px;
		}

    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('head'); ?>
	##parent-placeholder-1a954628a960aaef81d7b2d4521929579f3541e6##

    <?php echo $__env->make('money_script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <?php $__currentLoopData = $account->getFontFolders(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $font): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <script src="<?php echo e(asset('js/vfs_fonts/'.$font.'.js')); ?>" type="text/javascript"></script>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	<script src="<?php echo e(asset('pdf.built.js')); ?>?no_cache=<?php echo e(NINJA_VERSION); ?>" type="text/javascript"></script>
    <script src="<?php echo e(asset('js/lightbox.min.js')); ?>" type="text/javascript"></script>
	<script src="<?php echo e(asset('js/quill.min.js')); ?>" type="text/javascript"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php if($errors->first('invoice_items')): ?>
        <div class="alert alert-danger"><?php echo e(trans($errors->first('invoice_items'))); ?></div>
    <?php endif; ?>

	<?php if($invoice->id): ?>
		<ol class="breadcrumb">
		<?php if($invoice->is_recurring): ?>
            <li><?php echo link_to(($entityType == ENTITY_QUOTE ? 'recurring_quotes' : 'recurring_invoices'), trans('texts.' . ($entityType == ENTITY_QUOTE ? 'recurring_quotes' : 'recurring_invoices'))); ?></li>
		<?php else: ?>
			<li><?php echo link_to(($entityType == ENTITY_QUOTE ? 'quotes' : 'invoices'), trans('texts.' . ($entityType == ENTITY_QUOTE ? 'quotes' : 'invoices'))); ?></li>
			<li class="active"><?php echo e($invoice->invoice_number); ?></li>
		<?php endif; ?>
		<?php if($invoice->is_recurring && $invoice->isSent()): ?>
			<?php if(! $invoice->last_sent_date || $invoice->last_sent_date == '0000-00-00'): ?>
				<?php echo $invoice->present()->statusLabel(trans('texts.pending')); ?>

			<?php elseif($invoice->end_date && Carbon::parse(Utils::toSqlDate($invoice->end_date))->isPast()): ?>
				<?php echo $invoice->present()->statusLabel(trans('texts.status_completed')); ?>

			<?php else: ?>
				<?php echo $invoice->present()->statusLabel(trans('texts.active')); ?>

			<?php endif; ?>
		<?php else: ?>
			<?php echo $invoice->present()->statusLabel; ?>

		<?php endif; ?>
		</ol>
	<?php endif; ?>

	<?php echo Former::open($url)
            ->method($method)
            ->addClass('warn-on-exit main-form search')
            ->autocomplete('off')
            ->name('lastpass-disable-search') // 'search' prevents LastPass auto-fill http://stackoverflow.com/a/30921628/497368
            ->onsubmit('return onFormSubmit(event)')
            ->rules(array(
        		'client' => 'required',
                'invoice_number' => 'required',
                'invoice_date' => 'required',
        		'product_key' => 'max:255'
        	)); ?>


    <?php echo $__env->make('partials.autocomplete_fix', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

	<input type="submit" style="display:none" name="submitButton" id="submitButton">

	<div data-bind="with: invoice">
    <div class="panel panel-default">
    <div class="panel-body">

    <div class="row" style="min-height:195px" onkeypress="formEnterClick(event)">
    	<div class="col-md-4" id="col_1">

    		<?php if($invoice->id || $data): ?>
				<div class="form-group">
					<label for="client" class="control-label col-lg-4 col-sm-4"><b><?php echo e(trans('texts.client')); ?></b></label>
					<div class="col-lg-8 col-sm-8">
                        <h4>
                            <span data-bind="text: getClientDisplayName(ko.toJS(client()))"></span>
                            <?php if($invoice->client->is_deleted): ?>
                                &nbsp;&nbsp;<div class="label label-danger"><?php echo e(trans('texts.deleted')); ?></div>
                            <?php endif; ?>
                        </h4>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', $invoice->client)): ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit', $invoice->client)): ?>
                                <a id="editClientLink" class="pointer" data-bind="click: $root.showClientForm"><?php echo e(trans('texts.edit_client')); ?></a> |
                            <?php endif; ?>
                            <?php echo link_to('/clients/'.$invoice->client->public_id, trans('texts.view_client'), ['target' => '_blank']); ?>

                        <?php endif; ?>
					</div>
				</div>
				<div style="display:none">
    		<?php endif; ?>

            <?php echo Former::select('client')
					->addOption('', '')
					->data_bind("dropdown: client, dropdownOptions: {highlighter: comboboxHighlighter}")
					->addClass('client-input')
					->addGroupClass('client_select closer-row'); ?>


			<div class="form-group" style="margin-bottom: 8px">
				<div class="col-lg-8 col-sm-8 col-lg-offset-4 col-sm-offset-4">
					<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', ENTITY_CLIENT)): ?>
					<a id="createClientLink" class="pointer" data-bind="click: $root.showClientForm, html: $root.clientLinkText"></a>
					<?php endif; ?>
                    <span data-bind="visible: $root.invoice().client().public_id() > 0" style="display:none">|
                        <a data-bind="attr: {href: '<?php echo e(url('/clients')); ?>/' + $root.invoice().client().public_id()}" target="_blank"><?php echo e(trans('texts.view_client')); ?></a>
                    </span>
				</div>
			</div>

			<?php if($invoice->id || $data): ?>
				</div>
			<?php endif; ?>

			<div data-bind="with: client" class="invoice-contact">
				<div style="display:none" class="form-group" data-bind="visible: contacts().length > 0, foreach: contacts">
					<div class="col-lg-8 col-lg-offset-4 col-sm-offset-4">
						<label class="checkbox" data-bind="attr: {for: $index() + '_check'}, visible: email.display" onclick="refreshPDF(true)">
                            <input type="hidden" value="0" data-bind="attr: {name: 'client[contacts][' + $index() + '][send_invoice]'}">
							<input type="checkbox" value="1" data-bind="visible: email() || first_name() || last_name(), checked: send_invoice, attr: {id: $index() + '_check', name: 'client[contacts][' + $index() + '][send_invoice]'}">
							<span data-bind="visible: first_name || last_name">
								<span data-bind="text: (first_name() || '') + ' ' + (last_name() || '')"></span>
								<br/>
							</span>
							<span data-bind="visible: email">
								<span data-bind="text: email"></span>
								<br/>
							</span>
                        </label>
                        <?php if( ! $invoice->is_deleted && ! $invoice->client->is_deleted): ?>
                        <span data-bind="visible: !$root.invoice().is_recurring()">
                            <span data-bind="html: $data.view_as_recipient"></span>&nbsp;&nbsp;
                            <?php if(Utils::isConfirmed()): ?>
	                            <span style="vertical-align:text-top;color:red" class="fa fa-exclamation-triangle"
	                                    data-bind="visible: $data.email_error, tooltip: {title: $data.email_error}"></span>
	                            <span style="vertical-align:text-top;padding-top:2px" class="fa fa-info-circle"
	                                    data-bind="visible: $data.invitation_status, tooltip: {title: $data.invitation_status, html: true},
	                                    style: {color: $data.info_color}"></span>
								<span class="signature-wrapper">&nbsp;
								<span style="vertical-align:text-top;color:#888" class="fa fa-user"
	                                    data-bind="visible: $data.invitation_signature_svg, tooltip: {title: $data.invitation_signature_svg, html: true}"></span>
								</span>
                            <?php endif; ?>
                        </span>
                        <?php endif; ?>
					</div>
				</div>
			</div>

		</div>
		<div class="col-md-4" id="col_2">
			<div data-bind="visible: !is_recurring()">
				<?php echo Former::text('invoice_date')->data_bind("datePicker: invoice_date, valueUpdate: 'afterkeydown'")->label($account->getLabel("{$entityType}_date"))
							->data_date_format(Session::get(SESSION_DATE_PICKER_FORMAT, DEFAULT_DATE_PICKER_FORMAT))->appendIcon('calendar')->addGroupClass('invoice_date'); ?>

				<?php echo Former::text('due_date')->data_bind("datePicker: due_date, valueUpdate: 'afterkeydown'")->label($account->getLabel($invoice->getDueDateLabel()))
							->placeholder($invoice->id || $invoice->isQuote() ? ' ' : $account->present()->dueDatePlaceholder())
							->data_date_format(Session::get(SESSION_DATE_PICKER_FORMAT, DEFAULT_DATE_PICKER_FORMAT))->appendIcon('calendar')->addGroupClass('due_date'); ?>


				<div class="form-group partial">
					<label for="partial" class="control-label col-lg-4 col-sm-4"><?php echo e(trans('texts.partial')); ?></label>
					<div class="col-lg-8 col-sm-8 no-gutter">
						<div data-bind="css: {'col-md-4': showPartialDueDate(), 'col-md-12': ! showPartialDueDate()}" class="partial">
							<?php echo Former::text('partial')->data_bind("value: partial, valueUpdate: 'afterkeydown'")
										->onkeyup('onPartialChange()')
										->raw(); ?>

						</div>
						<div class="col-lg-8 no-gap">
							<?php echo Former::text('partial_due_date')
										->placeholder('due_date')
										->style('display: none')
										->data_bind("datePicker: partial_due_date, valueUpdate: 'afterkeydown', visible: showPartialDueDate")
										->data_date_format(Session::get(SESSION_DATE_PICKER_FORMAT, DEFAULT_DATE_PICKER_FORMAT))
										->raw(); ?>

						</div>
					</div>
				</div>
			</div>
			<?php if(isset($frequencies)): ?>
			<div data-bind="visible: is_recurring" style="display: none">
				<?php echo Former::select('frequency_id')->label('frequency')->options($frequencies)->data_bind("value: frequency_id")
                        ->appendIcon('question-sign')->addGroupClass('frequency_id')->onchange('onFrequencyChange()'); ?>

				<?php echo Former::text('start_date')->data_bind("datePicker: start_date, valueUpdate: 'afterkeydown'")
							->data_date_format(Session::get(SESSION_DATE_PICKER_FORMAT, DEFAULT_DATE_PICKER_FORMAT))->appendIcon('calendar')->addGroupClass('start_date'); ?>

				<?php echo Former::text('end_date')->data_bind("datePicker: end_date, valueUpdate: 'afterkeydown'")
							->data_date_format(Session::get(SESSION_DATE_PICKER_FORMAT, DEFAULT_DATE_PICKER_FORMAT))->appendIcon('calendar')->addGroupClass('end_date'); ?>

                <?php echo Former::select('recurring_due_date')->label($account->getLabel($invoice->getDueDateLabel()))->options($recurringDueDates)->data_bind("value: recurring_due_date")->appendIcon('question-sign')->addGroupClass('recurring_due_date'); ?>

			</div>
			<?php endif; ?>

            <?php if($account->customLabel('invoice_text1')): ?>
				<?php echo $__env->make('partials.custom_field', [
					'field' => 'custom_text_value1',
					'label' => $account->customLabel('invoice_text1'),
					'databind' => "value: custom_text_value1, valueUpdate: 'afterkeydown'",
				], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php endif; ?>
		</div>

		<div class="col-md-4" id="col_2">
            <span data-bind="visible: !is_recurring()">
            <?php echo Former::text('invoice_number')
                        ->label(trans("texts.{$entityType}_number_short"))
                        ->onchange('checkInvoiceNumber()')
                        ->addGroupClass('invoice-number')
                        ->data_bind("value: invoice_number, valueUpdate: 'afterkeydown'"); ?>

            </span>
            <?php if($entityType == ENTITY_INVOICE): ?>
            <span data-bind="visible: is_recurring()" style="display: none">
                <div data-bind="visible: !(auto_bill() == <?php echo e(AUTO_BILL_OPT_IN); ?> &amp;&amp; client_enable_auto_bill()) &amp;&amp; !(auto_bill() == <?php echo e(AUTO_BILL_OPT_OUT); ?> &amp;&amp; !client_enable_auto_bill())" style="display: none">
                <?php echo Former::select('auto_bill')
                        ->data_bind("value: auto_bill, valueUpdate: 'afterkeydown', event:{change:function(){if(auto_bill()==".AUTO_BILL_OPT_IN.")client_enable_auto_bill(0);if(auto_bill()==".AUTO_BILL_OPT_OUT.")client_enable_auto_bill(1)}}")
                        ->options([
                            AUTO_BILL_OFF => trans('texts.off'),
                            AUTO_BILL_OPT_IN => trans('texts.opt_in'),
                            AUTO_BILL_OPT_OUT => trans('texts.opt_out'),
                            AUTO_BILL_ALWAYS => trans('texts.always'),
                        ]); ?>

                </div>
                <input type="hidden" name="client_enable_auto_bill" data-bind="attr: { value: client_enable_auto_bill() }" />
                <div class="form-group" data-bind="visible: auto_bill() == <?php echo e(AUTO_BILL_OPT_IN); ?> &amp;&amp; client_enable_auto_bill()">
                    <div class="col-sm-4 control-label"><?php echo e(trans('texts.auto_bill')); ?></div>
                    <div class="col-sm-8" style="padding-top:10px;padding-bottom:9px">
                        <?php echo e(trans('texts.opted_in')); ?> - <a href="#" data-bind="click:function(){client_enable_auto_bill(false)}">(<?php echo e(trans('texts.disable')); ?>)</a>
                    </div>
                </div>
                <div class="form-group" data-bind="visible: auto_bill() == <?php echo e(AUTO_BILL_OPT_OUT); ?> &amp;&amp; !client_enable_auto_bill()">
                    <div class="col-sm-4 control-label"><?php echo e(trans('texts.auto_bill')); ?></div>
                    <div class="col-sm-8" style="padding-top:10px;padding-bottom:9px">
                        <?php echo e(trans('texts.opted_out')); ?> - <a href="#" data-bind="click:function(){client_enable_auto_bill(true)}">(<?php echo e(trans('texts.enable')); ?>)</a>
                    </div>
                </div>
            </span>
            <?php endif; ?>
			<?php echo Former::text('po_number')->label($account->getLabel('po_number', 'po_number_short'))->data_bind("value: po_number, valueUpdate: 'afterkeydown'"); ?>

			<?php echo Former::text('discount')->data_bind("value: discount, valueUpdate: 'afterkeydown'")
					->addGroupClass('no-padding-or-border')->type('number')->min('0')->step('any')->append(
						Former::select('is_amount_discount')
							->addOption(trans('texts.discount_percent'), '0')
							->addOption(trans('texts.discount_amount'), '1')
							->data_bind("value: is_amount_discount, event:{ change: isAmountDiscountChanged}")
							->raw()
			); ?>


            <?php if($account->customLabel('invoice_text2')): ?>
				<?php echo $__env->make('partials.custom_field', [
					'field' => 'custom_text_value2',
					'label' => $account->customLabel('invoice_text2'),
					'databind' => "value: custom_text_value2, valueUpdate: 'afterkeydown'",
				], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php endif; ?>

            <div class="form-group" style="margin-bottom: 8px">
                <div class="col-lg-8 col-sm-8 col-sm-offset-4 smaller" style="padding-top: 10px;">
                	<?php if($invoice->recurring_invoice_id && $invoice->recurring_invoice): ?>
                        <?php echo trans('texts.created_by_invoice', ['invoice' => link_to('/invoices/'.$invoice->recurring_invoice->public_id, trans('texts.recurring_invoice'))]); ?> <p/>
    				<?php elseif($invoice->id): ?>
                        <?php if(isset($lastSent) && $lastSent): ?>
                            <?php echo trans('texts.last_sent_on', ['date' => link_to('/invoices/'.$lastSent->public_id, $invoice->last_sent_date, ['id' => 'lastSent'])]); ?> <p/>
                        <?php endif; ?>
                        <?php if($invoice->is_recurring && $invoice->start_date && $invoice->is_public): ?>
							<?php if($sendNextDate = $invoice->getNextSendDate()): ?>
                           		<?php echo trans('texts.next_send_on', ['date' => '<span data-bind="tooltip: {title: \''.$invoice->getPrettySchedule().'\', html: true}">' . $account->formatDate($sendNextDate).
                                	'<span class="glyphicon glyphicon-info-sign" style="padding-left:10px;color:#B1B5BA"></span></span>']); ?>

							<?php endif; ?>
                            <?php if($invoice->getDueDate()): ?>
                                <br>
                                <?php echo trans('texts.next_due_on', ['date' => '<span>'.$account->formatDate($invoice->getDueDate($invoice->getNextSendDate())).'</span>']); ?>

                            <?php endif; ?>
							<p/>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
		</div>
	</div>

	<div class="table-responsive" style="padding-top:4px;">

		<?php echo $__env->make('invoices.edit_table', ['isTasks' => false], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		<?php if($account->isModuleEnabled(ENTITY_TASK) && ($invoice->has_tasks || ! empty($tasks))): ?>
			<?php echo $__env->make('invoices.edit_table', ['isTasks' => true], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		<?php endif; ?>

		<table class="pull-right subtotals-table" style="margin-right:40px; margin-top:0px;">
			<tr>
				<td colspan="2"><?php echo e(trans('texts.subtotal')); ?></td>
				<td style="text-align: right"><span data-bind="text: totals.subtotal"/></td>
			</tr>

			<tr style="display:none" data-bind="visible: discount() != 0">
				<td colspan="2"><?php echo e(trans('texts.discount')); ?></td>
				<td style="text-align: right"><span data-bind="text: totals.discounted"/></td>
			</tr>

			<?php if($account->customLabel('invoice1') && $invoice->custom_taxes1): ?>
				<tr>
					<td colspan="2"><?php echo e($account->customLabel('invoice1') ?: trans('texts.surcharge')); ?></td>
					<td><input name="custom_value1" class="form-control" data-bind="value: custom_value1, valueUpdate: 'afterkeydown'"/></td>
				</tr>
			<?php endif; ?>
            <?php if($account->customLabel('invoice2') && $invoice->custom_taxes2): ?>
				<tr>
					<td colspan="2"><?php echo e($account->customLabel('invoice2') ?: trans('texts.surcharge')); ?></td>
					<td><input name="custom_value2" class="form-control" data-bind="value: custom_value2, valueUpdate: 'afterkeydown'"/></td>
				</tr>
			<?php endif; ?>

            <tr style="display:none" data-bind="visible: $root.invoice_item_taxes.show &amp;&amp; totals.hasItemTaxes">
                <td><?php echo e(trans('texts.tax')); ?>&nbsp;&nbsp;</td>
                <td style="min-width:120px"><span data-bind="html: totals.itemTaxRates"/></td>
                <td style="text-align: right"><span data-bind="html: totals.itemTaxAmounts"/></td>
            </tr>

			<tr style="display:none" data-bind="visible: $root.invoice_taxes.show">
				<td><?php echo e(trans('texts.tax')); ?>&nbsp;&nbsp;</td>
				<td style="min-width:120px">
                    <?php echo Former::select('')
                            ->id('taxRateSelect1')
                            ->addOption('', '')
                            ->options($taxRateOptions)
                            ->addClass($account->enable_second_tax_rate ? 'tax-select' : '')
                            ->data_bind('value: tax1, event:{change:onTax1Change}')
                            ->raw(); ?>

                    <input type="text" name="tax_name1" data-bind="value: tax_name1" style="display:none">
                    <input type="text" name="tax_rate1" data-bind="value: tax_rate1" style="display:none">
                    <div data-bind="visible: $root.invoice().account.enable_second_tax_rate == '1'">
                    <?php echo Former::select('')
                            ->addOption('', '')
                            ->options($taxRateOptions)
                            ->addClass('tax-select')
                            ->data_bind('value: tax2, event:{change:onTax2Change}')
                            ->raw(); ?>

                    </div>
                    <input type="text" name="tax_name2" data-bind="value: tax_name2" style="display:none">
                    <input type="text" name="tax_rate2" data-bind="value: tax_rate2" style="display:none">
                </td>
				<td style="text-align: right"><span data-bind="text: totals.taxAmount"/></td>
			</tr>

            <?php if($account->customLabel('invoice1') && !$invoice->custom_taxes1): ?>
				<tr>
					<td colspan="2"><?php echo e($account->customLabel('invoice1') ?: trans('texts.surcharge')); ?></td>
					<td><input name="custom_value1" class="form-control" data-bind="value: custom_value1, valueUpdate: 'afterkeydown'"/></td>
				</tr>
			<?php endif; ?>

            <?php if($account->customLabel('invoice2') && !$invoice->custom_taxes2): ?>
				<tr>
					<td colspan="2"><?php echo e($account->customLabel('invoice2') ?: trans('texts.surcharge')); ?></td>
					<td><input name="custom_value2" class="form-control" data-bind="value: custom_value2, valueUpdate: 'afterkeydown'"/></td>
				</tr>
			<?php endif; ?>

			<?php if(!$account->hide_paid_to_date): ?>
				<tr>
					<td colspan="2"><?php echo e(trans('texts.paid_to_date')); ?></td>
					<td style="text-align: right" data-bind="text: totals.paidToDate"></td>
				</tr>
			<?php endif; ?>

			<tr data-bind="style: { 'font-weight': partial() ? 'normal' : 'bold', 'font-size': partial() ? '1em' : '1.05em' }" style="font-size:1.05em;font-weight:bold;">
				<td class="hide-border" data-bind="css: {'hide-border': !partial()}" colspan="2"><?php echo e($entityType == ENTITY_INVOICE ? $invoiceLabels['balance_due'] : trans('texts.total')); ?></td>
				<td class="hide-border" data-bind="css: {'hide-border': !partial()}" style="text-align: right"><span data-bind="text: totals.total"></span></td>
			</tr>

			<tr style="font-size:1.05em; display:none; font-weight:bold" data-bind="visible: partial">
				<td class="hide-border" colspan="2"><?php echo e($invoiceLabels['partial_due']); ?></td>
				<td class="hide-border" style="text-align: right"><span data-bind="text: totals.partial"></span></td>
			</tr>
		</table>


		<div role="tabpanel" class="pull-left" style="margin-left:40px; margin-top:30px;">

			<ul class="nav nav-tabs" role="tablist" style="border: none">
				<li role="presentation" class="active"><a href="#public_notes" aria-controls="notes" role="tab" data-toggle="tab"><?php echo e(trans('texts.public_notes')); ?></a></li>
				<li role="presentation"><a href="#private_notes" aria-controls="terms" role="tab" data-toggle="tab"><?php echo e(trans("texts.private_notes")); ?></a></li>
				<li role="presentation"><a href="#terms" aria-controls="terms" role="tab" data-toggle="tab"><?php echo e(trans("texts.terms")); ?></a></li>
				<li role="presentation"><a href="#footer" aria-controls="footer" role="tab" data-toggle="tab"><?php echo e(trans("texts.footer")); ?></a></li>
				<?php if($account->hasFeature(FEATURE_DOCUMENTS)): ?>
					<li role="presentation"><a href="#attached-documents" aria-controls="attached-documents" role="tab" data-toggle="tab">
						<?php echo e(trans("texts.documents")); ?>

						<?php if($count = ($invoice->countDocuments($expenses))): ?>
							(<?php echo e($count); ?>)
						<?php endif; ?>
					</a></li>
				<?php endif; ?>
			</ul>

			<?php echo e(Former::setOption('TwitterBootstrap3.labelWidths.large', 0)); ?>

			<?php echo e(Former::setOption('TwitterBootstrap3.labelWidths.small', 0)); ?>


			<div class="tab-content" style="padding-right:12px;max-width:600px;">
				<div role="tabpanel" class="tab-pane active" id="public_notes" style="padding-bottom:44px;">
					<?php echo Former::textarea('public_notes')
							->data_bind("value: public_notes, valueUpdate: 'afterkeydown'")
							->label(null)->style('width: 100%')->rows(4)->label(null); ?>

				</div>
				<div role="tabpanel" class="tab-pane" id="private_notes" style="padding-bottom:44px">
					<?php echo Former::textarea('private_notes')
							->data_bind("value: private_notes, valueUpdate: 'afterkeydown'")
							->label(null)->style('width: 100%')->rows(4); ?>

				</div>
				<div role="tabpanel" class="tab-pane" id="terms">
					<?php echo Former::textarea('terms')
							->data_bind("value:terms, placeholder: terms_placeholder, valueUpdate: 'afterkeydown'")
							->label(false)->style('width: 100%')->rows(4)
							->help('<div class="checkbox">
										<label>
											<input name="set_default_terms" type="checkbox" style="width: 16px" data-bind="checked: set_default_terms"/>'.trans('texts.save_as_default_terms').'
										</label>
										<div class="pull-right" data-bind="visible: showResetTerms()">
											<a href="#" onclick="return resetTerms()" title="'. trans('texts.reset_terms_help') .'">' . trans("texts.reset_terms") . '</a>
										</div>
									</div>'); ?>

				</div>
				<div role="tabpanel" class="tab-pane" id="footer">
					<?php echo Former::textarea('invoice_footer')
							->data_bind("value:invoice_footer, placeholder: footer_placeholder, valueUpdate: 'afterkeydown'")
							->label(false)->style('width: 100%')->rows(4)
							->help('<div class="checkbox">
										<label>
											<input name="set_default_footer" type="checkbox" style="width: 16px" data-bind="checked: set_default_footer"/>'.trans('texts.save_as_default_footer').'
										</label>
										<div class="pull-right" data-bind="visible: showResetFooter()">
											<a href="#" onclick="return resetFooter()" title="'. trans('texts.reset_footer_help') .'">' . trans("texts.reset_footer") . '</a>
										</div>
									</div>'); ?>

				</div>
				<?php if($account->hasFeature(FEATURE_DOCUMENTS)): ?>
				<div role="tabpanel" class="tab-pane" id="attached-documents" style="position:relative;z-index:9">
					<div id="document-upload">
						<div class="dropzone">
							<div data-bind="foreach: documents">
								<input type="hidden" name="document_ids[]" data-bind="value: public_id"/>
							</div>
						</div>
						<?php if($invoice->hasExpenseDocuments() || $expenses->count()): ?>
							<h4><?php echo e(trans('texts.documents_from_expenses')); ?></h4>
							<?php $__currentLoopData = $invoice->expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php if($expense->invoice_documents): ?>
									<?php $__currentLoopData = $expense->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<div><?php echo e($document->name); ?></div>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<?php endif; ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php if($expense->invoice_documents): ?>
									<?php $__currentLoopData = $expense->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<div><?php echo e($document->name); ?></div>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<?php endif; ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php endif; ?>
					</div>
				</div>
				<?php endif; ?>
			</div>

			<?php echo e(Former::setOption('TwitterBootstrap3.labelWidths.large', 4)); ?>

			<?php echo e(Former::setOption('TwitterBootstrap3.labelWidths.small', 4)); ?>


		</div>

    </div>
	</div>
	</div>

	<center class="buttons">

		<div style="display:none">
			<?php echo Former::populateField('entityType', $entityType); ?>


			<?php echo Former::text('entityType'); ?>

			<?php echo Former::text('action'); ?>

			<?php echo Former::text('public_id')->data_bind('value: public_id'); ?>

			<?php echo Former::text('is_public')->data_bind('value: is_public'); ?>

            <?php echo Former::text('is_recurring')->data_bind('value: is_recurring'); ?>

            <?php echo Former::text('is_quote')->data_bind('value: is_quote'); ?>

            <?php echo Former::text('has_tasks')->data_bind('value: has_tasks'); ?>

            <?php echo Former::text('data')->data_bind('value: ko.mapping.toJSON(model)'); ?>

			<?php echo Former::text('has_expenses')->data_bind('value: has_expenses'); ?>

            <?php echo Former::text('pdfupload'); ?>

		</div>

		<?php if(!Utils::hasFeature(FEATURE_MORE_INVOICE_DESIGNS)): ?>
			<?php echo Former::select('invoice_design_id')->style('display:inline;width:150px;background-color:white !important')->raw()->fromQuery($invoiceDesigns, 'name', 'id')->data_bind("value: invoice_design_id")->addOption(trans('texts.more_designs') . '...', '-1'); ?>

		<?php else: ?>
			<?php echo Former::select('invoice_design_id')->style('display:inline;width:150px;background-color:white !important')->raw()->fromQuery($invoiceDesigns, 'name', 'id')->data_bind("value: invoice_design_id"); ?>

		<?php endif; ?>

        <?php if( $invoice->id && ! $invoice->is_recurring): ?>
		    <?php echo Button::primary(trans('texts.download'))
                    ->withAttributes(['onclick' => 'onDownloadClick()', 'id' => 'downloadPdfButton'])
                    ->appendIcon(Icon::create('download-alt')); ?>

        <?php endif; ?>

        <?php if(Auth::user()->canCreateOrEdit(ENTITY_INVOICE, $invoice)): ?>
            <?php if($invoice->isClientTrashed()): ?>
                <!-- do nothing -->
			<?php elseif($invoice->isLocked()): ?>
				<?php if(! $invoice->trashed()): ?>
					<?php echo Button::info(trans("texts.email_{$entityType}"))->withAttributes(array('id' => 'emailButton', 'onclick' => 'onEmailClick()'))->appendIcon(Icon::create('send')); ?>

					<?php echo DropdownButton::normal(trans('texts.more_actions'))->withContents($invoice->present()->moreActions())->dropup(); ?>

				<?php endif; ?>
            <?php else: ?>
				<?php if(!$invoice->is_deleted): ?>
					<?php if( ! Auth::user()->account->realtime_preview && Auth::user()->account->live_preview): ?>
						<?php echo Button::normal('PDF')->withAttributes(['id' => 'refreshPdfButton', 'onclick' => 'refreshPDF(true,true)'])->appendIcon(Icon::create('refresh')); ?>

					<?php endif; ?>

					<?php if($invoice->isSent()): ?>
						<?php echo Button::success(trans("texts.save_{$entityType}"))->withAttributes(array('id' => 'saveButton', 'onclick' => 'onSaveClick()'))->appendIcon(Icon::create('floppy-disk')); ?>

					<?php else: ?>
						<?php echo Button::normal(trans("texts.save_draft"))->withAttributes(array('id' => 'draftButton', 'onclick' => 'onSaveDraftClick()'))->appendIcon(Icon::create('floppy-disk')); ?>

						<?php if(! $invoice->trashed()): ?>
							<?php echo Button::success(trans($invoice->is_recurring ? "texts.mark_ready" : "texts.mark_sent"))->withAttributes(array('id' => 'saveButton', 'onclick' => 'onMarkSentClick()'))->appendIcon(Icon::create('globe')); ?>

						<?php endif; ?>
					<?php endif; ?>
					<?php if(! $invoice->trashed()): ?>
						<?php echo Button::info(trans("texts.email_{$entityType}"))->withAttributes(array('id' => 'emailButton', 'onclick' => 'onEmailClick()'))->appendIcon(Icon::create('send')); ?>

					<?php endif; ?>
                    <?php if($invoice->id): ?>
                        <?php echo DropdownButton::normal(trans('texts.more_actions'))->withContents($invoice->present()->moreActions())->dropup(); ?>

                    <?php elseif(Request::is('*/clone')): ?>
                        <?php echo Button::normal(trans($invoice->is_recurring ? 'texts.disable_recurring' : 'texts.enable_recurring'))->withAttributes(['id' => 'recurrButton', 'onclick' => 'onRecurrClick()'])->appendIcon(Icon::create('repeat')); ?>

					<?php elseif(! empty($tasks)): ?>
						<?php echo Button::normal(trans('texts.add_product'))->withAttributes(['id' => 'addItemButton', 'onclick' => 'onAddItemClick()'])->appendIcon(Icon::create('plus-sign')); ?>

                    <?php endif; ?>
        	    <?php endif; ?>
                <?php if($invoice->trashed()): ?>
                    <?php echo Button::primary(trans('texts.restore'))->withAttributes(['onclick' => 'submitBulkAction("restore")'])->appendIcon(Icon::create('cloud-download')); ?>

                <?php endif; ?>
    		<?php endif; ?>
        <?php endif; ?>

	</center>

	<?php echo $__env->make('invoices.pdf', ['account' => Auth::user()->account, 'hide_pdf' => ! Auth::user()->account->live_preview, 'realtime_preview' => Auth::user()->account->realtime_preview], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

	<?php if(!Auth::user()->account->isPro()): ?>
		<div style="font-size:larger">
			<?php echo trans('texts.pro_plan_remove_logo', ['link'=>'<a href="javascript:showUpgradeModal()">' . trans('texts.pro_plan_remove_logo_link') . '</a>']); ?>

		</div>
	<?php endif; ?>

	<div class="modal fade" id="clientModal" tabindex="-1" role="dialog" aria-labelledby="clientModalLabel" aria-hidden="true">
	  <div class="modal-dialog" data-bind="css: {'large-dialog': $root.showMore}">
	    <div class="modal-content" style="background-color: #f8f8f8">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title" id="clientModalLabel"><?php echo e(trans('texts.client')); ?></h4>
	      </div>

       <div class="container" style="width: 100%; padding-bottom: 0px !important">
       <div class="panel panel-default">
        <div class="panel-body">

        <div class="row" data-bind="with: client" onkeypress="clientModalEnterClick(event)">
            <div style="margin-left:0px;margin-right:0px" data-bind="css: {'col-md-6': $root.showMore}">

                <?php echo Former::hidden('client_public_id')->data_bind("value: public_id, valueUpdate: 'afterkeydown',
                            attr: {name: 'client[public_id]'}"); ?>

                <?php echo Former::text('client[name]')
                    ->data_bind("value: name, valueUpdate: 'afterkeydown', attr { placeholder: name.placeholder }")
                    ->label('client_name'); ?>


				<?php if( ! $account->client_number_counter): ?>
                <span data-bind="visible: $root.showMore">
				<?php endif; ?>

            	<?php echo Former::text('client[id_number]')
                            ->label('id_number')
							->placeholder($account->clientNumbersEnabled() ? $account->getNextNumber() : ' ')
                            ->data_bind("value: id_number, valueUpdate: 'afterkeydown'"); ?>


				<?php if( ! $account->client_number_counter): ?>
				</span>
				<?php endif; ?>

				<span data-bind="visible: $root.showMore">
                    <?php echo Former::text('client[vat_number]')
                            ->label('vat_number')
                            ->data_bind("value: vat_number, valueUpdate: 'afterkeydown'"); ?>


                    <?php echo Former::text('client[website]')
                            ->label('website')
                            ->data_bind("value: website, valueUpdate: 'afterkeydown'"); ?>

                    <?php echo Former::text('client[work_phone]')
                            ->label('work_phone')
                            ->data_bind("value: work_phone, valueUpdate: 'afterkeydown'"); ?>


                </span>

                <?php if(Auth::user()->hasFeature(FEATURE_INVOICE_SETTINGS)): ?>
                    <?php if($account->customLabel('client1')): ?>
						<?php echo $__env->make('partials.custom_field', [
							'field' => 'client[custom_value1]',
							'label' => $account->customLabel('client1'),
							'databind' => "value: custom_value1, valueUpdate: 'afterkeydown'",
						], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php endif; ?>
                    <?php if($account->customLabel('client2')): ?>
						<?php echo $__env->make('partials.custom_field', [
							'field' => 'client[custom_value2]',
							'label' => $account->customLabel('client2'),
							'databind' => "value: custom_value2, valueUpdate: 'afterkeydown'",
						], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php endif; ?>
                <?php endif; ?>

                <span data-bind="visible: $root.showMore">
                    &nbsp;

                    <?php echo Former::text('client[address1]')
                            ->label(trans('texts.address1'))
                            ->data_bind("value: address1, valueUpdate: 'afterkeydown'"); ?>

                    <?php echo Former::text('client[address2]')
                            ->label(trans('texts.address2'))
                            ->data_bind("value: address2, valueUpdate: 'afterkeydown'"); ?>

                    <?php echo Former::text('client[city]')
                            ->label(trans('texts.city'))
                            ->data_bind("value: city, valueUpdate: 'afterkeydown'"); ?>

                    <?php echo Former::text('client[state]')
                            ->label(trans('texts.state'))
                            ->data_bind("value: state, valueUpdate: 'afterkeydown'"); ?>

                    <?php echo Former::text('client[postal_code]')
                            ->label(trans('texts.postal_code'))
                            ->data_bind("value: postal_code, valueUpdate: 'afterkeydown'"); ?>

                    <?php echo Former::select('client[country_id]')
                            ->label(trans('texts.country_id'))
                            ->autocomplete('off')
                            ->addOption('','')->addGroupClass('country_select')
                            ->fromQuery($countries, 'name', 'id')
							->data_bind("dropdown: country_id"); ?>

                </span>

            </div>
            <div style="margin-left:0px;margin-right:0px" data-bind="css: {'col-md-6': $root.showMore}">

                <div data-bind='template: { foreach: contacts,
                                        beforeRemove: hideContact,
                                        afterAdd: showContact }'>

                    <?php echo Former::hidden('public_id')->data_bind("value: public_id, valueUpdate: 'afterkeydown',
                            attr: {name: 'client[contacts][' + \$index() + '][public_id]'}"); ?>

                    <?php echo Former::text('first_name')->data_bind("value: first_name, valueUpdate: 'afterkeydown',
                            attr: {name: 'client[contacts][' + \$index() + '][first_name]'}"); ?>

                    <?php echo Former::text('last_name')->data_bind("value: last_name, valueUpdate: 'afterkeydown',
                            attr: {name: 'client[contacts][' + \$index() + '][last_name]'}"); ?>

                    <?php echo Former::text('email')->data_bind("value: email, valueUpdate: 'afterkeydown',
                            attr: {name: 'client[contacts][' + \$index() + '][email]', id:'email'+\$index()}")
                            ->addClass('client-email'); ?>

                    <?php echo Former::text('phone')->data_bind("value: phone, valueUpdate: 'afterkeydown',
                            attr: {name: 'client[contacts][' + \$index() + '][phone]'}"); ?>

                    <?php if($account->hasFeature(FEATURE_CLIENT_PORTAL_PASSWORD) && $account->enable_portal_password): ?>
                        <?php echo Former::password('password')->data_bind("value: (typeof password=='function'?password():null)?'-%unchanged%-':'', valueUpdate: 'afterkeydown',
                            attr: {name: 'client[contacts][' + \$index() + '][password]'}")->autocomplete('new-password')->data_lpignore('true'); ?>

                    <?php endif; ?>
					<?php if(Auth::user()->hasFeature(FEATURE_INVOICE_SETTINGS)): ?>
	                    <?php if($account->customLabel('contact1')): ?>
							<?php echo $__env->make('partials.custom_field', [
								'field' => 'custom_contact1',
								'label' => $account->customLabel('contact1'),
								'databind' => "value: custom_value1, valueUpdate: 'afterkeydown',
			                            attr: {name: 'client[contacts][' + \$index() + '][custom_value1]'}",
							], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	                    <?php endif; ?>
	                    <?php if($account->customLabel('contact2')): ?>
							<?php echo $__env->make('partials.custom_field', [
								'field' => 'custom_contact2',
								'label' => $account->customLabel('contact2'),
								'databind' => "value: custom_value2, valueUpdate: 'afterkeydown',
			                            attr: {name: 'client[contacts][' + \$index() + '][custom_value2]'}",
							], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	                    <?php endif; ?>
	                <?php endif; ?>
                    <div class="form-group">
                        <div class="col-lg-8 col-lg-offset-4">
                            <span class="redlink bold" data-bind="visible: $parent.contacts().length > 1">
                                <?php echo link_to('#', trans('texts.remove_contact').' -', array('data-bind'=>'click: $parent.removeContact')); ?>

                            </span>
                            <span data-bind="visible: $index() === ($parent.contacts().length - 1)" class="pull-right greenlink bold">
                                <?php echo link_to('#', trans('texts.add_contact').' +', array('data-bind'=>'click: $parent.addContact')); ?>

                            </span>
                        </div>
                    </div>
                </div>

                <span data-bind="visible: $root.showMore">
                    &nbsp;
                </span>

                <?php echo Former::select('client[currency_id]')->addOption('','')
                        ->placeholder($account->currency ? trans('texts.currency_'.Str::slug($account->currency->name, '_')) : '')
                        ->label(trans('texts.currency_id'))
                        ->data_bind('value: currency_id')
                        ->fromQuery($currencies, 'name', 'id'); ?>


                <span data-bind="visible: $root.showMore">
                <?php echo Former::select('client[language_id]')->addOption('','')
						->placeholder($account->language ? trans('texts.lang_'.$account->language->name) : '')
                        ->label(trans('texts.language_id'))
                        ->data_bind('value: language_id')
                        ->fromQuery($languages, 'name', 'id'); ?>

                <?php echo Former::select('client[payment_terms]')->addOption('','')->data_bind('value: payment_terms')
                        ->fromQuery(\App\Models\PaymentTerm::getSelectOptions(), 'name', 'num_days')
                        ->label(trans('texts.payment_terms'))
                        ->help(trans('texts.payment_terms_help')); ?>

                <?php echo Former::select('client[size_id]')->addOption('','')->data_bind('value: size_id')
                        ->label(trans('texts.size_id'))
                        ->fromQuery($sizes, 'name', 'id'); ?>

                <?php echo Former::select('client[industry_id]')->addOption('','')->data_bind('value: industry_id')
                        ->label(trans('texts.industry_id'))
                        ->fromQuery($industries, 'name', 'id'); ?>

                <?php echo Former::textarea('client_private_notes')
                        ->label(trans('texts.private_notes'))
                        ->data_bind("value: private_notes, attr:{ name: 'client[private_notes]'}"); ?>

                </span>
            </div>
            </div>
        </div>
        </div>
        </div>

         <div class="modal-footer">
            <span class="error-block" id="emailError" style="display:none;float:left;font-weight:bold"><?php echo e(trans('texts.provide_name_or_email')); ?></span><span>&nbsp;</span>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(trans('texts.cancel')); ?></button>
            <button type="button" class="btn btn-default" data-bind="click: $root.showMoreFields, text: $root.showMore() ? '<?php echo e(trans('texts.less_fields')); ?>' : '<?php echo e(trans('texts.more_fields')); ?>'"></button>
            <button id="clientDoneButton" type="button" class="btn btn-primary" data-bind="click: $root.clientFormComplete"><?php echo e(trans('texts.done')); ?></button>
         </div>

        </div>
      </div>
    </div>

	<div class="modal fade" id="recurringModal" tabindex="-1" role="dialog" aria-labelledby="recurringModalLabel" aria-hidden="true">
	  <div class="modal-dialog" style="min-width:150px">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title" id="recurringModalLabel"><?php echo e(trans('texts.recurring_invoices')); ?></h4>
	      </div>

		  <div class="container" style="width: 100%; padding-bottom: 0px !important">
          <div class="panel panel-default">
			 <div class="panel-body">
				 <?php echo isset($recurringHelp) ? $recurringHelp : ''; ?>

			 </div>
		  </div>
		  </div>

	     <div class="modal-footer">
	      	<button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo e(trans('texts.close')); ?></button>
	     </div>

	    </div>
	  </div>
	</div>

    <div class="modal fade" id="recurringDueDateModal" tabindex="-1" role="dialog" aria-labelledby="recurringDueDateModalLabel" aria-hidden="true">
	  <div class="modal-dialog" style="min-width:150px">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title" id="recurringDueDateModalLabel"><?php echo e(trans('texts.recurring_due_dates')); ?></h4>
	      </div>

		  <div class="container" style="width: 100%; padding-bottom: 0px !important">
          <div class="panel panel-default">
			 <div class="panel-body">
				 <?php echo isset($recurringDueDateHelp) ? $recurringDueDateHelp : ''; ?>

			</div>
		 </div>
		 </div>

	     <div class="modal-footer">
	      	<button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo e(trans('texts.close')); ?></button>
	     </div>

	    </div>
	  </div>
	</div>

	<?php echo $__env->make('partials.email_templates', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	<?php echo $__env->make('invoices.email', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <?php echo Former::close(); ?>

    </form>

    <?php echo Former::open("{$entityType}s/bulk")->addClass('bulkForm'); ?>

    <?php echo Former::populateField('bulk_public_id', $invoice->public_id); ?>

    <span style="display:none">
    <?php echo Former::text('bulk_public_id'); ?>

    <?php echo Former::text('bulk_action'); ?>

    </span>
    <?php echo Former::close(); ?>


    </div>

    <?php echo $__env->make('invoices.knockout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

	<script type="text/javascript">
    Dropzone.autoDiscover = false;

    var products = <?php echo $products; ?>;
	var clients = <?php echo $clients; ?>;
    var account = <?php echo Auth::user()->account; ?>;
    var dropzone;

    var clientMap = {};
    var $clientSelect = $('select#client');
    var invoiceDesigns = <?php echo $invoiceDesigns; ?>;
    var invoiceFonts = <?php echo $invoiceFonts; ?>;

	$(function() {
        // create client dictionary
        for (var i=0; i<clients.length; i++) {
            var client = clients[i];
            clientMap[client.public_id] = client;
            <?php if(! $invoice->id): ?>
	            if (!getClientDisplayName(client)) {
	                continue;
	            }
            <?php endif; ?>
			var clientName = client.name || '';
			for (var j=0; j<client.contacts.length; j++) {
                var contact = client.contacts[j];
                var contactName = getContactDisplayNameWithEmail(contact);
				if (clientName && contactName) {
					clientName += '<br/>  • ';
				}
				if (contactName) {
					clientName += contactName;
				}
            }
            $clientSelect.append(new Option(clientName, client.public_id));
        }

        <?php if($data): ?>
            // this means we failed so we'll reload the previous state
            window.model = new ViewModel(<?php echo $data; ?>);
        <?php else: ?>
            // otherwise create blank model
            window.model = new ViewModel();

            var invoice = <?php echo $invoice; ?>;
            ko.mapping.fromJS(invoice, model.invoice().mapping, model.invoice);
            model.invoice().is_recurring(<?php echo e($invoice->is_recurring ? '1' : '0'); ?>);
            model.invoice().start_date_orig(model.invoice().start_date());
            <?php if($invoice->id): ?>
                var invitationContactIds = <?php echo json_encode($invitationContactIds); ?>;
                var client = clientMap[invoice.client.public_id];
                if (client) { // in case it's deleted
                    for (var i=0; i<client.contacts.length; i++) {
                        var contact = client.contacts[i];
                        contact.send_invoice = invitationContactIds.indexOf(contact.public_id) >= 0;
                    }
                }
                model.invoice().addItem(); // add blank item
            <?php else: ?>
                // set the default account tax rate
                <?php if($account->invoice_taxes): ?>
					<?php if(! empty($account->tax_name1)): ?>
						model.invoice().tax_rate1("<?php echo e($account->tax_rate1); ?>");
						model.invoice().tax_name1(<?php echo json_encode($account->tax_name1); ?>);
					<?php endif; ?>
					<?php if(! empty($account->tax_name2)): ?>
						model.invoice().tax_rate2("<?php echo e($account->tax_rate2); ?>");
						model.invoice().tax_name2(<?php echo json_encode($account->tax_name2); ?>);
					<?php endif; ?>
                <?php endif; ?>

				// load previous isAmountDiscount setting
				if (isStorageSupported()) {
					var lastIsAmountDiscount = parseInt(localStorage.getItem('last:is_amount_discount'));
		            if (lastIsAmountDiscount) {
						model.invoice().is_amount_discount(lastIsAmountDiscount);
		            }
		        }
            <?php endif; ?>

            <?php if(isset($tasks) && count($tasks)): ?>
                NINJA.formIsChanged = true;
                var tasks = <?php echo json_encode($tasks); ?>;
                for (var i=0; i<tasks.length; i++) {
                    var task = tasks[i];
                    var item = model.invoice().addItem(true);
                    item.notes(task.description);
                    item.qty(task.duration);
					item.cost(task.cost);
                    item.task_public_id(task.publicId);
                }
                model.invoice().has_tasks(true);
				NINJA.formIsChanged = true;
            <?php endif; ?>

            <?php if(isset($expenses) && $expenses->count()): ?>
                NINJA.formIsChanged = true;
                model.expense_currency_id(<?php echo e(isset($expenseCurrencyId) ? $expenseCurrencyId : 0); ?>);

                // move the blank invoice line item to the end
                var blank = model.invoice().invoice_items_without_tasks.pop();
                var expenses = <?php echo $expenses; ?>


                for (var i=0; i<expenses.length; i++) {
                    var expense = expenses[i];
                    var item = model.invoice().addItem();
                    item.product_key(expense.expense_category ? expense.expense_category.name : '');
                    item.notes(expense.public_notes);
                    item.qty(1);
                    item.expense_public_id(expense.public_id);
					item.cost(expense.converted_amount);
                    item.tax_rate1(expense.tax_rate1);
                    item.tax_name1(expense.tax_name1);
                    item.tax_rate2(expense.tax_rate2);
                    item.tax_name2(expense.tax_name2);
                }
                model.invoice().invoice_items_without_tasks.push(blank);
                model.invoice().has_expenses(true);
				NINJA.formIsChanged = true;
            <?php endif; ?>

			<?php if($selectedProducts = session('selectedProducts')): ?>
				// move the blank invoice line item to the end
				var blank = model.invoice().invoice_items_without_tasks.pop();
				var productMap = {};
				for (var i=0; i<products.length; i++) {
					var product = products[i];
					productMap[product.product_key] = product;
				}
				var selectedProducts = <?php echo json_encode($selectedProducts); ?>

				for (var i=0; i<selectedProducts.length; i++) {
					var productKey = selectedProducts[i];
					product = productMap[productKey];
					if (product) {
						var item = model.invoice().addItem();
						item.loadData(product);
						item.qty(1);
					}
				}
				model.invoice().invoice_items_without_tasks.push(blank);
				NINJA.formIsChanged = true;
			<?php endif; ?>

        <?php endif; ?>

        // display blank instead of '0'
        if (!NINJA.parseFloat(model.invoice().discount())) model.invoice().discount('');
        if (!NINJA.parseFloat(model.invoice().partial())) model.invoice().partial('');
        if (!model.invoice().custom_value1()) model.invoice().custom_value1('');
        if (!model.invoice().custom_value2()) model.invoice().custom_value2('');

        ko.applyBindings(model);
        onItemChange(true);

        $('#client\\[country_id\\]').on('change', function(e) {
			var countryId = $(e.currentTarget).val();
			var country = _.findWhere(countries, {id: parseInt(countryId)});
			if (country) {
                model.invoice().client().country = country;
                model.invoice().client().country_id(countryId);
            } else {
				model.invoice().client().country = false;
				model.invoice().client().country_id(0);
			}
		});

		$('[rel=tooltip]').tooltip({'trigger':'manual'});

		$('#invoice_date, #due_date, #start_date, #end_date, #last_sent_date, #partial_due_date').datepicker();

		<?php if($invoice->client && !$invoice->id): ?>
			$('input[name=client]').val(<?php echo e($invoice->client->public_id); ?>);
		<?php endif; ?>

		var $input = $('select#client');
		$input.combobox().on('change', function(e) {
            var oldId = model.invoice().client().public_id();
            var clientId = parseInt($('input[name=client]').val(), 10) || 0;
            if (clientId > 0) {
                var selected = clientMap[clientId];
				model.loadClient(selected);
                // we enable searching by contact but the selection must be the client
                $('.client-input').val(getClientDisplayName(selected));
                // if there's an invoice number pattern we'll apply it now
                setInvoiceNumber(selected);
                refreshPDF(true, true);
			} else if (oldId) {
				model.loadClient($.parseJSON(ko.toJSON(new ClientModel())));
				model.invoice().client().country = false;
                refreshPDF(true, true);
			}
		});

		// If no clients exists show the client form when clicking on the client select input
		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', ENTITY_CLIENT)): ?>;
		if (clients.length === 0) {
			$('.client_select input.form-control').on('click', function() {
				model.showClientForm();
			});
		}
		<?php endif; ?>

		$('#invoice_footer, #terms, #public_notes, #invoice_number, #invoice_date, #due_date, #partial_due_date, #start_date, #po_number, #discount, #currency_id, #invoice_design_id, #recurring, #is_amount_discount, #partial, #custom_text_value1, #custom_text_value2, #taxRateSelect1, #taxRateSelect2').change(function() {
            $('#downloadPdfButton').attr('disabled', true);
			setTimeout(function() {
				refreshPDF(true);
			}, 1);
		});

        $('.frequency_id .input-group-addon').click(function() {
            showLearnMore();
        });

        $('.recurring_due_date .input-group-addon').click(function() {
            showRecurringDueDateLearnMore();
        });

        var fields = ['invoice_date', 'due_date', 'start_date', 'end_date', 'last_sent_date'];
        for (var i=0; i<fields.length; i++) {
            var field = fields[i];
            (function (_field) {
                $('.' + _field + ' .input-group-addon').click(function() {
                    toggleDatePicker(_field);
                });
            })(field);
        }

        if (model.invoice().client().public_id() || <?php echo e($invoice->id || count($clients) == 0 ? '1' : '0'); ?>) {
            // do nothing
        } else {
            $('.client_select input.form-control').focus();
        }

		$('#clientModal').on('shown.bs.modal', function () {
            $('#client\\[name\\]').focus();
		}).on('hidden.bs.modal', function () {
			if (model.clientBackup) {
				model.loadClient(model.clientBackup);
				refreshPDF(true);
			}
		})

		$('#relatedActions > button:first').click(function() {
			onPaymentClick();
		});

		$('label.radio').addClass('radio-inline');

		<?php if($invoice->client->id): ?>
			$input.trigger('change');
		<?php else: ?>
			refreshPDF(true, true);
		<?php endif; ?>

		var client = model.invoice().client();
		setComboboxValue($('.client_select'),
			client.public_id(),
			client.name.display());

        applyComboboxListeners();

        <?php if(Auth::user()->account->hasFeature(FEATURE_DOCUMENTS)): ?>
        $('.main-form').submit(function(){
            if($('#document-upload .dropzone .fallback input').val())$(this).attr('enctype', 'multipart/form-data')
            else $(this).removeAttr('enctype')
        })

        // Initialize document upload
        window.dropzone = false;
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            if (window.dropzone) {
                return;
            }

            var target = $(e.target).attr('href') // activated tab
            if (target != '#attached-documents') {
                return;
            }

			<?php echo $__env->make('partials.dropzone', ['documentSource' => 'model.invoice().documents()'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        });
        <?php endif; ?>
	});

    function onFrequencyChange(){
        var currentName = $('#frequency_id').find('option:selected').text()
        var currentDueDateNumber = $('#recurring_due_date').find('option:selected').attr('data-num');
        var optionClass = currentName && currentName.toLowerCase().indexOf('week') > -1 ? 'weekly' :  'monthly';
        var replacementOption = $('#recurring_due_date option[data-num=' + currentDueDateNumber + '].' + optionClass);

        $('#recurring_due_date option').hide();
        $('#recurring_due_date option.' + optionClass).show();

        // Switch to an equivalent option
        if(replacementOption.length){
            replacementOption.attr('selected','selected');
        }
        else{
            $('#recurring_due_date').val('');
        }
    }

	function applyComboboxListeners() {
        var selectorStr = '.invoice-table input, .invoice-table textarea';
		$(selectorStr).off('change').on('change', function(event) {
            if ($(event.target).hasClass('handled')) {
                return;
            }
            $('#downloadPdfButton').attr('disabled', true);
            onItemChange();
            refreshPDF(true);
		});

        var selectorStr = '.invoice-table select';
        $(selectorStr).off('blur').on('blur', function(event) {
            onItemChange();
            refreshPDF(true);
        });

        $('textarea.word-wrap').on('keyup focus', function(e) {
            $(this).height(0).height(this.scrollHeight-18);
        });

	}

	function createInvoiceModel() {
        var model = ko.toJS(window.model);
        if (! model) {
			return;
		}
		var invoice = model.invoice;
		invoice.features = {
            customize_invoice_design:<?php echo e(Auth::user()->hasFeature(FEATURE_CUSTOMIZE_INVOICE_DESIGN) ? 'true' : 'false'); ?>,
            remove_created_by:<?php echo e(Auth::user()->hasFeature(FEATURE_REMOVE_CREATED_BY) ? 'true' : 'false'); ?>,
            invoice_settings:<?php echo e(Auth::user()->hasFeature(FEATURE_INVOICE_SETTINGS) ? 'true' : 'false'); ?>

        };
		invoice.is_quote = <?php echo e($entityType == ENTITY_QUOTE ? 'true' : 'false'); ?>;
		invoice.contact = _.findWhere(invoice.client.contacts, {send_invoice: true});

        if (invoice.is_recurring) {
            invoice.invoice_number = <?php echo json_encode(trans('texts.assigned_when_sent')); ?>;
			invoice.due_date = <?php echo json_encode(trans('texts.assigned_when_sent')); ?>;
            if (invoice.start_date) {
                invoice.invoice_date = invoice.start_date;
            } else {
				invoice.invoice_date = invoice.due_date;
			}
        }

        <?php if(!$invoice->id || $invoice->is_recurring): ?>
            if (!invoice.terms) {
                invoice.terms = account['<?php echo e($entityType); ?>_terms'];
            }
            if (!invoice.invoice_footer) {
                invoice.invoice_footer = account['invoice_footer'];
            }
        <?php endif; ?>

		<?php if($account->hasLogo()): ?>
			invoice.image = "<?php echo e(Form::image_data($account->getLogoRaw(), true)); ?>";
			invoice.imageWidth = <?php echo e($account->getLogoWidth()); ?>;
			invoice.imageHeight = <?php echo e($account->getLogoHeight()); ?>;
		<?php endif; ?>

		if (! invoice.public_id || NINJA.formIsChanged) {
			invoice.watermark = "<?php echo e(strtoupper(trans('texts.preview'))); ?>";
		}

        return invoice;
	}

	var origInvoiceNumber = false;

	function getPDFString(cb, force) {
		<?php if(! $invoice->id && $account->credit_number_counter > 0): ?>
			var total = model.invoice().totals.rawTotal();
			var invoiceNumber = model.invoice().invoice_number();
			var creditNumber = "<?php echo e($account->getNextNumber(new \App\Models\Credit())); ?>";
			if (total < 0 && invoiceNumber != creditNumber) {
				origInvoiceNumber = invoiceNumber;
				model.invoice().invoice_number(creditNumber);
			} else if (total >= 0 && invoiceNumber == creditNumber && origInvoiceNumber) {
				model.invoice().invoice_number(origInvoiceNumber);
			}
		<?php endif; ?>

		<?php if( ! $account->live_preview): ?>
			return;
		<?php endif; ?>

		var invoice = createInvoiceModel();
		var design = getDesignJavascript();

		if (! design) {
			return;
		}

        generatePDF(invoice, design, force, cb);
	}

	function getDesignJavascript() {
		var id = $('#invoice_design_id').val();
		if (id == '-1') {
			showMoreDesigns();
			model.invoice().invoice_design_id(1);
			return invoiceDesigns[0].javascript;
		} else {
            var design = _.find(invoiceDesigns, function(design){ return design.id == id});
            return design ? design.javascript : '';
		}
	}

    function resetTerms() {
        sweetConfirm(function() {
            model.invoice().terms(model.invoice().default_terms());
            refreshPDF();
        });

        return false;
    }

    function resetFooter() {
        sweetConfirm(function() {
            model.invoice().invoice_footer(model.invoice().default_footer());
            refreshPDF();
        });

        return false;
    }

	function onDownloadClick() {
		trackEvent('/activity', '/download_pdf');
		var invoice = createInvoiceModel();
        var design  = getDesignJavascript();
		if (!design) return;
		var doc = generatePDF(invoice, design, true);
        var type = invoice.is_quote ? <?php echo json_encode(trans('texts.'.ENTITY_QUOTE)); ?> : <?php echo json_encode(trans('texts.'.ENTITY_INVOICE)); ?>;
		doc.save(type + '_' + $('#invoice_number').val() + '.pdf');
	}

    function onRecurrClick() {
        var invoice = model.invoice();
        if (invoice.is_recurring()) {
            var recurring = false;
            var enableLabel = "<?php echo e(trans('texts.enable_recurring')); ?>";
			var actionLabel = "<?php echo e(trans('texts.mark_sent')); ?>";
        } else {
            var recurring = true;
            var enableLabel = "<?php echo e(trans('texts.disable_recurring')); ?>";
			var actionLabel = "<?php echo e(trans('texts.mark_active')); ?>";
        }
        invoice.is_recurring(recurring);
        $('#recurrButton').html(enableLabel + "<span class='glyphicon glyphicon-repeat'></span>");
		$('#saveButton').html(actionLabel + "<span class='glyphicon glyphicon-globe'></span>");
    }

	function onAddItemClick() {
		model.forceShowItems(true);
		$('#addItemButton').hide();
	}

	function onEmailClick() {
        if (!NINJA.isRegistered) {
            swal(<?php echo json_encode(trans('texts.registration_required')); ?>);
            return;
        }

        var clientId = parseInt($('input[name=client]').val(), 10) || 0;
        if (clientId == 0 ) {
            swal(<?php echo json_encode(trans('texts.no_client_selected')); ?>);
            return;
        }

        if (!isContactSelected()) {
            swal(<?php echo json_encode(trans('texts.no_contact_selected')); ?>);
            return;
        }

        if (!isEmailValid()) {
            swal(<?php echo json_encode(trans('texts.provide_email')); ?>);
            return;
        }

		if (model.invoice().is_recurring()) {
			sweetConfirm(function() {
				onConfirmEmailClick();
			}, getSendToEmails());
		} else {
			showEmailModal();
		}
	}

	function onConfirmEmailClick() {
		$('#emailModal div.modal-footer button').attr('disabled', true);
		model.invoice().is_public(true);
		submitAction('email');
	}

	function onSaveDraftClick() {
		model.invoice().is_public(false);
		onSaveClick();
	}

	function onMarkSentClick() {
		if (model.invoice().is_recurring()) {
			if (! model.invoice().start_date()) {
				swal("<?php echo e(trans('texts.start_date_required')); ?>");
				return false;
			}
			if (!isSaveValid()) {

				<?php if(Auth::user()->can('create', ENTITY_CLIENT)): ?>
					model.showClientForm();
					return false;
				<?php else: ?>
					showPermissionErrorModal();
				<?php endif; ?>
			}

			<?php if($account->auto_email_invoice): ?>
				var title = <?php echo json_encode(trans("texts.confirm_recurring_email_invoice")); ?>;
			<?php else: ?>
				var title = <?php echo json_encode(trans("texts.confirm_recurring_email_invoice_not_sent")); ?>;
			<?php endif; ?>

			var text = '\n' + getSendToEmails();
			var startDate = moment($('#start_date').datepicker('getDate'));

			// warn invoice will be emailed when saving new recurring invoice
			if (model.invoice().start_date() == "<?php echo e(Utils::fromSqlDate(date('Y-m-d'))); ?>") {
				<?php if($account->auto_email_invoice): ?>
					text += '\n\n' + <?php echo json_encode(trans("texts.confirm_recurring_timing")); ?>;
				<?php else: ?>
					text += '\n\n' + <?php echo json_encode(trans("texts.confirm_recurring_timing_not_sent")); ?>;
				<?php endif; ?>
			// check if the start date is in the future
			} else if (startDate.isAfter(moment(), 'day')) {
				var message = <?php echo json_encode(trans("texts.email_will_be_sent_on")); ?>;
				text += '\n\n' + message.replace(':date', model.invoice().start_date());
			}

			sweetConfirm(function() {
				model.invoice().is_public(true);
				submitAction('');
			}, text, title);
			return;
		} else {
			model.invoice().is_public(true);
			onSaveClick();
		}
	}

	function onSaveClick() {
		<?php if($invoice->id): ?>
			if (model.invoice().is_recurring()) {
	            if (model.invoice().start_date() != model.invoice().start_date_orig()) {
	                var text = <?php echo json_encode(trans("texts.original_start_date")); ?> + ': ' + model.invoice().start_date_orig() + '\n'
	                            + <?php echo json_encode(trans("texts.new_start_date")); ?> + ': ' + model.invoice().start_date();
					<?php if($account->auto_email_invoice): ?>
						var title = <?php echo json_encode(trans("texts.warn_start_date_changed")); ?>;
					<?php else: ?>
						var title = <?php echo json_encode(trans("texts.warn_start_date_changed_not_sent")); ?>;
					<?php endif; ?>
	                sweetConfirm(function() {
	                    submitAction('');
	                }, text, title);
	                return;
	            }
	        }
		<?php endif; ?>

        <?php if(!empty($autoBillChangeWarning)): ?>
            var text = <?php echo json_encode(trans('texts.warn_change_auto_bill')); ?>;
            sweetConfirm(function() {
                submitAction('');
            }, text);
            return;
        <?php endif; ?>

        submitAction('');
    }

    function getSendToEmails() {
        var client = model.invoice().client();
        var parts = [];

        for (var i=0; i<client.contacts().length; i++) {
            var contact = client.contacts()[i];
            if (contact.send_invoice()) {
                parts.push(contact.displayName());
            }
        }

        return parts.join('\n');
    }

    function preparePdfData(action) {
        var invoice = createInvoiceModel();
        var design = getDesignJavascript();
        if (!design) return;

        doc = generatePDF(invoice, design, true);
        doc.getDataUrl( function(pdfString){
            $('#pdfupload').val(pdfString);
            submitAction(action);
        });
    }

	function submitAction(value) {
		if (!isSaveValid()) {

			<?php if(Auth::user()->can('create', ENTITY_CLIENT)): ?>
				model.showClientForm();
				return false;
			<?php else: ?>
                showPermissionErrorModal();
			<?php endif; ?>

        }

		$('#action').val(value);
		$('#submitButton').click();
	}

    function onFormSubmit(event) {
        if (window.countUploadingDocuments > 0) {
            swal(<?php echo json_encode(trans('texts.wait_for_upload')); ?>);
            return false;
        }

        <?php if($invoice->is_deleted || $invoice->isClientTrashed()): ?>
            if ($('#bulk_action').val() != 'restore') {
                return false;
            }
        <?php endif; ?>

        // check invoice number is unique
        if ($('.invoice-number').hasClass('has-error')) {
            return false;
        } else if ($('.partial').hasClass('has-error')) {
            return false;
        }

        if (!isSaveValid()) {
            model.showClientForm();
            return false;
        }

        // check currency matches for expenses
        var expenseCurrencyId = model.expense_currency_id();
        var clientCurrencyId = model.invoice().client().currency_id() || <?php echo e($account->getCurrencyId()); ?>;
        if (expenseCurrencyId && expenseCurrencyId != clientCurrencyId) {
            swal(<?php echo json_encode(trans('texts.expense_error_mismatch_currencies')); ?>);
            return false;
        }

        <?php if(Auth::user()->canCreateOrEdit(ENTITY_INVOICE, $invoice)): ?>
			if ($('#saveButton').is(':disabled')) {
				return false;
			}
            $('#saveButton, #emailButton, #draftButton').attr('disabled', true);
            // if save fails ensure user can try again
            $.post('<?php echo e(url($url)); ?>', $('.main-form').serialize(), function(data) {
				if (data && data.toLowerCase().indexOf('http') === 0) {
					NINJA.formIsChanged = false;
					location.href = data;
				} else {
					handleSaveFailed();
				}
            }).fail(function(data) {
				handleSaveFailed(data);
            });
            return false;
        <?php else: ?>
            return false;
        <?php endif; ?>
    }

	function handleSaveFailed(data) {
		$('#saveButton, #emailButton, #draftButton').attr('disabled', false);
		$('#emailModal div.modal-footer button').attr('disabled', false);
		var error = '';
		if (data) {
			var error = firstJSONError(data.responseJSON) || data.statusText;
		}
		swal(<?php echo json_encode(trans('texts.invoice_save_error')); ?>, error);
	}

    function submitBulkAction(value) {
        $('#bulk_action').val(value);
        $('.bulkForm')[0].submit();
    }

	function isSaveValid() {
		var isValid = model.invoice().client().name() ? true : false;
		for (var i=0; i<model.invoice().client().contacts().length; i++) {
			var contact = model.invoice().client().contacts()[i];
			var email = contact.email() ? contact.email().trim() : '';
			if (isValidEmailAddress(email) || contact.first_name() || contact.last_name()) {
				isValid = true;
				break;
			}
		}
		return isValid;
	}

    function isContactSelected() {
		var sendTo = false;
		var client = model.invoice().client();
		for (var i=0; i<client.contacts().length; i++) {
			var contact = client.contacts()[i];
            if (contact.send_invoice()) {
                return true;
            }
		}
		return false;
    }

	function isEmailValid() {
		var isValid = true;
		var client = model.invoice().client();
		for (var i=0; i<client.contacts().length; i++) {
			var contact = client.contacts()[i];
            if ( ! contact.send_invoice()) {
                continue;
            }
			var email = contact.email() ? contact.email().trim() : '';
			if (isValidEmailAddress(email)) {
				isValid = true;
			} else {
				isValid = false;
				break;
			}
		}
		return isValid;
	}

	function onCloneInvoiceClick() {
		submitAction('clone_invoice');
	}

	function onCloneQuoteClick() {
		submitAction('clone_quote');
	}

	function onConvertClick() {
		submitAction('convert');
	}

    <?php if($invoice->id): ?>
    	function onPaymentClick() {
            <?php if(!empty($autoBillChangeWarning)): ?>
                sweetConfirm(function() {
                    window.location = '<?php echo e(URL::to('payments/create/' . $invoice->client->public_id . '/' . $invoice->public_id )); ?>';
                }, <?php echo json_encode(trans('texts.warn_change_auto_bill')); ?>);
            <?php else: ?>
                window.location = '<?php echo e(URL::to('payments/create/' . $invoice->client->public_id . '/' . $invoice->public_id )); ?>';
            <?php endif; ?>
    	}

    	function onCreditClick() {
    		window.location = '<?php echo e(URL::to('credits/create/' . $invoice->client->public_id . '/' . $invoice->public_id )); ?>';
    	}
    <?php endif; ?>

	function onArchiveClick() {
		submitBulkAction('archive');
	}

	function onDeleteClick() {
        sweetConfirm(function() {
            submitBulkAction('delete');
        });
	}
	function formEnterClick(event) {
		if (event.keyCode === 13){
			if (event.target.type == 'textarea') {
				return;
			}
			event.preventDefault();

            <?php if($invoice->trashed()): ?>
                return;
            <?php endif; ?>
			submitAction('');
			return false;
		}
	}

	function clientModalEnterClick(event) {
		if (event.keyCode === 13){
			event.preventDefault();
            model.clientFormComplete();
            return false;
        }
	}

	function onItemChange(silent)
	{
		var hasEmptyStandard = false;
		var hasEmptyTask = false;

		for (var i=0; i<model.invoice().invoice_items_without_tasks().length; i++) {
			var item = model.invoice().invoice_items_without_tasks()[i];
			if (item.isEmpty()) {
				hasEmptyStandard = true;
			}
		}
		if (!hasEmptyStandard) {
			model.invoice().addItem();
		}

		for (var i=0; i<model.invoice().invoice_items_with_tasks().length; i++) {
			var item = model.invoice().invoice_items_with_tasks()[i];
			if (item.isEmpty()) {
				hasEmptyTask = true;
			}
		}
		if (!hasEmptyTask) {
			model.invoice().addItem(true);
		}

		if (!silent) {
        	NINJA.formIsChanged = true;
		}
	}

    function onPartialChange()
    {
        var val = NINJA.parseFloat($('#partial').val());
        var oldVal = val;
        val = Math.max(Math.min(val, model.invoice().totals.rawTotal()), 0);

        if (val != oldVal) {
            if ($('.partial').hasClass('has-error')) {
                return;
            }
            $('.partial')
                .addClass('has-error')
                .find('div.partial')
                .append('<span class="help-block"><?php echo e(trans('texts.partial_value')); ?></span>');
        } else {
            $('.partial')
                .removeClass('has-error')
                .find('span')
                .hide();
        }

    }

    function onRecurringEnabled()
    {
        if ($('#recurring').prop('checked')) {
            $('#emailButton').attr('disabled', true);
            model.invoice().partial('');
        } else {
            $('#emailButton').removeAttr('disabled');
        }
    }

    function showLearnMore() {
        $('#recurringModal').modal('show');
    }

    function showRecurringDueDateLearnMore() {
        $('#recurringDueDateModal').modal('show');
    }

    function setInvoiceNumber(client) {
		<?php if($invoice->id || !$account->hasClientNumberPattern($invoice)): ?>
            return;
        <?php endif; ?>
        var number = '<?php echo e($account->applyNumberPattern($invoice)); ?>';
        number = number.replace('{$clientCustom1}', client.custom_value1 ? client.custom_value1 : '');
        number = number.replace('{$clientCustom2}', client.custom_value2 ? client.custom_value1 : '');
        number = number.replace('{$clientIdNumber}', client.id_number ? client.id_number : '');
		<?php if($invoice->isQuote() && ! $account->share_counter): ?>
			number = number.replace('{$clientCounter}', pad(client.quote_number_counter, <?php echo e($account->invoice_number_padding); ?>));
		<?php else: ?>
        	number = number.replace('{$clientCounter}', pad(client.invoice_number_counter, <?php echo e($account->invoice_number_padding); ?>));
		<?php endif; ?>
		// backwards compatibility
		number = number.replace('{$custom1}', client.custom_value1 ? client.custom_value1 : '');
        number = number.replace('{$custom2}', client.custom_value2 ? client.custom_value1 : '');
        number = number.replace('{$idNumber}', client.id_number ? client.id_number : '');
        model.invoice().invoice_number(number);
    }

	function addDocument(file) {
		file.index = model.invoice().documents().length;
	    model.invoice().addDocument({name:file.name, size:file.size, type:file.type});
	}

	function addedDocument(file, response) {
		model.invoice().documents()[file.index].update(response.document);
	    <?php if($account->invoice_embed_documents): ?>
	        refreshPDF(true);
	    <?php endif; ?>
	}

	function deleteDocument(file) {
		model.invoice().removeDocument(file.public_id);
		refreshPDF(true);
	}

    function showPermissionErrorModal() {
        swal(<?php echo json_encode(trans('texts.create_client')); ?>);
    }

	</script>
    <?php if($account->hasFeature(FEATURE_DOCUMENTS) && $account->invoice_embed_documents): ?>
        <?php $__currentLoopData = $invoice->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($document->isPDFEmbeddable()): ?>
                <script src="<?php echo e($document->getVFSJSUrl()); ?>" type="text/javascript" async></script>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php $__currentLoopData = $invoice->expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $__currentLoopData = $expense->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($document->isPDFEmbeddable()): ?>
                    <script src="<?php echo e($document->getVFSJSUrl()); ?>" type="text/javascript" async></script>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>