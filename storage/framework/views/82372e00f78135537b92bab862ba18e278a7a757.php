<?php $__env->startSection('head'); ?>
    ##parent-placeholder-1a954628a960aaef81d7b2d4521929579f3541e6##

    <script src="<?php echo e(asset('js/daterangepicker.min.js')); ?>" type="text/javascript"></script>
    <link href="<?php echo e(asset('css/daterangepicker.css')); ?>" rel="stylesheet" type="text/css"/>

    <?php echo $__env->make('money_script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php $__currentLoopData = $account->getFontFolders(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $font): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <script src="<?php echo e(asset('js/vfs_fonts/'.$font.'.js')); ?>" type="text/javascript"></script>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <script src="<?php echo e(asset('pdf.built.js')); ?>?no_cache=<?php echo e(NINJA_VERSION); ?>" type="text/javascript"></script>

    <script>

        var invoiceDesign = JSON.stringify(<?php echo //Utils::getFromCache($account->invoice_design_id ?: 1, 'invoiceDesigns')->pdfmake
            Utils::getFromCache(1, 'invoiceDesigns')->pdfmake; ?>);
        var invoiceFonts = <?php echo Cache::get('fonts'); ?>;

        var statementStartDate = moment("<?php echo e($startDate); ?>");
		var statementEndDate = moment("<?php echo e($endDate); ?>");
        var chartQuarter = moment().quarter();
        var dateRanges = <?php echo $account->present()->dateRangeOptions; ?>;

        function getPDFString(cb) {
            invoice.is_statement = true;
            invoice.image = window.accountLogo;
            invoice.features = {
                  customize_invoice_design:<?php echo e($account->hasFeature(FEATURE_CUSTOMIZE_INVOICE_DESIGN) ? 'true' : 'false'); ?>,
                  remove_created_by:<?php echo e($account->hasFeature(FEATURE_REMOVE_CREATED_BY) ? 'true' : 'false'); ?>,
                  invoice_settings:<?php echo e($account->hasFeature(FEATURE_INVOICE_SETTINGS) ? 'true' : 'false'); ?>

              };

            generatePDF(invoice, invoiceDesign, true, cb);
        }

        $(function() {
            if (isStorageSupported()) {
				var lastRange = localStorage.getItem('last:statement_range');
                var lastStatusId = localStorage.getItem('last:statement_status_id');
                var lastShowPayments = localStorage.getItem('last:statement_show_payments');
                var lastShowAging = localStorage.getItem('last:statement_show_aging');
				lastRange = dateRanges[lastRange];
				if (lastRange) {
					statementStartDate = lastRange[0];
					statementEndDate = lastRange[1];
				}
                if (lastStatusId) {
                    $('#status_id').val(lastStatusId);
                }
                if (lastShowPayments) {
                    $('#show_payments').prop('checked', true);
                }
                if (lastShowAging) {
                    $('#show_aging').prop('checked', true);
                }
			}

            // Initialize date range selector
            function cb(start, end, label) {
                statementStartDate = start;
                statementEndDate = end;
                $('#reportrange span').html(start.format('<?php echo e($account->getMomentDateFormat()); ?>') + ' - ' + end.format('<?php echo e($account->getMomentDateFormat()); ?>'));
                $('#start_date').val(start.format('YYYY-MM-DD'));
                $('#end_date').val(end.format('YYYY-MM-DD'));

				if (isStorageSupported() && label && label != "<?php echo e(trans('texts.custom_range')); ?>") {
					localStorage.setItem('last:statement_range', label);
				}

                refreshData();
            }

            $('#reportrange').daterangepicker({
                locale: {
                    format: "<?php echo e($account->getMomentDateFormat()); ?>",
                    customRangeLabel: "<?php echo e(trans('texts.custom_range')); ?>",
                    applyLabel: "<?php echo e(trans('texts.apply')); ?>",
                    cancelLabel: "<?php echo e(trans('texts.cancel')); ?>",
                },
                startDate: statementStartDate,
                endDate: statementEndDate,
                linkedCalendars: false,
				ranges: dateRanges,
            }, cb);

            cb(statementStartDate, statementEndDate);
        });

        function refreshData() {
            var statusId = $('#status_id').val();
            if (statusId == <?php echo e(INVOICE_STATUS_UNPAID); ?>) {
                $('#reportrange').css('color', '#AAA');
                $('#reportrange').css('pointer-events', 'none');
            } else {
                $('#reportrange').css('color', '#000');
                $('#reportrange').css('pointer-events', 'auto');
            }

            var url = '<?php echo e(request()->url()); ?>' +
                '?status_id=' + statusId +
                '&start_date=' + statementStartDate.format('YYYY-MM-DD') +
                '&end_date=' + statementEndDate.format('YYYY-MM-DD') +
                '&show_payments=' + ($('#show_payments').is(':checked') ? '1' : '') +
                '&show_aging=' + ($('#show_aging').is(':checked') ? '1' : '') +
                '&json=true';

            $.get(url, function(response) {
                invoice = currentInvoice = JSON.parse(response);
                refreshPDF();
            });

            if (isStorageSupported()) {
                localStorage.setItem('last:statement_status_id', $('#status_id').val());
                localStorage.setItem('last:statement_show_payments', $('#show_payments').is(':checked') ? '1' : '');
                localStorage.setItem('last:statement_show_aging', $('#show_aging').is(':checked') ? '1' : '');
            }
        }

        function onDownloadClick() {
            var doc = generatePDF(invoice, invoiceDesign, true);
            doc.save("<?php echo e(str_replace(' ', '_', trim($client->getDisplayName())) . '-' . trans('texts.statement')); ?>" + '.pdf');
        }

    </script>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php if(empty($extends)): ?>
        <div class="pull-right">
            <?php echo Button::normal(trans('texts.download'))
                    ->withAttributes(['onclick' => 'onDownloadClick()'])
                    ->appendIcon(Icon::create('download-alt')); ?>

            <?php echo Button::primary(trans('texts.view_client'))
                    ->asLinkTo($client->present()->url); ?>

        </div>

        <ol class="breadcrumb pull-left">
          <li><?php echo e(link_to('/clients', trans('texts.clients'))); ?></li>
          <li class='active'><?php echo e($client->getDisplayName()); ?></li>
        </ol>

        <p>&nbsp;</p>
        <p>&nbsp;</p>
    <?php endif; ?>

    <div class="well" style="background: #eeeeee; padding-bottom:30px;">
        <div class="pull-left">
            <?php echo Former::inline_open()->onchange('refreshData()'); ?>


            <?php echo e(trans('texts.status')); ?>


            &nbsp;&nbsp;

            <?php echo Former::select('status_id')
                    ->label('status')
                    ->addOption(trans('texts.all'), 'false')
                    ->addOption(trans('texts.unpaid'), INVOICE_STATUS_UNPAID)
                    ->addOption(trans('texts.paid'), INVOICE_STATUS_PAID); ?>


            &nbsp;&nbsp;&nbsp;&nbsp;

            <?php echo e(trans('texts.date_range')); ?>


            &nbsp;&nbsp;

            <span id="reportrange" style="background: #f9f9f9; cursor: pointer; padding: 9px 14px; border: 1px solid #dfe0e1; margin-top: 0px;">
                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                <span></span> <b class="caret"></b>
            </span>

            <div style="display:none">
                <?php echo Former::text('start_date'); ?>

                <?php echo Former::text('end_date'); ?>

            </div>

            &nbsp;&nbsp;&nbsp;&nbsp;

            <?php if(empty($extends)): ?>
                <?php echo Former::checkbox('show_payments')->text('show_payments'); ?>

                &nbsp;&nbsp;&nbsp;&nbsp;
                <?php echo Former::checkbox('show_aging')->text('show_aging'); ?>

            <?php else: ?>
                <?php echo Former::checkbox('show_payments')->text('show_payments')->inline(); ?>

                &nbsp;&nbsp;&nbsp;&nbsp;
                <?php echo Former::checkbox('show_aging')->text('show_aging')->inline(); ?>

            <?php endif; ?>

            <?php echo Former::close(); ?>


        </div>

        <?php if(! empty($extends)): ?>
            <div class="pull-right">
                <?php echo Button::normal(trans('texts.download') . ' &nbsp; ')
                        ->withAttributes(['onclick' => 'onDownloadClick()'])
                        ->appendIcon(Icon::create('download-alt')); ?>

            </div>
        <?php endif; ?>
        &nbsp;
    </div>

    <?php echo $__env->make('invoices.pdf', ['account' => $account], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make(! empty($extends) ? $extends : 'header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>