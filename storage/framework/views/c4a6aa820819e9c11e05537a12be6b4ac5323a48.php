<?php $__env->startSection('head'); ?>
	##parent-placeholder-1a954628a960aaef81d7b2d4521929579f3541e6##

    <?php echo $__env->make('money_script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

	<script src="<?php echo e(asset('js/Chart.min.js')); ?>" type="text/javascript"></script>
    <script src="<?php echo e(asset('js/daterangepicker.min.js')); ?>" type="text/javascript"></script>
    <link href="<?php echo e(asset('css/daterangepicker.css')); ?>" rel="stylesheet" type="text/css"/>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<script type="text/javascript">

    <?php if(Auth::user()->hasPermission('view_dashboard')): ?>
        function loadChart(data) {
            var ctx = document.getElementById('chart-canvas').getContext('2d');
            if (window.myChart) {
                window.myChart.config.data = data;
                window.myChart.config.options.scales.xAxes[0].time.unit = chartGroupBy.toLowerCase();
                window.myChart.config.options.scales.xAxes[0].time.round = chartGroupBy.toLowerCase();
                window.myChart.update();
            } else {
                $('#progress-div').hide();
                $('#chart-canvas').fadeIn();
                window.myChart = new Chart(ctx, {
                    type: 'line',
                    data: data,
                    options: {
                        tooltips: {
                            mode: 'x-axis',
                            titleFontSize: 15,
                            titleMarginBottom: 12,
                            bodyFontSize: 15,
                            bodySpacing: 10,
                            callbacks: {
                                title: function(item) {
                                    return moment(item[0].xLabel).format("<?php echo e($account->getMomentDateFormat()); ?>");
                                },
                                label: function(item, data) {
                                    if (item.datasetIndex == 0) {
                                        var label = " <?php echo trans('texts.invoices'); ?>: ";
                                    } else if (item.datasetIndex == 1) {
                                        var label = " <?php echo trans('texts.payments'); ?>: ";
                                    } else if (item.datasetIndex == 2) {
                                        var label = " <?php echo trans('texts.expenses'); ?>: ";
                                    }

                                    return label + formatMoney(item.yLabel, realCurrencyId, account.country_id);
                                }
                            }
                        },
                        title: {
                            display: false,
                            fontSize: 18,
                            text: '<?php echo e(trans('texts.total_revenue')); ?>'
                        },
                        scales: {
                            xAxes: [{
                                type: 'time',
                                time: {
                                    unit: chartGroupBy,
                                    round: chartGroupBy,
                                },
                                gridLines: {
                                    display: false,
                                },
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    callback: function(label, index, labels) {
                                        return formatMoney(label, realCurrencyId, account.country_id);
                                    }
                                },
                            }]
                        }
                    }
                });
            }
        }

        var account = <?php echo $account; ?>;
        var chartGroupBy = 'day';
        var realCurrencyId = <?php echo e($account->getCurrencyId()); ?>;
        var chartCurrencyId = <?php echo e($account->getCurrencyId()); ?>;
        var chartQuarter = moment().quarter();
		var dateRanges = <?php echo $account->present()->dateRangeOptions; ?>;
		var chartStartDate;
        var chartEndDate;
        var dashboardTotalsInAllCurrenciesHelp;

        $(function() {

            // Initialize date range selector
			chartStartDate = moment().subtract(29, 'days');
	        chartEndDate = moment();
            dashboardTotalsInAllCurrenciesHelp = $("#dashboard-totals-in-all-currencies-help");
			lastRange = false;

			if (isStorageSupported()) {
				lastRange = localStorage.getItem('last:dashboard_range');
				dateRange = dateRanges[lastRange];

				if (dateRange) {
					chartStartDate = dateRange[0];
					chartEndDate = dateRange[1];
				}

				<?php if(count($currencies) > 1): ?>
					var currencyId = localStorage.getItem('last:dashboard_currency_id');
					if (currencyId) {
						chartCurrencyId = currencyId;
						$("#currency-btn-group [data-button=\"" + chartCurrencyId + "\"]").addClass("active").siblings().removeClass("active");
					}
				<?php endif; ?>

				var groupBy = localStorage.getItem('last:dashboard_group_by');
				if (groupBy) {
					chartGroupBy = groupBy;
					$("#group-btn-group [data-button=\"" + groupBy + "\"]").addClass("active").siblings().removeClass("active");
				}
			}

            function cb(start, end, label) {
                $('#reportrange span').html(start.format('<?php echo e($account->getMomentDateFormat()); ?>') + ' - ' + end.format('<?php echo e($account->getMomentDateFormat()); ?>'));
                chartStartDate = start;
                chartEndDate = end;
				$('.range-label-div').show();
				if (label) {
					$('.range-label-div').text(label);
				}
                displayTotalsNote();
                loadData();

				if (isStorageSupported() && label && label != "<?php echo e(trans('texts.custom_range')); ?>") {
					localStorage.setItem('last:dashboard_range', label);
				}
            }

            $('#reportrange').daterangepicker({
                locale: {
					format: "<?php echo e($account->getMomentDateFormat()); ?>",
					customRangeLabel: "<?php echo e(trans('texts.custom_range')); ?>",
					applyLabel: "<?php echo e(trans('texts.apply')); ?>",
					cancelLabel: "<?php echo e(trans('texts.cancel')); ?>",
                },
				startDate: chartStartDate,
                endDate: chartEndDate,
                linkedCalendars: false,
                ranges: dateRanges,
            }, cb);

            cb(chartStartDate, chartEndDate, lastRange);

            $("#currency-btn-group > .btn").click(function(){
                var t = $(this);

                t.addClass("active").siblings().removeClass("active");

                if(t.attr("data-button") === "totals"){
                    realCurrencyId  = account.currency.id;
                    chartCurrencyId = "totals";
                }else {
                    realCurrencyId  = currencyMap[t.text()].id;
                    chartCurrencyId = realCurrencyId;
                }
                displayTotalsNote();

                loadData();
				if (isStorageSupported()) {
					localStorage.setItem('last:dashboard_currency_id', $(this).attr('data-button'));
				}
            });

            $("#group-btn-group > .btn").click(function(){
                $(this).addClass("active").siblings().removeClass("active");
                chartGroupBy = $(this).attr('data-button');
                displayTotalsNote();
                loadData();
				if (isStorageSupported()) {
					localStorage.setItem('last:dashboard_group_by', chartGroupBy);
				}
            });

            function loadData() {
                var includeExpenses = "<?php echo e($showExpenses ? 'true' : 'false'); ?>";
                var url = '<?php echo e(url('/dashboard_chart_data')); ?>/' + chartGroupBy + '/' + chartStartDate.format('YYYY-MM-DD') + '/' + chartEndDate.format('YYYY-MM-DD') + '/' + chartCurrencyId + '/' + includeExpenses;
                $.get(url, function(response) {
                    response = JSON.parse(response);
                    loadChart(response.data);

                    var totals = response.totals;
                    $('.revenue-div').text(formatMoney(totals.revenue, realCurrencyId, account.country_id));
                    $('.outstanding-div').text(formatMoney(totals.balance, realCurrencyId, account.country_id));
                    $('.expenses-div').text(formatMoney(totals.expenses, realCurrencyId, account.country_id));
                    $('.average-div').text(formatMoney(totals.average, realCurrencyId, account.country_id));

                    $('.currency').hide();
                    $('.currency_' + chartCurrencyId).show();

					// add blank values to fix layout
					var divs = ['revenue', 'expenses', 'outstanding']
					for (var i=0; i<divs.length; i++) {
						var type = divs[i];
						if (!$('.' + type + '-panel .currency_' + chartCurrencyId).length) {
							$('.' + type + '-panel .currency_blank').text(formatMoney(0, realCurrencyId)).show();
						}
					}
                })
            }

            function displayTotalsNote() {
                if(chartCurrencyId === "totals"){
                    dashboardTotalsInAllCurrenciesHelp.show();
                }else {
                    dashboardTotalsInAllCurrenciesHelp.hide();
                }
            }

        });
    <?php else: ?>
        $(function() {
            $('.currency').show();
        })
    <?php endif; ?>

