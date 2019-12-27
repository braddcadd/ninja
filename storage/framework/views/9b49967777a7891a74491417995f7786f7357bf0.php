<?php $__env->startSection('head'); ?>
	##parent-placeholder-1a954628a960aaef81d7b2d4521929579f3541e6##

    <script src="<?php echo e(asset('js/select2.min.js')); ?>" type="text/javascript"></script>
    <link href="<?php echo e(asset('css/select2.css')); ?>" rel="stylesheet" type="text/css"/>

    <script src="<?php echo e(asset('js/fullcalendar.min.js')); ?>" type="text/javascript"></script>
    <link href="<?php echo e(asset('css/fullcalendar.css')); ?>" rel="stylesheet" type="text/css"/>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('head_css'); ?>
	##parent-placeholder-65e7fa855b4f81a209a50c6e440870f25d0240e1##

	<style type="text/css">
		.fc-day,
		.fc-list-item {
			background-color: white;
		}
	</style>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('top-right'); ?>
	<div id="entityTypeFilterWrapper" style="display:none">
	    <select class="form-control" style="width: 220px;" id="entityTypeFilter" multiple="true">
	        <?php $__currentLoopData = [ENTITY_INVOICE, ENTITY_PAYMENT, ENTITY_QUOTE, ENTITY_PROJECT, ENTITY_TASK, ENTITY_EXPENSE]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	            <option value="<?php echo e($value); ?>"><?php echo e(trans("texts.{$value}")); ?></option>
	        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	    </select>
	</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<?php if(!Utils::isPro()): ?>
		<div class="alert alert-warning" style="font-size:larger">
			<center>
				<?php echo trans('texts.pro_plan_calendar', ['link'=>'<a href="javascript:showUpgradeModal()">' . trans('texts.pro_plan_remove_logo_link') . '</a>']); ?>

			</center>
		</div>
	<?php endif; ?>

    <div id='calendar'></div>

    <script type="text/javascript">

		$(function() {

			var lastFilter = false;
			var lastView = 'month';

			if (isStorageSupported()) {
				lastFilter = JSON.parse(localStorage.getItem('last:calendar_filter'));
				lastView = localStorage.getItem('last:calendar_view') || lastView;
			}

			// Setup state/status filter
			$('#entityTypeFilter').select2({
				placeholder: "<?php echo e(trans('texts.filter')); ?>",
			}).val(lastFilter).trigger('change').on('change', function() {
				$('#calendar').fullCalendar('refetchEvents');
				if (isStorageSupported()) {
					var filter = JSON.stringify($('#entityTypeFilter').val());
					localStorage.setItem('last:calendar_filter', filter);
				}
			}).maximizeSelect2Height();
			$('#entityTypeFilterWrapper').show();

			$('#calendar').fullCalendar({
				locale: '<?php echo e(App::getLocale()); ?>',
				firstDay: <?php echo e($account->start_of_week ?: '0'); ?>,
				defaultView: lastView,
				viewRender: function(view, element) {
					if (isStorageSupported()) {
						localStorage.setItem('last:calendar_view', view.name);
					}
				},
				header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,basicWeek,basicDay,listWeek'
				},
				defaultDate: '<?php echo e(date('Y-m-d')); ?>',
				eventLimit: true,
				events: {
					url: '<?php echo e(url('/reports/calendar_events')); ?>',
					type: 'GET',
					data: function() {
						return {
							filter: $('#entityTypeFilter').val()
						};
					},
					error: function() {
						alert("<?php echo e(trans('texts.error_refresh_page')); ?>");
					},
				}
			});
		});

    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>