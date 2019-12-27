<?php $__env->startSection('head'); ?>
    ##parent-placeholder-1a954628a960aaef81d7b2d4521929579f3541e6##

        <style type="text/css">
            .iframe_url {
                display: none;
            }
            .input-group-addon div.checkbox {
                display: inline;
            }
            .tab-content .pad-checkbox span.input-group-addon {
                padding-right: 30px;
            }
        </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
	##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##
    <?php echo $__env->make('accounts.nav', ['selected' => ACCOUNT_INVOICE_SETTINGS, 'advanced' => true], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <?php echo Former::open()->rules(['iframe_url' => 'url'])->addClass('warn-on-exit'); ?>

    <?php echo e(Former::populate($account)); ?>

    <?php echo e(Former::populateField('auto_convert_quote', intval($account->auto_convert_quote))); ?>

    <?php echo e(Former::populateField('auto_archive_quote', intval($account->auto_archive_quote))); ?>

    <?php echo e(Former::populateField('auto_email_invoice', intval($account->auto_email_invoice))); ?>

    <?php echo e(Former::populateField('auto_archive_invoice', intval($account->auto_archive_invoice))); ?>

    <?php echo e(Former::populateField('custom_invoice_taxes1', intval($account->custom_invoice_taxes1))); ?>

    <?php echo e(Former::populateField('custom_invoice_taxes2', intval($account->custom_invoice_taxes2))); ?>

    <?php echo e(Former::populateField('share_counter', intval($account->share_counter))); ?>

    <?php $__currentLoopData = App\Models\Account::$customFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo e(Former::populateField("custom_fields[$field]", $account->customLabel($field))); ?>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php $__currentLoopData = App\Models\Account::$customFieldsOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo e(Former::populateField("custom_fields_options[$field]", $account->customFieldsOption($field))); ?>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo trans('texts.generated_numbers'); ?></h3>
        </div>
        <div class="panel-body form-padding-right">

            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist" style="border: none">
                    <li role="presentation" class="active">
                        <a href="#invoice_number" aria-controls="invoice_number" role="tab" data-toggle="tab"><?php echo e(trans('texts.invoice_number')); ?></a>
                    </li>
                    <li role="presentation">
                        <a href="#quote_number" aria-controls="quote_number" role="tab" data-toggle="tab"><?php echo e(trans('texts.quote_number')); ?></a>
                    </li>
                    <li role="presentation">
                        <a href="#client_number" aria-controls="client_number" role="tab" data-toggle="tab"><?php echo e(trans('texts.client_number')); ?></a>
                    </li>
                    <li role="presentation">
                        <a href="#credit_number" aria-controls="credit_number" role="tab" data-toggle="tab"><?php echo e(trans('texts.credit_number')); ?></a>
                    </li>
                    <li role="presentation">
                        <a href="#options" aria-controls="options" role="tab" data-toggle="tab"><?php echo e(trans('texts.options')); ?></a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="invoice_number">
                    <div class="panel-body">
                        <?php echo Former::inline_radios('invoice_number_type')
                                ->onchange("onNumberTypeChange('invoice')")
                                ->label(trans('texts.type'))
                                ->radios([
                                    trans('texts.prefix') => ['value' => 'prefix', 'name' => 'invoice_number_type'],
                                    trans('texts.pattern') => ['value' => 'pattern', 'name' => 'invoice_number_type'],
                                ])->check($account->invoice_number_pattern ? 'pattern' : 'prefix'); ?>


                        <?php echo Former::text('invoice_number_prefix')
                                ->addGroupClass('invoice-prefix')
                                ->label(trans('texts.prefix')); ?>

                        <?php echo Former::text('invoice_number_pattern')
                                ->appendIcon('question-sign')
                                ->addGroupClass('invoice-pattern')
                                ->label(trans('texts.pattern'))
                                ->addGroupClass('number-pattern'); ?>

                        <?php echo Former::text('invoice_number_counter')
                                ->label(trans('texts.counter'))
                                ->help(trans('texts.invoice_number_help') . ' ' .
                                    trans('texts.next_invoice_number', ['number' => $account->previewNextInvoiceNumber()])); ?>

                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="quote_number">
                    <div class="panel-body">
                        <?php echo Former::inline_radios('quote_number_type')
                                ->onchange("onNumberTypeChange('quote')")
                                ->label(trans('texts.type'))
                                ->radios([
                                    trans('texts.prefix') => ['value' => 'prefix', 'name' => 'quote_number_type'],
                                    trans('texts.pattern') => ['value' => 'pattern', 'name' => 'quote_number_type'],
                                ])->check($account->quote_number_pattern ? 'pattern' : 'prefix'); ?>


                        <?php echo Former::text('quote_number_prefix')
                                ->addGroupClass('quote-prefix')
                                ->label(trans('texts.prefix')); ?>

                        <?php echo Former::text('quote_number_pattern')
                                ->appendIcon('question-sign')
                                ->addGroupClass('quote-pattern')
                                ->addGroupClass('number-pattern')
                                ->label(trans('texts.pattern')); ?>

                        <?php echo Former::text('quote_number_counter')
                                ->label(trans('texts.counter'))
                                ->addGroupClass('pad-checkbox')
                                ->append(Former::checkbox('share_counter')->raw()->value(1)
                                ->onclick('setQuoteNumberEnabled()') . ' ' . trans('texts.share_invoice_counter'))
                                ->help(trans('texts.quote_number_help') . ' ' .
                                    trans('texts.next_quote_number', ['number' => $account->previewNextInvoiceNumber(ENTITY_QUOTE)])); ?>



                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="client_number">
                    <div class="panel-body">
                        <?php echo Former::checkbox('client_number_enabled')
                                ->label('client_number')
                                ->onchange('onClientNumberEnabled()')
                                ->text('enable')
                                ->value(1)
                                ->check($account->client_number_counter > 0); ?>


                        <div id="clientNumberDiv" style="display:none">

                            <?php echo Former::inline_radios('client_number_type')
                                    ->onchange("onNumberTypeChange('client')")
                                    ->label(trans('texts.type'))
                                    ->radios([
                                        trans('texts.prefix') => ['value' => 'prefix', 'name' => 'client_number_type'],
                                        trans('texts.pattern') => ['value' => 'pattern', 'name' => 'client_number_type'],
                                    ])->check($account->client_number_pattern ? 'pattern' : 'prefix'); ?>


                            <?php echo Former::text('client_number_prefix')
                                    ->addGroupClass('client-prefix')
                                    ->label(trans('texts.prefix')); ?>

                            <?php echo Former::text('client_number_pattern')
                                    ->appendIcon('question-sign')
                                    ->addGroupClass('client-pattern')
                                    ->addGroupClass('client-number-pattern')
                                    ->label(trans('texts.pattern')); ?>

                            <?php echo Former::text('client_number_counter')
                                    ->label(trans('texts.counter'))
                                    ->addGroupClass('pad-checkbox')
                                    ->help(trans('texts.client_number_help') . ' ' .
                                        trans('texts.next_client_number', ['number' => $account->getNextNumber() ?: '0001'])); ?>


                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="credit_number">
                    <div class="panel-body">

                        <?php echo Former::checkbox('credit_number_enabled')
                                ->label('credit_number')
                                ->onchange('onCreditNumberEnabled()')
                                ->text('enable')
                                ->value(1)
                                ->check($account->credit_number_counter > 0); ?>


                        <div id="creditNumberDiv" style="display:none">

                            <?php echo Former::inline_radios('credit_number_type')
                                    ->onchange("onNumberTypeChange('credit')")
                                    ->label(trans('texts.type'))
                                    ->radios([
                                        trans('texts.prefix') => ['value' => 'prefix', 'name' => 'credit_number_type'],
                                        trans('texts.pattern') => ['value' => 'pattern', 'name' => 'credit_number_type'],
                                    ])->check($account->credit_number_pattern ? 'pattern' : 'prefix'); ?>


                            <?php echo Former::text('credit_number_prefix')
                                    ->addGroupClass('credit-prefix')
                                    ->label(trans('texts.prefix')); ?>

                            <?php echo Former::text('credit_number_pattern')
                                    ->appendIcon('question-sign')
                                    ->addGroupClass('credit-pattern')
                                    ->addGroupClass('credit-number-pattern')
                                    ->label(trans('texts.pattern')); ?>

                            <?php echo Former::text('credit_number_counter')
                                    ->label(trans('texts.counter'))
                                    ->addGroupClass('pad-checkbox')
                                    ->help(trans('texts.credit_number_help') . ' ' .
                                        trans('texts.next_credit_number', ['number' => $account->getNextNumber(new \App\Models\Credit()) ?: '0001'])); ?>

                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="options">
                    <div class="panel-body">

                        <?php echo Former::text('invoice_number_padding')
                                ->help('padding_help'); ?>


                        <?php echo Former::text('recurring_invoice_number_prefix')
                                ->label(trans('texts.recurring_prefix'))
                                ->help(trans('texts.recurring_invoice_number_prefix_help')); ?>


                        <?php echo Former::select('reset_counter_frequency_id')
                                ->onchange('onResetFrequencyChange()')
                                ->label('reset_counter')
                                ->addOption(trans('texts.never'), '')
                                ->options(\App\Models\Frequency::selectOptions())
                                ->help('reset_counter_help'); ?>


                        <?php echo Former::text('reset_counter_date')
                                    ->label('next_reset')
                                    ->data_date_format(Session::get(SESSION_DATE_PICKER_FORMAT, DEFAULT_DATE_PICKER_FORMAT))
                                    ->addGroupClass('reset_counter_date_group')
                                    ->append('<i class="glyphicon glyphicon-calendar"></i>')
                                    ->data_date_start_date($account->formatDate($account->getDateTime())); ?>


                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo trans('texts.custom_fields'); ?></h3>
        </div>
        <div class="panel-body form-padding-right">

            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist" style="border: none">
                    <li role="presentation" class="active">
                        <a href="#product_fields" aria-controls="product_fields" role="tab" data-toggle="tab"><?php echo e(trans('texts.products')); ?></a>
                    </li>
                    <li role="presentation">
                        <a href="#client_fields" aria-controls="client_fields" role="tab" data-toggle="tab"><?php echo e(trans('texts.clients')); ?></a>
                    </li>
                    <li role="presentation">
                        <a href="#invoice_fields" aria-controls="invoice_fields" role="tab" data-toggle="tab"><?php echo e(trans('texts.invoices')); ?></a>
                    </li>
                    <li role="presentation">
                        <a href="#task_fields" aria-controls="expense_fields" role="tab" data-toggle="tab"><?php echo e(trans('texts.tasks')); ?></a>
                    </li>
                    <li role="presentation">
                        <a href="#expense_fields" aria-controls="task_fields" role="tab" data-toggle="tab"><?php echo e(trans('texts.expenses')); ?></a>
                    </li>
                    <li role="presentation">
                        <a href="#company_fields" aria-controls="company_fields" role="tab" data-toggle="tab"><?php echo e(trans('texts.company')); ?></a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="product_fields">
                    <div class="panel-body">

                        <?php echo Former::text('custom_fields[product1]')
                                ->label('product_field')
                                ->data_lpignore('true'); ?>

                        <?php echo Former::text('custom_fields[product2]')
                                ->label('product_field')
                                ->data_lpignore('true')
                                ->help(trans('texts.custom_product_fields_help') . ' ' . trans('texts.custom_fields_tip')); ?>


                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="client_fields">
                    <div class="panel-body">

                        <?php echo Former::text('custom_fields[client1]')
                                ->label('client_field')
                                ->addGroupClass('pad-checkbox')
                                ->append(Former::checkbox('custom_fields_options[client1_filter]')
                                            ->value(1)
                                            ->raw() . trans('texts.include_in_filter')); ?>


                        <?php echo Former::text('custom_fields[client2]')
                                ->label('client_field')
                                ->addGroupClass('pad-checkbox')
                                ->append(Former::checkbox('custom_fields_options[client2_filter]')
                                            ->value(1)
                                            ->raw() . trans('texts.include_in_filter'))
                                ->help(trans('texts.custom_client_fields_helps') . ' ' . trans('texts.custom_fields_tip')); ?>


                        <br/>

                        <?php echo Former::text('custom_fields[contact1]')
                                ->label('contact_field'); ?>

                        <?php echo Former::text('custom_fields[contact2]')
                                ->label('contact_field')
                                ->help(trans('texts.custom_contact_fields_help') . ' ' . trans('texts.custom_fields_tip')); ?>


                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="invoice_fields">
                    <div class="panel-body">

                        <?php echo Former::text('custom_fields[invoice_text1]')
                                ->label('invoice_field'); ?>

                        <?php echo Former::text('custom_fields[invoice_text2]')
                                ->label('invoice_field')
                                ->help(trans('texts.custom_invoice_fields_helps') . ' ' . trans('texts.custom_fields_tip')); ?>


                        <?php echo Former::text('custom_fields[invoice1]')
                                ->label('invoice_surcharge')
                                ->addGroupClass('pad-checkbox')
                                ->append(Former::checkbox('custom_invoice_taxes1')
                                            ->value(1)
                                            ->raw() . trans('texts.charge_taxes')); ?>


                        <?php echo Former::text('custom_fields[invoice2]')
                                ->label('invoice_surcharge')
                                ->addGroupClass('pad-checkbox')
                                ->append(Former::checkbox('custom_invoice_taxes2')
                                            ->value(1)
                                            ->raw() . trans('texts.charge_taxes'))
                                            ->help(trans('texts.custom_invoice_charges_helps')); ?>


                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="task_fields">
                    <div class="panel-body">

                        <?php echo Former::text('custom_fields[task1]')
                                ->label('task_field'); ?>

                        <?php echo Former::text('custom_fields[task2]')
                                ->label('task_field')
                                ->help(trans('texts.custom_task_fields_help') . ' ' . trans('texts.custom_fields_tip')); ?>


                        <br/>

                        <?php echo Former::text('custom_fields[project1]')
                                ->label('project_field'); ?>

                        <?php echo Former::text('custom_fields[project2]')
                                ->label('project_field')
                                ->help(trans('texts.custom_project_fields_help') . ' ' . trans('texts.custom_fields_tip')); ?>


                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="expense_fields">
                    <div class="panel-body">

                        <?php echo Former::text('custom_fields[expense1]')
                                ->label(trans('texts.expense_field')); ?>

                        <?php echo Former::text('custom_fields[expense2]')
                                ->label(trans('texts.expense_field'))
                                ->help(trans('texts.custom_expense_fields_help') . ' ' . trans('texts.custom_fields_tip')); ?>


                        <br/>

                        <?php echo Former::text('custom_fields[vendor1]')
                                ->label(trans('texts.vendor_field')); ?>

                        <?php echo Former::text('custom_fields[vendor2]')
                                ->label(trans('texts.vendor_field'))
                                ->help(trans('texts.custom_vendor_fields_help') . ' ' . trans('texts.custom_fields_tip')); ?>


                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="company_fields">
                    <div class="panel-body">

                        <?php echo Former::text('custom_fields[account1]')
                                ->label(trans('texts.company_field')); ?>

                        <?php echo Former::text('custom_value1')
                                ->label(trans('texts.field_value')); ?>

                        <p>&nbsp;</p>
                        <?php echo Former::text('custom_fields[account2]')
                                ->label(trans('texts.company_field')); ?>

                        <?php echo Former::text('custom_value2')
                                ->label(trans('texts.field_value'))
                                ->help(trans('texts.custom_account_fields_helps')); ?>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo trans('texts.workflow_settings'); ?></h3>
        </div>
        <div class="panel-body form-padding-right">

            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist" style="border: none">
                    <li role="presentation" class="active">
                        <a href="#invoice_workflow" aria-controls="invoice_workflow" role="tab" data-toggle="tab"><?php echo e(trans('texts.invoice_workflow')); ?></a>
                    </li>
                    <li role="presentation">
                        <a href="#quote_workflow" aria-controls="quote_workflow" role="tab" data-toggle="tab"><?php echo e(trans('texts.quote_workflow')); ?></a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="invoice_workflow">
                    <div class="panel-body">
                        <?php echo Former::checkbox('auto_email_invoice')
                                ->text(trans('texts.enable'))
                                ->blockHelp(trans('texts.auto_email_invoice_help'))
                                ->value(1); ?>


                        <?php echo Former::checkbox('auto_archive_invoice')
                                ->text(trans('texts.enable'))
                                ->blockHelp(trans('texts.auto_archive_invoice_help'))
                                ->value(1); ?>

                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="quote_workflow">
                    <div class="panel-body">
                        <?php echo Former::checkbox('auto_archive_quote')
                                ->text(trans('texts.enable'))
                                ->blockHelp(trans('texts.auto_archive_quote_help'))
                                ->value(1); ?>


                        <?php echo Former::checkbox('require_approve_quote')
                                ->text(trans('texts.enable'))
                                ->blockHelp(trans('texts.require_approve_quote_help'))
                                ->value(1); ?>


                        <?php echo Former::checkbox('auto_convert_quote')
                                ->text(trans('texts.enable'))
                                ->blockHelp(trans('texts.auto_convert_quote_help'))
                                ->value(1); ?>


                        <?php echo Former::checkbox('allow_approve_expired_quote')
                                ->text(trans('texts.enable'))
                                ->blockHelp(trans('texts.allow_approve_expired_quote_help'))
                                ->value(1); ?>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo trans('texts.defaults'); ?></h3>
      </div>
        <div class="panel-body" style="min-height:350px">

            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist" style="border: none">
                    <li role="presentation" class="active"><a href="#invoice_terms" aria-controls="invoice_terms" role="tab" data-toggle="tab"><?php echo e(trans('texts.invoice_terms')); ?></a></li>
                    <li role="presentation"><a href="#invoice_footer" aria-controls="invoice_footer" role="tab" data-toggle="tab"><?php echo e(trans('texts.invoice_footer')); ?></a></li>
                    <li role="presentation"><a href="#quote_terms" aria-controls="quote_terms" role="tab" data-toggle="tab"><?php echo e(trans('texts.quote_terms')); ?></a></li>
                    <?php if($account->hasFeature(FEATURE_DOCUMENTS)): ?>
                        <li role="presentation"><a href="#documents" aria-controls="documents" role="tab" data-toggle="tab">
                            <?php echo e(trans('texts.documents')); ?>

                            <?php if($count = $account->defaultDocuments->count()): ?>
                                (<?php echo e($count); ?>)
                            <?php endif; ?>
                        </a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="invoice_terms">
                    <div class="panel-body">
                        <?php echo Former::textarea('invoice_terms')
                                ->label(trans('texts.default_invoice_terms'))
                                ->rows(8)
                                ->raw(); ?>

                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="invoice_footer">
                    <div class="panel-body">
                        <?php echo Former::textarea('invoice_footer')
                                ->label(trans('texts.default_invoice_footer'))
                                ->rows(8)
                                ->raw(); ?>

                        <?php if($account->hasFeature(FEATURE_REMOVE_CREATED_BY) && ! $account->isTrial()): ?>
                            <div class="help-block">
                                <?php echo e(trans('texts.invoice_footer_help')); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="quote_terms">
                    <div class="panel-body">
                        <?php echo Former::textarea('quote_terms')
                                ->label(trans('texts.default_quote_terms'))
                                ->rows(8)
                                ->raw(); ?>

                    </div>
                </div>
                <?php if($account->hasFeature(FEATURE_DOCUMENTS)): ?>
                    <div role="tabpanel" class="tab-pane" id="documents">
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="col-lg-12 col-sm-12">
                                    <div role="tabpanel" class="tab-pane" id="attached-documents" style="position:relative;z-index:9">
                                        <div id="document-upload">
                                            <div class="dropzone">
                                                <!--
                                                <div data-bind="foreach: documents">
                                                    <input type="hidden" name="document_ids[]" data-bind="value: public_id"/>
                                                </div>
                                                -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>



    <?php if(Auth::user()->hasFeature(FEATURE_INVOICE_SETTINGS)): ?>
        <center>
            <?php echo Button::success(trans('texts.save'))->large()->submit()->appendIcon(Icon::create('floppy-disk')); ?>

        </center>
    <?php endif; ?>

    <div class="modal fade" id="patternHelpModal" tabindex="-1" role="dialog" aria-labelledby="patternHelpModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="min-width:150px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="patternHelpModalLabel"><?php echo e(trans('texts.pattern_help_title')); ?></h4>
                </div>

                <div class="container" style="width: 100%; padding-bottom: 0px !important">
                <div class="panel panel-default">
                <div class="panel-body">
                    <p><?php echo e(trans('texts.pattern_help_1')); ?></p>
                    <p><?php echo e(trans('texts.pattern_help_2')); ?></p>
                    <ul>
                        <?php $__currentLoopData = \App\Models\Invoice::$patternFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($field == 'date:'): ?>
                                <li>{$date:format} - <?php echo link_to(PHP_DATE_FORMATS, trans('texts.see_options'), ['target' => '_blank']); ?></li>
                            <?php elseif(strpos($field, 'client') !== false): ?>
                                <li class="hide-client">{$<?php echo e($field); ?>}</li>
                            <?php else: ?>
                                <li>{$<?php echo e($field); ?>}</li>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <p class="hide-client"><?php echo e(trans('texts.pattern_help_3', [
                            'example' => '{$year}-{$counter}',
                            'value' => date('Y') . '-0001'
                        ])); ?></p>
                </div>
                </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo e(trans('texts.close')); ?></button>
                </div>

            </div>
        </div>
    </div>


	<?php echo Former::close(); ?>



	<script type="text/javascript">

  	function setQuoteNumberEnabled() {
			var disabled = $('#share_counter').prop('checked');
			$('#quote_number_counter').prop('disabled', disabled);
			$('#quote_number_counter').val(disabled ? '' : <?php echo json_encode($account->quote_number_counter); ?>);
		}

    function onNumberTypeChange(entityType) {
        var val = $('input[name=' + entityType + '_number_type]:checked').val();
        if (val == 'prefix') {
            $('.' + entityType + '-prefix').show();
            $('.' + entityType + '-pattern').hide();
        } else {
            $('.' + entityType + '-prefix').hide();
            $('.' + entityType + '-pattern').show();
        }
    }

    function onClientNumberEnabled() {
        var enabled = $('#client_number_enabled').is(':checked');
        if (enabled) {
            $('#clientNumberDiv').show();
            $('#client_number_counter').val(<?php echo e($account->client_number_counter ?: 1); ?>);
        } else {
            $('#clientNumberDiv').hide();
            $('#client_number_counter').val(0);
        }
    }

    function onCreditNumberEnabled() {
        var enabled = $('#credit_number_enabled').is(':checked');
        if (enabled) {
            $('#creditNumberDiv').show();
            $('#credit_number_counter').val(<?php echo e($account->credit_number_counter ?: 1); ?>);
        } else {
            $('#creditNumberDiv').hide();
            $('#credit_number_counter').val(0);
        }
    }

    function onResetFrequencyChange() {
        var val = $('#reset_counter_frequency_id').val();
        if (val) {
            $('.reset_counter_date_group').show();
        } else {
            $('.reset_counter_date_group').hide();
        }
    }

    $('.number-pattern .input-group-addon').click(function() {
        $('.hide-client').show();
        $('#patternHelpModal').modal('show');
    });

    $('.client-number-pattern .input-group-addon').click(function() {
        $('.hide-client').hide();
        $('#patternHelpModal').modal('show');
    });

    $('.credit-number-pattern .input-group-addon').click(function() {
        $('.hide-client').hide();
        $('#patternHelpModal').modal('show');
    });


    var defaultDocuments = <?php echo $account->defaultDocuments()->get(); ?>;

    $(function() {
    	setQuoteNumberEnabled();
        onNumberTypeChange('invoice');
        onNumberTypeChange('quote');
        onNumberTypeChange('client');
        onNumberTypeChange('credit');
        onClientNumberEnabled();
        onCreditNumberEnabled();
        onResetFrequencyChange();
        updateCheckboxes();

        $('#reset_counter_date').datepicker('update', '<?php echo e(Utils::fromSqlDate($account->reset_counter_date) ?: 'new Date()'); ?>');
        $('.reset_counter_date_group .input-group-addon').click(function() {
            toggleDatePicker('reset_counter_date');
        });

        <?php if($account->hasFeature(FEATURE_DOCUMENTS)): ?>
            <?php echo $__env->make('partials.dropzone', ['documentSource' => 'defaultDocuments', 'isDefault' => true], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php endif; ?>
    });

    $('#require_approve_quote').change(updateCheckboxes);

    function updateCheckboxes() {
        var checked = $('#require_approve_quote').is(':checked');
        $('#auto_convert_quote').prop('disabled', ! checked);
        $('#allow_approve_expired_quote').prop('disabled', ! checked);
    }

	</script>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('onReady'); ?>
    $('#custom_invoice_label1').focus();
<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>