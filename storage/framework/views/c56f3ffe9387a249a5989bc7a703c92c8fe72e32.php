<?php $__env->startSection('head'); ?>
    ##parent-placeholder-1a954628a960aaef81d7b2d4521929579f3541e6##

    <?php echo $__env->make('money_script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('proposals.grapesjs_header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php echo Former::open($url)
            ->method($method)
            ->onsubmit('return onFormSubmit(event)')
            ->id('mainForm')
            ->autocomplete('off')
            ->addClass('warn-on-exit')
            ->rules([
                'invoice_id' => 'required',
            ]); ?>


    <?php if($proposal): ?>
        <?php echo Former::populate($proposal); ?>

    <?php endif; ?>

    <span style="display:none">
        <?php echo Former::text('public_id'); ?>

        <?php echo Former::text('action'); ?>

        <?php echo Former::text('html'); ?>

        <?php echo Former::text('css'); ?>

    </span>

    <div class="row">
		<div class="col-lg-12">
            <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo Former::select('invoice_id')->addOption('', '')
                                ->label(trans('texts.quote'))
                                ->addGroupClass('invoice-select'); ?>

                        <?php echo Former::select('proposal_template_id')->addOption('', '')
                                ->label(trans('texts.template'))
                                ->addGroupClass('template-select'); ?>


                    </div>
                    <div class="col-md-6">
                        <?php echo Former::textarea('private_notes')
                                ->style('height: 100px'); ?>

                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <?php if(Auth::user()->canCreateOrEdit(ENTITY_PROPOSAL, $proposal)): ?>
    <center class="buttons">
        <?php echo Button::normal(trans('texts.cancel'))
                ->appendIcon(Icon::create('remove-circle'))
                ->asLinkTo(HTMLUtils::previousUrl('/proposals')); ?>


        <?php if($proposal): ?>
            <?php echo Button::primary(trans('texts.download'))
                    ->withAttributes(['onclick' => 'onDownloadClick()'])
                    ->appendIcon(Icon::create('download-alt')); ?>

        <?php endif; ?>

        <?php echo Button::success(trans("texts.save"))
                ->withAttributes(['id' => 'saveButton'])
                ->submit()
                ->appendIcon(Icon::create('floppy-disk')); ?>


        <?php echo Button::info(trans('texts.email'))
                ->withAttributes(['id' => 'emailButton', 'onclick' => 'onEmailClick()'])
                ->appendIcon(Icon::create('send')); ?>


        <?php if($proposal): ?>
            <?php echo DropdownButton::normal(trans('texts.more_actions'))
                    ->withContents($proposal->present()->moreActions()); ?>

        <?php endif; ?>

    </center>
    <?php endif; ?>

    <?php echo Former::close(); ?>


    <div id="gjs"></div>

    <script type="text/javascript">
    var invoices = <?php echo $invoices; ?>;
    var invoiceMap = {};

    var templates = <?php echo $templates; ?>;
    var templateMap = {};
    var isFormSubmitting = false;

    function onFormSubmit() {
        // prevent duplicate form submissions
        if (isFormSubmitting) {
            return;
        }
        isFormSubmitting = true;
        $('#saveButton, #emailButton').prop('disabled', true);

        $('#html').val(grapesjsEditor.getHtml());
        $('#css').val(grapesjsEditor.getCss());

        return true;
    }

    function onEmailClick() {
        sweetConfirm(function() {
            $('#action').val('email');
            $('#saveButton').click();
        })
    }

    <?php if($proposal): ?>
        function onDownloadClick() {
            location.href = "<?php echo e(url("/proposals/{$proposal->public_id}/download")); ?>";
        }
    <?php endif; ?>

    function loadTemplate() {
        var templateId = $('select#proposal_template_id').val();
        var template = templateMap[templateId];

        if (! template) {
            return;
        }

        var html = mergeTemplate(template.html);

        grapesjsEditor.CssComposer.getAll().reset();
        grapesjsEditor.setComponents(html);
        grapesjsEditor.setStyle(template.css);
    }

    function mergeTemplate(html) {
        var invoiceId = $('select#invoice_id').val();
        var invoice = invoiceMap[invoiceId];

        if (!invoice) {
            return html;
        }

        invoice.account = <?php echo auth()->user()->account->load('country'); ?>;
        invoice.contact = invoice.client.contacts[0];

        var regExp = new RegExp(/\$[a-z][\w\.]*/g);
        var matches = html.match(regExp);

        if (matches) {
            for (var i=0; i<matches.length; i++) {
                var match = matches[i];

                field = match.replace('$quote.', '$');
                field = field.substring(1, field.length);
                field = toSnakeCase(field);

                if (field == 'quote_number') {
                    field = 'invoice_number';
                } else if (field == 'valid_until') {
                    field = 'due_date';
                } else if (field == 'quote_date') {
                    field = 'invoice_date';
                } else if (field == 'footer') {
                    field = 'invoice_footer';
                } else if (match == '$account.phone') {
                    field = 'account.work_phone';
                } else if (match == '$client.phone') {
                    field = 'client.phone';
                }

                if (field == 'logo_url') {
                    var value = "<?php echo e($account->getLogoURL()); ?>";
                } else if (field == 'quote_image_url') {
                    var value = "<?php echo e(asset('/images/quote.png')); ?>";
                } else if (match == '$client.name') {
                    var value = getClientDisplayName(invoice.client);
                } else {
                    var value = getDescendantProp(invoice, field) || ' ';
                }

                value = doubleDollarSign(value) + '';
                value = value.replace(/\n/g, "\\n").replace(/\r/g, "\\r");

                if (['amount', 'partial', 'client.balance', 'client.paid_to_date'].indexOf(field) >= 0) {
                    value = formatMoneyInvoice(value, invoice);
                } else if (['invoice_date', 'due_date', 'partial_due_date'].indexOf(field) >= 0) {
                    value = moment.utc(value).format('<?php echo e($account->getMomentDateFormat()); ?>');
                }

                html = html.replace(match, value);
            }
        }

        return html;
    }

    <?php if($proposal): ?>
        function onArchiveClick() {
            submitForm_proposal('archive', <?php echo e($proposal->id); ?>);
    	}

        function onDeleteClick() {
            submitForm_proposal('delete', <?php echo e($proposal->id); ?>);
        }
    <?php endif; ?>

    $(function() {
        var invoiceId = <?php echo e(! empty($invoicePublicId) ? $invoicePublicId : 0); ?>;
        var $invoiceSelect = $('select#invoice_id');
        for (var i = 0; i < invoices.length; i++) {
            var invoice = invoices[i];
            invoiceMap[invoice.public_id] = invoice;
            $invoiceSelect.append(new Option(invoice.invoice_number + ' - ' + getClientDisplayName(invoice.client), invoice.public_id));
        }
        <?php echo $__env->make('partials/entity_combobox', ['entityType' => ENTITY_INVOICE], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        if (invoiceId) {
            var invoice = invoiceMap[invoiceId];
            if (invoice) {
                $invoiceSelect.val(invoice.public_id);
                setComboboxValue($('.invoice-select'), invoice.public_id, invoice.invoice_number + ' - ' + getClientDisplayName(invoice.client));
            }
        }
        $invoiceSelect.change(loadTemplate);

        var templateId = <?php echo e(! empty($templatePublicId) ? $templatePublicId : 0); ?>;
        var $proposal_templateSelect = $('select#proposal_template_id');
        for (var i = 0; i < templates.length; i++) {
            var template = templates[i];
            templateMap[template.public_id] = template;
            $proposal_templateSelect.append(new Option(template.name, template.public_id));
        }
        <?php echo $__env->make('partials/entity_combobox', ['entityType' => ENTITY_PROPOSAL_TEMPLATE], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        if (templateId) {
            var template = templateMap[templateId];
            $proposal_templateSelect.val(template.public_id);
            setComboboxValue($('.template-select'), template.public_id, template.name);
        }
        $proposal_templateSelect.change(loadTemplate);
	})

    </script>

    <?php echo $__env->make('partials.bulk_form', ['entityType' => ENTITY_PROPOSAL], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('proposals.grapesjs', ['entity' => $proposal], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <script type="text/javascript">

    $(function() {
        grapesjsEditor.on('canvas:drop', function(event, block) {
            if (! block.attributes || block.attributes.type != 'image') {
                var html = mergeTemplate(grapesjsEditor.getHtml());
                grapesjsEditor.setComponents(html);
            }
        });

        <?php if(! $proposal && $templatePublicId): ?>
            loadTemplate();
        <?php endif; ?>

        <?php if(request()->show_assets): ?>
            setTimeout(function() {
                grapesjsEditor.runCommand('open-assets');
            }, 500);
        <?php endif; ?>
    });

    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>