</script>


<?php if($invoiceExchangeRateMissing): ?>
    <div class="row" id="dashboard-totals-in-all-currencies-help" style="display: none">
        <div class="col-xs-12">
            <div class="alert alert-warning custom-message"><?php echo trans('texts.dashboard_totals_in_all_currencies_help', [
                'link' => link_to('/settings/invoice_settings#invoice_fields', trans('texts.custom_field'), ['target' => '_blank']),
                'name' => trans('texts.exchange_rate')
            ]); ?></div>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-2">
        <ol class="breadcrumb"><li class='active'><?php echo e(trans('texts.dashboard')); ?></li></ol>
    </div>
    <?php if(count($tasks)): ?>
        <div class="col-md-2" style="padding-top:6px">
            <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo Button::primary($task->present()->titledName)->small()->asLinkTo($task->present()->url); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="col-md-8">
    <?php else: ?>
        <div class="col-md-10">
    <?php endif; ?>
        <?php if(Auth::user()->hasPermission('view_dashboard')): ?>
        <div class="pull-right">
            <?php if(count($currencies) > 1): ?>
            <div id="currency-btn-group" class="btn-group" role="group" style="border: 1px solid #ccc;">
              <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button type="button" class="btn btn-normal <?php echo e(array_values($currencies)[0] == $val ? 'active' : ''); ?>"
                    data-button="<?php echo e($key); ?>" style="font-weight:normal !important;background-color:white"><?php echo e($val); ?></button>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <button type="button" class="btn btn-normal"
                        data-button="totals" style="font-weight:normal !important;background-color:white"><?php echo e(trans('texts.totals')); ?></button>
            </div>
            <?php endif; ?>
            <div id="group-btn-group" class="btn-group" role="group" style="border: 1px solid #ccc; margin-left:18px">
              <button type="button" class="btn btn-normal active" data-button="day" style="font-weight:normal !important;background-color:white"><?php echo e(trans('texts.day')); ?></button>
              <button type="button" class="btn btn-normal" data-button="week" style="font-weight:normal !important;background-color:white"><?php echo e(trans('texts.week')); ?></button>
              <button type="button" class="btn btn-normal" data-button="month" style="font-weight:normal !important;background-color:white"><?php echo e(trans('texts.month')); ?></button>
            </div>
            <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 9px 14px; border: 1px solid #ccc; margin-top: 0px; margin-left:18px">
                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                <span></span> <b class="caret"></b>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if($account->company->hasEarnedPromo()): ?>
	<?php echo $__env->make('partials/discount_promo', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php elseif($showBlueVinePromo): ?>
    <?php echo $__env->make('partials/bluevine_promo', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php endif; ?>

<?php if($showWhiteLabelExpired): ?>
	<?php echo $__env->make('partials/white_label_expired', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php endif; ?>

<?php if(Auth::user()->hasPermission('admin')): ?>
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-body revenue-panel">
                <div style="overflow:hidden">
                    <div class="<?php echo e($headerClass); ?>">
                        <?php echo e(trans('texts.total_revenue')); ?>

                    </div>
                    <div class="revenue-div in-bold pull-right" style="color:#337ab7">
                    </div>
                    <div class="in-bold">
                        <?php if(count($paidToDate)): ?>
                            <?php $__currentLoopData = $paidToDate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="currency currency_<?php echo e($item->currency_id ?: $account->getCurrencyId()); ?>" style="display:none">
                                    <?php echo e(Utils::formatMoney($item->value, $item->currency_id)); ?>

                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <div class="currency currency_totals" style="display:none">
                                    <?php echo e(Utils::formatMoney($paidToDateTotal, $account->getCurrencyId())); ?>

                                </div>
                        <?php else: ?>
                            <div class="currency currency_<?php echo e($account->getCurrencyId()); ?>" style="display:none">
                                <?php echo e(Utils::formatMoney(0)); ?>

                            </div>
                        <?php endif; ?>
						<div class="currency currency_blank" style="display:none">
							&nbsp;
						</div>
                    </div>
					<div class="range-label-div <?php echo e($footerClass); ?> pull-right" style="color:#337ab7;font-size:16px;display:none;">
						<?php echo e(trans('texts.last_30_days')); ?>

					</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-body expenses-panel">
                <div style="overflow:hidden">
                    <?php if($showExpenses): ?>
                        <div class="<?php echo e($headerClass); ?>">
                            <?php echo e(trans('texts.total_expenses')); ?>

                        </div>
                        <div class="expenses-div in-bold pull-right" style="color:#337ab7">
                        </div>
                        <div class="in-bold">
                            <?php $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="currency currency_<?php echo e($item->currency_id ?: $account->getCurrencyId()); ?>" style="display:none">
                                    <?php echo e(Utils::formatMoney($item->value, $item->currency_id)); ?><br/>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <div class="currency currency_totals" style="display:none">
                                    <?php echo e(Utils::formatMoney($expensesTotals, $account->getCurrencyId())); ?><br/>
                                </div>
							<div class="currency currency_blank" style="display:none">
								&nbsp;
							</div>
                        </div>
                    <?php else: ?>
                        <div class="<?php echo e($headerClass); ?>">
                            <?php echo e(trans('texts.average_invoice')); ?>

                        </div>
                        <div class="average-div in-bold pull-right" style="color:#337ab7">
                        </div>
                        <div class="in-bold">
                            <?php if(count($averageInvoice)): ?>
                                <?php $__currentLoopData = $averageInvoice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="currency currency_<?php echo e($item->currency_id ?: $account->getCurrencyId()); ?>" style="display:none">
                                        <?php echo e(Utils::formatMoney($item->invoice_avg, $item->currency_id)); ?><br/>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <div class="currency currency_totals" style="display:none">
                                        <?php echo e(Utils::formatMoney($averageInvoiceTotal, $account->getCurrencyId())); ?><br/>
                                    </div>
                            <?php else: ?>
                                <div class="currency currency_<?php echo e($account->getCurrencyId()); ?>" style="display:none">
                                    <?php echo e(Utils::formatMoney(0)); ?>

                                </div>
                            <?php endif; ?>
							<div class="currency currency_blank" style="display:none">
								&nbsp;
							</div>
                        </div>
                    <?php endif; ?>
					<div class="range-label-div <?php echo e($footerClass); ?> pull-right" style="color:#337ab7;font-size:16px;display:none;">
						<?php echo e(trans('texts.last_30_days')); ?>

					</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-body outstanding-panel">
                <div style="overflow:hidden">
                    <div class="<?php echo e($headerClass); ?>">
                        <?php echo e(trans('texts.outstanding')); ?>

                    </div>
                    <div class="outstanding-div in-bold pull-right" style="color:#337ab7">
                    </div>
                    <div class="in-bold">
                        <?php if(count($balances)): ?>
                            <?php $__currentLoopData = $balances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="currency currency_<?php echo e($item->currency_id ?: $account->getCurrencyId()); ?>" style="display:none">
                                    <?php echo e(Utils::formatMoney($item->value, $item->currency_id)); ?><br/>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <div class="currency currency_totals" style="display:none">
                                    <?php echo e(Utils::formatMoney($balancesTotals, $account->getCurrencyId())); ?><br/>
                                </div>
                        <?php else: ?>
                            <div class="currency currency_<?php echo e($account->getCurrencyId()); ?>" style="display:none">
                                <?php echo e(Utils::formatMoney(0)); ?>

                            </div>
                        <?php endif; ?>
						<div class="currency currency_blank" style="display:none">
							&nbsp;
						</div>
                    </div>
					<div class="range-label-div <?php echo e($footerClass); ?> pull-right" style="color:#337ab7;font-size:16px;display:none;">
						<?php echo e(trans('texts.last_30_days')); ?>

					</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="progress-div" class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar"
                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
        </div>
        <canvas id="chart-canvas" height="70px" style="background-color:white;padding:20px;display:none"></canvas>
    </div>
</div>
<p>&nbsp;</p>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default dashboard" style="height:320px">
            <div class="panel-heading">
                <h3 class="panel-title in-bold-white">
                    <i class="glyphicon glyphicon-exclamation-sign"></i> <?php echo e(trans('texts.activity')); ?>

                    <?php if(Auth::user()->hasPermission('admin') && $invoicesSent): ?>
                        <div class="pull-right" style="font-size:14px;padding-top:4px">
							<?php if($invoicesSent == 1): ?>
								<?php echo e(trans('texts.invoice_sent', ['count' => $invoicesSent])); ?>

							<?php else: ?>
								<?php echo e(trans('texts.invoices_sent', ['count' => $invoicesSent])); ?>

							<?php endif; ?>
                        </div>
                    <?php endif; ?>
                </h3>
            </div>
            <ul class="panel-body list-group" style="height:276px;overflow-y:auto;">
                <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="list-group-item">
                    <span style="color:#888;font-style:italic"><?php echo e(Utils::timestampToDateString(strtotime($activity->created_at))); ?>:</span>
                    <?php echo $activity->getMessage(); ?>

                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default dashboard" style="height:320px;">
            <div class="panel-heading" style="margin:0; background-color: #f5f5f5 !important;">
                <h3 class="panel-title" style="color: black !important">
					<?php if(Auth::user()->hasPermission('admin')): ?>
	                    <?php if($showExpenses && count($averageInvoice)): ?>
	                        <div class="pull-right" style="font-size:14px;padding-top:4px;font-weight:bold">
	                            <?php $__currentLoopData = $averageInvoice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                                <span class="currency currency_<?php echo e($item->currency_id ?: $account->getCurrencyId()); ?>" style="display:none">
	                                    <?php echo e(trans('texts.average_invoice')); ?>

	                                    <?php echo e(Utils::formatMoney($item->invoice_avg, $item->currency_id)); ?> |
	                                </span>
	                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	                            <span class="average-div" style="color:#337ab7"/>
	                        </div>
						<?php endif; ?>
                    <?php endif; ?>
                    <i class="glyphicon glyphicon-ok-sign"></i> <?php echo e(trans('texts.recent_payments')); ?>

                </h3>
            </div>
            <div class="panel-body" style="height:274px;overflow-y:auto;">
                <table class="table table-striped">
                    <thead>
                        <th><?php echo e(trans('texts.invoice_number_short')); ?></th>
                        <th><?php echo e(trans('texts.client')); ?></th>
                        <th><?php echo e(trans('texts.payment_date')); ?></th>
                        <th><?php echo e(trans('texts.amount')); ?></th>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo \App\Models\Invoice::calcLink($payment); ?></td>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', [ENTITY_CLIENT, $payment])): ?>
                                <td><?php echo link_to('/clients/'.$payment->client_public_id, trim($payment->client_name) ?: (trim($payment->first_name . ' ' . $payment->last_name) ?: $payment->email)); ?></td>
                            <?php else: ?>
                                <td><?php echo e(trim($payment->client_name) ?: (trim($payment->first_name . ' ' . $payment->last_name) ?: $payment->email)); ?></td>
                            <?php endif; ?>
                            <td><?php echo e(Utils::fromSqlDate($payment->payment_date)); ?></td>
                            <td><?php echo e(Utils::formatMoney($payment->amount, $payment->currency_id ?: ($account->currency_id ?: DEFAULT_CURRENCY))); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default dashboard" style="height:320px;">
            <div class="panel-heading" style="margin:0; background-color: #f5f5f5 !important;">
                <h3 class="panel-title" style="color: black !important">
                    <i class="glyphicon glyphicon-time"></i> <?php echo e(trans('texts.upcoming_invoices')); ?>

                </h3>
            </div>
            <div class="panel-body" style="height:274px;overflow-y:auto;">
                <table class="table table-striped">
                    <thead>
                        <th><?php echo e(trans('texts.invoice_number_short')); ?></th>
                        <th><?php echo e(trans('texts.client')); ?></th>
                        <th><?php echo e(trans('texts.due_date')); ?></th>
                        <th><?php echo e(trans('texts.balance_due')); ?></th>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $upcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($invoice->invoice_type_id == INVOICE_TYPE_STANDARD): ?>
                                <tr>
                                    <td><?php echo \App\Models\Invoice::calcLink($invoice); ?></td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', [ENTITY_CLIENT, $invoice])): ?>
                                        <td><?php echo link_to('/clients/'.$invoice->client_public_id, trim($invoice->client_name) ?: (trim($invoice->first_name . ' ' . $invoice->last_name) ?: $invoice->email)); ?></td>
                                    <?php else: ?>
                                        <td><?php echo e(trim($invoice->client_name) ?: (trim($invoice->first_name . ' ' . $invoice->last_name) ?: $invoice->email)); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo e(Utils::fromSqlDate($invoice->due_date)); ?></td>
                                    <td><?php echo e(Utils::formatMoney($invoice->balance, $invoice->currency_id ?: ($account->currency_id ?: DEFAULT_CURRENCY))); ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default dashboard" style="height:320px">
            <div class="panel-heading" style="background-color:#777 !important">
                <h3 class="panel-title in-bold-white">
                    <i class="glyphicon glyphicon-time"></i> <?php echo e(trans('texts.invoices_past_due')); ?>

                </h3>
            </div>
            <div class="panel-body" style="height:274px;overflow-y:auto;">
                <table class="table table-striped">
                    <thead>
                        <th><?php echo e(trans('texts.invoice_number_short')); ?></th>
                        <th><?php echo e(trans('texts.client')); ?></th>
                        <th><?php echo e(trans('texts.due_date')); ?></th>
                        <th><?php echo e(trans('texts.balance_due')); ?></th>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pastDue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($invoice->invoice_type_id == INVOICE_TYPE_STANDARD): ?>
                                <tr>
                                    <td><?php echo \App\Models\Invoice::calcLink($invoice); ?></td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', [ENTITY_CLIENT, $invoice])): ?>
                                        <td><?php echo link_to('/clients/'.$invoice->client_public_id, trim($invoice->client_name) ?: (trim($invoice->first_name . ' ' . $invoice->last_name) ?: $invoice->email)); ?></td>
                                    <?php else: ?>
                                        <td><?php echo e(trim($invoice->client_name) ?: (trim($invoice->first_name . ' ' . $invoice->last_name) ?: $invoice->email)); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo e(Utils::fromSqlDate($invoice->due_date)); ?></td>
                                    <td><?php echo e(Utils::formatMoney($invoice->balance, $invoice->currency_id ?: ($account->currency_id ?: DEFAULT_CURRENCY))); ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if($hasQuotes): ?>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default dashboard" style="height:320px;">
                <div class="panel-heading" style="margin:0; background-color: #f5f5f5 !important;">
                    <h3 class="panel-title" style="color: black !important">
                        <i class="glyphicon glyphicon-time"></i> <?php echo e(trans('texts.upcoming_quotes')); ?>

                    </h3>
                </div>
                <div class="panel-body" style="height:274px;overflow-y:auto;">
                    <table class="table table-striped">
                        <thead>
                            <th><?php echo e(trans('texts.quote_number_short')); ?></th>
                            <th><?php echo e(trans('texts.client')); ?></th>
                            <th><?php echo e(trans('texts.valid_until')); ?></th>
                            <th><?php echo e(trans('texts.amount')); ?></th>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $upcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($invoice->invoice_type_id == INVOICE_TYPE_QUOTE): ?>
                                    <tr>
                                        <td><?php echo \App\Models\Invoice::calcLink($invoice); ?></td>
                                        <td><?php echo link_to('/clients/'.$invoice->client_public_id, trim($invoice->client_name) ?: (trim($invoice->first_name . ' ' . $invoice->last_name) ?: $invoice->email)); ?></td>
                                        <td><?php echo e(Utils::fromSqlDate($invoice->due_date)); ?></td>
                                        <td><?php echo e(Utils::formatMoney($invoice->balance, $invoice->currency_id ?: ($account->currency_id ?: DEFAULT_CURRENCY))); ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default dashboard" style="height:320px">
                <div class="panel-heading" style="background-color:#777 !important">
                    <h3 class="panel-title in-bold-white">
                        <i class="glyphicon glyphicon-time"></i> <?php echo e(trans('texts.expired_quotes')); ?>

                    </h3>
                </div>
                <div class="panel-body" style="height:274px;overflow-y:auto;">
                    <table class="table table-striped">
                        <thead>
                            <th><?php echo e(trans('texts.quote_number_short')); ?></th>
                            <th><?php echo e(trans('texts.client')); ?></th>
                            <th><?php echo e(trans('texts.valid_until')); ?></th>
                            <th><?php echo e(trans('texts.amount')); ?></th>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $pastDue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($invoice->invoice_type_id == INVOICE_TYPE_QUOTE): ?>
                                    <tr>
                                        <td><?php echo \App\Models\Invoice::calcLink($invoice); ?></td>
                                        <td><?php echo link_to('/clients/'.$invoice->client_public_id, trim($invoice->client_name) ?: (trim($invoice->first_name . ' ' . $invoice->last_name) ?: $invoice->email)); ?></td>
                                        <td><?php echo e(Utils::fromSqlDate($invoice->due_date)); ?></td>
                                        <td><?php echo e(Utils::formatMoney($invoice->balance, $invoice->currency_id ?: ($account->currency_id ?: DEFAULT_CURRENCY))); ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>