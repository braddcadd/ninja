<?php $__env->startSection('head'); ?>
	##parent-placeholder-1a954628a960aaef81d7b2d4521929579f3541e6##

	<?php echo $__env->make('money_script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

	<script src="<?php echo e(asset('js/Chart.min.js')); ?>" type="text/javascript"></script>

    <script src="<?php echo e(asset('js/daterangepicker.min.js')); ?>?no_cache=<?php echo e(NINJA_VERSION); ?>" type="text/javascript"></script>
    <link href="<?php echo e(asset('css/daterangepicker.css')); ?>?no_cache=<?php echo e(NINJA_VERSION); ?>" rel="stylesheet" type="text/css"/>

    <link href="<?php echo e(asset('css/tablesorter.css')); ?>?no_cache=<?php echo e(NINJA_VERSION); ?>" rel="stylesheet" type="text/css"/>
    <script src="<?php echo e(asset('js/tablesorter.min.js')); ?>?no_cache=<?php echo e(NINJA_VERSION); ?>" type="text/javascript"></script>

	<link href="<?php echo e(asset('css/select2.css')); ?>?no_cache=<?php echo e(NINJA_VERSION); ?>" rel="stylesheet" type="text/css"/>
    <script src="<?php echo e(asset('js/select2.min.js')); ?>?no_cache=<?php echo e(NINJA_VERSION); ?>" type="text/javascript"></script>

	<style type="text/css">
		table.tablesorter th {
			color: white;
			background-color: #777 !important;
		}
		.select2-selection {
			background-color: #f9f9f9 !important;
			width: 100%;
		}

		.tablesorter-column-selector label {
			display: block;
		}

		.tablesorter-column-selector input {
			margin-right: 8px;
		}
	</style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('top-right'); ?>
	<?php if(config('services.postmark') && auth()->user()->hasPermission('view_reports')): ?>
		<?php echo Button::normal(trans('texts.emails'))
				->asLinkTo(url('/reports/emails'))
				->appendIcon(Icon::create('envelope')); ?>

	<?php endif; ?>
	<?php echo Button::normal(trans('texts.calendar'))
			->asLinkTo(url('/reports/calendar'))
			->appendIcon(Icon::create('calendar')); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

	<?php if(!Utils::isPro()): ?>
	    <div class="alert alert-warning" style="font-size:larger;">
	    <center>
	        <?php echo trans('texts.pro_plan_reports', ['link'=>'<a href="javascript:showUpgradeModal()">' . trans('texts.pro_plan_remove_logo_link') . '</a>']); ?>

	    </center>
	    </div>
	<?php endif; ?>

    <script type="text/javascript">

		var chartStartDate = moment("<?php echo e($startDate); ?>");
		var chartEndDate = moment("<?php echo e($endDate); ?>");
        var chartQuarter = moment().quarter();
		var dateRanges = <?php echo $account->present()->dateRangeOptions; ?>;

		function resolveRange(range) {
			if (range == "<?php echo e(trans('texts.this_month')); ?>") {
				return 'this_month';
			} else if (range == "<?php echo e(trans('texts.last_month')); ?>") {
				return 'last_month';
			} else if (range == "<?php echo e(trans('texts.this_year')); ?>") {
				return 'this_year';
			} else if (range == "<?php echo e(trans('texts.last_year')); ?>") {
				return 'last_year';
			} else {
				return '';
			}
		}

        $(function() {

			if (isStorageSupported()) {
				var lastRange = localStorage.getItem('last:report_range');
				$('#range').val(resolveRange(lastRange));
				lastRange = dateRanges[lastRange];
				if (lastRange) {
					chartStartDate = lastRange[0];
					chartEndDate = lastRange[1];
				}
			}

            // Initialize date range selector
            function cb(start, end, label) {
                $('#reportrange span').html(start.format('<?php echo e($account->getMomentDateFormat()); ?>') + ' - ' + end.format('<?php echo e($account->getMomentDateFormat()); ?>'));
                $('#start_date').val(start.format('YYYY-MM-DD'));
                $('#end_date').val(end.format('YYYY-MM-DD'));
				if (label) {
					$('#range').val(resolveRange(label));
				}

				if (isStorageSupported() && label) {
					localStorage.setItem('last:report_range', label);
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

            cb(chartStartDate, chartEndDate);

        });

    </script>


    <?php echo Former::open()->addClass('report-form')->rules(['start_date' => 'required', 'end_date' => 'required']); ?>



    <div style="display:none">
		<?php echo Former::text('action')->forceValue(''); ?>

		<?php echo Former::text('range')->forceValue(''); ?>

		<?php echo Former::text('scheduled_report_id')->forceValue(''); ?>

    </div>

    <?php echo Former::populateField('start_date', $startDate); ?>

    <?php echo Former::populateField('end_date', $endDate); ?>


	<div class="row">
		<div class="col-lg-12">
            <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo trans('texts.report_settings'); ?></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">

						<?php echo Former::select('report_type')
								->data_bind("options: report_types, optionsText: 'transType', optionsValue: 'type', value: report_type")
								->label(trans('texts.type')); ?>


						<div class="form-group">
                            <label for="reportrange" class="control-label col-lg-4 col-sm-4">
                                <?php echo e(trans('texts.date_range')); ?>

                            </label>
                            <div class="col-lg-8 col-sm-8">
                                <div id="reportrange" style="background: #f9f9f9; cursor: pointer; padding: 9px 14px; border: 1px solid #dfe0e1; margin-top: 0px;">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                    <span></span> <b class="caret"></b>
                                </div>

                                <div style="display:none">
                                    <?php echo Former::text('start_date'); ?>

                                    <?php echo Former::text('end_date'); ?>

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">

						<?php echo Former::select('group')
									->data_bind("options: groups, optionsText: 'transPeriod', optionsValue: 'period', value: group"); ?>


						<span data-bind="visible: showSubgroup">
							<?php echo Former::select('subgroup')
										->data_bind("options: subgroups, optionsText: 'transField', optionsValue: 'field', value: subgroup"); ?>

						</span>

						<div id="statusField" style="display:none" data-bind="visible: showStatus">
							<div class="form-group">
								<label for="status_ids" class="control-label col-lg-4 col-sm-4"><?php echo e(trans('texts.status')); ?></label>
								<div class="col-lg-8 col-sm-8">
									<select name="status_ids[]" class="form-control" style="width: 100%;" id="statuses_<?php echo e(ENTITY_INVOICE); ?>" multiple="true">
							            <?php $__currentLoopData = \App\Models\EntityModel::getStatusesFor(ENTITY_INVOICE); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							                <option value="<?php echo e($key); ?>"><?php echo e($value); ?></option>
							            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</select>
								</div>
							</div>
						</div>

						<div id="dateField" style="display:none" data-bind="visible: showInvoiceOrPaymentDate">
                            <?php echo Former::select('date_field')->label(trans('texts.filter'))
                                    ->addOption(trans('texts.invoice_date'), FILTER_INVOICE_DATE)
                                    ->addOption(trans('texts.payment_date'), FILTER_PAYMENT_DATE); ?>

                        </div>

						<div id="currencyType" style="display:none" data-bind="visible: showCurrencyType">
                            <?php echo Former::select('currency_type')->label(trans('texts.currency'))
                                    ->addOption(trans('texts.default'), 'default')
                                    ->addOption(trans('texts.converted'), 'converted'); ?>

                        </div>

						<div id="invoiceOrExpenseField" style="display:none" data-bind="visible: showInvoiceOrExpense">
							<?php echo Former::select('document_filter')->label('filter')
								->addOption(trans('texts.all'), '')
									->addOption(trans('texts.invoice'), 'invoice')
									->addOption(trans('texts.expense'), 'expense'); ?>

						</div>

        		  </div>
        </div>
	</div>
    </div>

	<?php if(!Auth::user()->hasFeature(FEATURE_REPORTS)): ?>
	<script>
		$(function() {
			$('form.report-form').find('input, button').prop('disabled', true);
		});
	</script>
	<?php endif; ?>


	<center class="buttons form-inline">
		<span class="well" style="padding-right:8px; padding-left:14px;">
		<?php echo Former::select('format')
					->data_bind("options: export_formats, optionsText: 'transFormat', optionsValue: 'format', value: export_format")
					->raw(); ?> &nbsp;

		<?php echo Button::normal(trans('texts.export'))
				->withAttributes(['style' => 'display:none', 'onclick' => 'onExportClick()', 'data-bind' => 'visible: showExportButton'])
				->appendIcon(Icon::create('download-alt')); ?>


		<?php echo Button::normal(trans('texts.cancel_schedule'))
				->withAttributes(['id' => 'cancelSchduleButton', 'onclick' => 'onCancelScheduleClick()', 'style' => 'display:none', 'data-bind' => 'visible: showCancelScheduleButton'])
				->appendIcon(Icon::create('remove')); ?>


		<?php echo Button::primary(trans('texts.schedule'))
				->withAttributes(['id'=>'scheduleButton', 'onclick' => 'showScheduleModal()', 'style' => 'display:none', 'data-bind' => 'visible: showScheduleButton, css: enableScheduleButton'])
				->appendIcon(Icon::create('time')); ?>


	 	</span> &nbsp;&nbsp;

		<?php echo Button::success(trans('texts.run'))
				->withAttributes(array('id' => 'submitButton'))
				->submit()
				->appendIcon(Icon::create('play'))
				->large(); ?>


		<?php if(request()->report_type): ?>
			<button id="popover" type="button" class="btn btn-default btn-lg">
			  <?php echo e(trans('texts.columns')); ?>

			  <?php echo Icon::create('th-list'); ?>

			</button>

			<div class="hidden">
			  <div id="popover-target"></div>
			</div>
		<?php endif; ?>

	</center>

	<?php if(request()->report_type): ?>

		<?php echo $__env->make('reports.chart_builder', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <div class="panel panel-default">
        <div class="panel-body">

        <?php if(count(array_values($reportTotals))): ?>
        <table class="tablesorter tablesorter-totals" style="display:none">
        <thead>
            <tr>
                <th><?php echo e(trans("texts.totals")); ?></th>
				<?php $__currentLoopData = array_values(array_values($reportTotals)[0])[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th><?php echo e(trans("texts.{$key}")); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $reportTotals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyId => $each): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<?php $__currentLoopData = $each; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dimension => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                <tr>
	                    <td><?php echo Utils::getFromCache($currencyId, 'currencies')->name; ?>

						<?php if($dimension): ?>
							- <?php echo e($dimension); ?>

						<?php endif; ?>
						</td>
	                    <?php $__currentLoopData = $val; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<td>
								<?php if($field == 'duration'): ?>
									<?php echo e(Utils::formatTime($value)); ?>

								<?php else: ?>
		                        	<?php echo e(Utils::formatMoney($value, $currencyId)); ?>

								<?php endif; ?>
							</td>
	                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	                </tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        </table>
		<p>&nbsp;</p>
        <?php endif; ?>

        <table id="<?php echo e(request()->report_type); ?>Report" class="tablesorter tablesorter-data" style="display:none">
        <thead>
            <tr>
				<?php echo $report ? $report->tableHeader() : ''; ?>

            </tr>
        </thead>
        <tbody>
            <?php if(count($displayData)): ?>
                <?php $__currentLoopData = $displayData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <?php $__currentLoopData = $record; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td><?php echo $field; ?></td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" style="text-align: center"><?php echo e(trans('texts.empty_table')); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
        </table>

		<br/>
		<div style="color:#888888">
			<?php echo e(trans('texts.reports_help')); ?>

		</div>

        </div>
        </div>

	</div>

	<?php endif; ?>

	<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel"><?php echo e(trans('texts.scheduled_report')); ?></h4>
            </div>

            <div class="container" style="width: 100%; padding-bottom: 0px !important">
            <div class="panel panel-default">
            <div class="panel-body">

				<center style="padding-bottom:40px;font-size:16px;">
					<div id="scheduleHelp"></div>
				</center>

				<?php echo Former::select('range')
							->addOption(trans('texts.none'), '')
							->addOption(trans('texts.this_month'), 'this_month')
							->addOption(trans('texts.last_month'), 'last_month')
							->addOption(trans('texts.current_quarter'), 'this_quarter')
							->addOption(trans('texts.last_quarter'), 'last_quarter')
							->addOption(trans('texts.this_year'), 'this_year')
							->addOption(trans('texts.last_year'), 'last_year')
							->value(''); ?>


				<?php echo Former::select('frequency')
							->addOption(trans('texts.freq_daily'), REPORT_FREQUENCY_DAILY)
							->addOption(trans('texts.freq_weekly'), REPORT_FREQUENCY_WEEKLY)
							->addOption(trans('texts.freq_biweekly'), REPORT_FREQUENCY_BIWEEKLY)
							->addOption(trans('texts.freq_monthly'), REPORT_FREQUENCY_MONTHLY)
							->value('weekly'); ?> &nbsp;

				<?php echo Former::text('send_date')
						->data_date_format(Session::get(SESSION_DATE_PICKER_FORMAT, DEFAULT_DATE_PICKER_FORMAT))
						->label('start_date')
						->appendIcon('calendar')
						->placeholder('')
						->addGroupClass('send-date')
						->data_date_start_date($account->formatDate($account->getDateTime())); ?>


            </div>
            </div>
            </div>

            <div class="modal-footer" id="signUpFooter" style="margin-top: 0px">
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(trans('texts.cancel')); ?> </button>
              <button type="button" class="btn btn-success" onclick="onScheduleClick()"><?php echo e(trans('texts.schedule')); ?> </button>
            </div>
          </div>
        </div>
    </div>

	<?php echo Former::close(); ?>



	<script type="text/javascript">

	var scheduledReports = <?php echo $scheduledReports; ?>;
	var scheduledReportMap = {};

	for (var i=0; i<scheduledReports.length; i++) {
		var schedule = scheduledReports[i];
		var config = JSON.parse(schedule.config);
		scheduledReportMap[config.report_type] = schedule.public_id;
	}

	function showScheduleModal() {
		var help = "<?php echo e(trans('texts.scheduled_report_help')); ?>";
		help = help.replace(':email', "<?php echo e(auth()->user()->email); ?>");
		help = help.replace(':format', $("#format").val().toUpperCase());
		help = help.replace(':report', $("#report_type option:selected").text());
		$('#scheduleHelp').text(help);
        $('#scheduleModal').modal('show');
    }

	function onExportClick() {
        $('#action').val('export');
        $('#submitButton').click();
		$('#action').val('');
    }

	function onScheduleClick(frequency) {
        $('#action').val('schedule');
        $('#submitButton').click();
		$('#action').val('');
    }

	function onCancelScheduleClick() {
		sweetConfirm(function() {
			var reportType = $('#report_type').val();
			$('#action').val('cancel_schedule');
			$('#frequency').val(frequency);
			$('#scheduled_report_id').val(scheduledReportMap[reportType]);
	        $('#submitButton').click();
			$('#action').val('');
		});
	}

	var sumColumns = [];
	<?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column => $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		sumColumns.push("<?php echo e(in_array($column, ['amount', 'paid', 'balance', 'cost', 'duration', 'tax', 'qty']) ? trans("texts.{$column}") : false); ?>");
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    $(function() {
        $('.start_date .input-group-addon').click(function() {
            toggleDatePicker('start_date');
        });
        $('.end_date .input-group-addon').click(function() {
            toggleDatePicker('end_date');
        });

		$('#document_filter').change(function() {
			var val = $('#document_filter').val();
            if (isStorageSupported()) {
                localStorage.setItem('last:document_filter', val);
            }
        });

		$('#format').change(function() {
			if (! isStorageSupported()) {
				return;
			}
			setTimeout(function() {
				localStorage.setItem('last:report_format', model.export_format());
			}, 1);
        });

        $('#report_type').change(function() {
            if (! isStorageSupported()) {
				return;
			}
			setTimeout(function() {
				localStorage.setItem('last:report_type', model.report_type());
			}, 1);
        });

		$('#group').change(function() {
			if (! isStorageSupported()) {
				return;
			}
			setTimeout(function() {
				localStorage.setItem('last:report_group', model.group());
			}, 1);
        });

		$('#subgroup').change(function() {
			if (! isStorageSupported()) {
				return;
			}
			setTimeout(function() {
				localStorage.setItem('last:report_subgroup', model.subgroup());
			}, 1);
        });

		function ReportTypeModel(type, transType) {
			var self = this;
			self.type = type;
			self.transType = transType;
		}

		function ExportFormatModel(format, transFormat) {
			var self = this;
			self.format = format;
			self.transFormat = transFormat;
		}

		function GroupModel(period, transPeriod) {
			var self = this;
			self.period = period;
			self.transPeriod = transPeriod;
		}

		function SubgroupModel(field, transField) {
			var self = this;
			self.field = field;
			self.transField = transField;
		}

		function ViewModel() {
			var self = this;
			self.report_types = ko.observableArray();
			self.report_type = ko.observable();
			self.export_format = ko.observable();
			self.start_date = ko.observable();
			self.end_date = ko.observable();
			self.group = ko.observable();
			self.subgroup = ko.observable();

			<?php $__currentLoopData = $reportTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				self.report_types.push(new ReportTypeModel("<?php echo e($key); ?>", "<?php echo e($val); ?>"));
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

			self.groups = ko.observableArray([
				new GroupModel('', ''),
				new GroupModel('day', '<?php echo e(trans('texts.day')); ?>'),
				new GroupModel('monthyear', '<?php echo e(trans('texts.month')); ?>'),
				new GroupModel('year', '<?php echo e(trans('texts.year')); ?>'),
			]);

			self.subgroups = ko.computed(function() {
				var reportType = self.report_type();

				var options = [
					new SubgroupModel('', '')
				];

				if (['client'].indexOf(reportType) == -1) {
					options.push(new SubgroupModel('client', "<?php echo e(trans('texts.client')); ?>"));
				}

				options.push(new SubgroupModel('user', "<?php echo e(trans('texts.user')); ?>"));

				if (reportType == 'activity') {
					options.push(new SubgroupModel('category', "<?php echo e(trans('texts.category')); ?>"));
				} else if (reportType == 'aging') {
					options.push(new SubgroupModel('age', "<?php echo e(trans('texts.age')); ?>"));
				} else if (reportType == 'expense') {
					options.push(new SubgroupModel('vendor', "<?php echo e(trans('texts.vendor')); ?>"));
					options.push(new SubgroupModel('category', "<?php echo e(trans('texts.category')); ?>"));
				} else if (reportType == 'payment') {
					options.push(new SubgroupModel('method', "<?php echo e(trans('texts.method')); ?>"));
				} else if (reportType == 'profit_and_loss') {
					options.push(new SubgroupModel('type', "<?php echo e(trans('texts.type')); ?>"));
				} else if (reportType == 'task' || reportType == 'task_details') {
					options.push(new SubgroupModel('project', "<?php echo e(trans('texts.project')); ?>"));
				} else if (reportType == 'client') {
					options.push(new SubgroupModel('country', "<?php echo e(trans('texts.country')); ?>"));
				} else if (reportType == 'invoice' || reportType == 'quote') {
					options.push(new SubgroupModel('status', "<?php echo e(trans('texts.status')); ?>"));
				} else if (reportType == 'product') {
					options.push(new SubgroupModel('product', "<?php echo e(trans('texts.product')); ?>"));
				}

				return options;
			});

			self.export_formats = ko.computed(function() {
				var options = [
					new ExportFormatModel('csv', 'CSV'),
					new ExportFormatModel('xlsx', 'XLSX'),
					//new ExportFormatModel('pdf', 'PDF'),
				]

				if (['<?php echo e(ENTITY_INVOICE); ?>', '<?php echo e(ENTITY_QUOTE); ?>', '<?php echo e(ENTITY_EXPENSE); ?>', '<?php echo e(ENTITY_DOCUMENT); ?>'].indexOf(self.report_type()) >= 0) {
					options.push(new ExportFormatModel('zip', 'ZIP - <?php echo e(trans('texts.documents')); ?>'));
				}

				if (['<?php echo e(ENTITY_INVOICE); ?>'].indexOf(self.report_type()) >= 0) {
					options.push(new ExportFormatModel('zip-invoices', 'ZIP - <?php echo e(trans('texts.invoices')); ?>'));
				}

				return options;
			});

			if (isStorageSupported()) {
				var lastReportType = localStorage.getItem('last:report_type');
				if (lastReportType) {
					self.report_type(lastReportType);
				}
				var lastGroup = localStorage.getItem('last:report_group');
				if (lastGroup) {
					self.group(lastGroup);
				}
				var lastSubgroup = localStorage.getItem('last:report_subgroup');
				if (lastSubgroup) {
					self.subgroup(lastSubgroup);
				}
				var lastFormat = localStorage.getItem('last:report_format');
				if (lastFormat) {
					self.export_format(lastFormat);
				}
			}

			self.showSubgroup = ko.computed(function() {
				return self.group();
			})

			self.showInvoiceOrPaymentDate = ko.computed(function() {
				return self.report_type() == '<?php echo e(ENTITY_TAX_RATE); ?>';
			});

			self.showStatus = ko.computed(function() {
				return ['<?php echo e(ENTITY_INVOICE); ?>', '<?php echo e(ENTITY_QUOTE); ?>', '<?php echo e(ENTITY_PRODUCT); ?>'].indexOf(self.report_type()) >= 0;
			});

			self.showInvoiceOrExpense = ko.computed(function() {
				return self.report_type() == '<?php echo e(ENTITY_DOCUMENT); ?>';
			});

			self.showCurrencyType = ko.computed(function() {
				return self.report_type() == '<?php echo e(ENTITY_PAYMENT); ?>';
			});

			self.enableScheduleButton = ko.computed(function() {
				return ['zip', 'zip-invoices'].indexOf(self.export_format()) >= 0 ? 'disabled' : 'enabled';
			});

			self.showScheduleButton = ko.computed(function() {
				return ! scheduledReportMap[self.report_type()];
			});

			self.showCancelScheduleButton = ko.computed(function() {
				return !! scheduledReportMap[self.report_type()];
			});

            self.showExportButton = ko.computed(function() {
                return self.export_format() != '';
            });
		}

		$(function(){
			window.model = new ViewModel();
			ko.applyBindings(model);

			var statusIds = isStorageSupported() ? (localStorage.getItem('last:report_status_ids') || '') : '';
			$('#statuses_<?php echo e(ENTITY_INVOICE); ?>').select2({
				//allowClear: true,
			}).val(statusIds.split(',')).trigger('change')
			  	.on('change', function() {
					if (isStorageSupported()) {
						var filter = $('#statuses_<?php echo e(ENTITY_INVOICE); ?>').val();
						if (filter) {
							filter = filter.join(',');
						} else {
							filter = '';
						}
						localStorage.setItem('last:report_status_ids', filter);
					}
				}).maximizeSelect2Height();

  			$(".tablesorter-data").tablesorter({
				<?php if(! request()->group): ?>
					sortList: [[0,0]],
				<?php endif; ?>
				theme: 'bootstrap',
				widgets: ['zebra', 'uitheme', 'filter'<?php echo request()->group ? ", 'group'" : ""; ?>, 'columnSelector'],
				headerTemplate : '{content} {icon}',
				<?php if($report): ?>
					dateFormat: '<?php echo e($report->convertDateFormat()); ?>',
				<?php endif; ?>
				numberSorter: function(a, b, direction) {
					var a = convertStringToNumber(a);
					var b = convertStringToNumber(b);
					return direction ? a - b : b - a;
				},
				widgetOptions : {
					columnSelector_mediaqueryName: "<?php echo e(trans('texts.auto')); ?>",
					columnSelector_mediaqueryHidden: true,
					columnSelector_saveColumns: true,
					//storage_useSessionStorage: true,
					filter_cssFilter: 'form-control',
					group_collapsed: true,
					group_saveGroups: false,
					//group_formatter   : function(txt, col, table, c, wo, data) {},
					group_callback: function ($cell, $rows, column, table) {
					  for (var i=0; i<sumColumns.length; i++) {
						  var label = sumColumns[i];
						  if (!label) {
							  continue;
						  }
						  var subtotal = 0;
				          $rows.each(function() {
				            var txt = $(this).find("td").eq(i).text();
				            subtotal += convertStringToNumber(txt) || 0;
				          });
				          $cell.find(".group-count").append(' | ' + label + ': ' + roundToTwo(subtotal, true));
					  }
			        },
			    }
			}).show();

			<?php if(request()->report_type): ?>
				$.tablesorter.columnSelector.attachTo( $('.tablesorter-data'), '#popover-target');
				$('#popover')
				.popover({
					placement: 'right',
					html: true, // required if content has HTML
					content: $('#popover-target')
				});
			<?php endif; ?>

			$(".tablesorter-totals").tablesorter({
				theme: 'bootstrap',
				widgets: ['zebra', 'uitheme'],
			}).show();

			if (isStorageSupported()) {
				var lastDocumentFilter = localStorage.getItem('last:document_filter');
				if (lastDocumentFilter) {
					$('#document_filter').val(lastDocumentFilter);
				}
			}
		});
    })


	</script>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('onReady'); ?>

	$('#start_date, #end_date').datepicker({
		autoclose: true,
		todayHighlight: true,
		keyboardNavigation: false
	});

	var currentDate = new Date();
	currentDate.setDate(currentDate.getDate() + 1);
	$('#send_date').datepicker('update', currentDate);

<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>