<?php $__env->startSection('head'); ?>
    ##parent-placeholder-1a954628a960aaef81d7b2d4521929579f3541e6##

    <script src="<?php echo e(asset('js/select2.min.js')); ?>" type="text/javascript"></script>
    <link href="<?php echo e(asset('css/select2.css')); ?>" rel="stylesheet" type="text/css"/>
    <script src="<?php echo e(asset('js/Chart.min.js')); ?>" type="text/javascript"></script>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-md-7">
            <ol class="breadcrumb">
              <li><?php echo e(link_to('/projects', trans('texts.projects'))); ?></li>
              <li class='active'><?php echo e($project->name); ?></li> <?php echo $project->present()->statusLabel; ?>

            </ol>
        </div>
        <div class="col-md-5">
            <div class="pull-right">

                <?php echo Former::open('projects/bulk')->autocomplete('off')->addClass('mainForm'); ?>

            		<div style="display:none">
            			<?php echo Former::text('action'); ?>

            			<?php echo Former::text('public_id')->value($project->public_id); ?>

            		</div>

                <?php if( ! $project->is_deleted): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit', $project)): ?>
                        <?php echo DropdownButton::normal(trans('texts.edit_project'))
                            ->withAttributes(['class'=>'normalDropDown'])
                            ->withContents([
                              ($project->trashed() ? false : ['label' => trans('texts.archive_project'), 'url' => "javascript:onArchiveClick()"]),
                              ['label' => trans('texts.delete_project'), 'url' => "javascript:onDeleteClick()"],
                            ]
                          )->split(); ?>

                    <?php endif; ?>
                <?php endif; ?>

                <?php if($project->trashed()): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit', $project)): ?>
                        <?php echo Button::primary(trans('texts.restore_project'))
                                ->appendIcon(Icon::create('cloud-download'))
                                ->withAttributes(['onclick' => 'onRestoreClick()']); ?>

                    <?php endif; ?>
                <?php endif; ?>

                <?php echo Former::close(); ?>


            </div>
        </div>
    </div>


    <div class="panel panel-default">
    <div class="panel-body">
	<div class="row">
        <div class="col-md-3">
			<h3><?php echo e(trans('texts.details')); ?></h3>
            <h4>
                <?php echo $project->client->present()->link; ?><br/>
            </h4>
            <?php if($project->due_date): ?>
                <?php echo e(trans('texts.due_date') . ': ' . Utils::fromSqlDate($project->due_date)); ?><br/>
            <?php endif; ?>
            <?php if($project->budgeted_hours): ?>
                <?php echo e(trans('texts.budgeted_hours') . ': ' . $project->budgeted_hours); ?><br/>
            <?php endif; ?>
            <?php if($project->present()->defaultTaskRate): ?>
                <?php echo e(trans('texts.task_rate') . ': ' . $project->present()->defaultTaskRate); ?><br/>
            <?php endif; ?>

            <?php if($account->customLabel('project1') && $project->custom_value1): ?>
                <?php echo e($account->present()->customLabel('project1') . ': '); ?> <?php echo nl2br(e($project->custom_value1)); ?><br/>
            <?php endif; ?>
            <?php if($account->customLabel('project2') && $project->custom_value2): ?>
                <?php echo e($account->present()->customLabel('project2') . ': '); ?> <?php echo nl2br(e($project->custom_value2)); ?><br/>
            <?php endif; ?>

        </div>

        <div class="col-md-3">
			<h3><?php echo e(trans('texts.notes')); ?></h3>
            <?php echo e($project->private_notes); ?>

        </div>

        <div class="col-md-4">
            <h3><?php echo e(trans('texts.summary')); ?>

			<table class="table" style="width:100%">
				<tr>
					<td><small><?php echo e(trans('texts.tasks')); ?></small></td>
					<td style="text-align: right"><?php echo e($chartData->count); ?></td>
				</tr>
				<tr>
					<td><small><?php echo e(trans('texts.duration')); ?></small></td>
					<td style="text-align: right">
                        <?php echo e(Utils::formatTime($chartData->duration)); ?>

                        <?php if(floatval($project->budgeted_hours)): ?>
            				[<?php echo e(round($chartData->duration / ($project->budgeted_hours * 60 * 60) * 100)); ?>%]
                        <?php endif; ?>
                    </td>
				</tr>
			</table>
			</h3>

        </div>

    </div>
    </div>
    </div>

    <?php if($chartData->duration): ?>
        <canvas id="chart-canvas" height="50px" style="background-color:white;padding:20px;display:none"></canvas><br/>
    <?php endif; ?>

    <ul class="nav nav-tabs nav-justified">
		<?php echo Form::tab_link('#tasks', trans('texts.tasks')); ?>

	</ul><br/>

	<div class="tab-content">
        <div class="tab-pane" id="tasks">
            <?php echo $__env->make('list', [
                'entityType' => ENTITY_TASK,
                'datatable' => new \App\Ninja\Datatables\ProjectTaskDatatable(true, true),
                'projectId' => $project->public_id,
                'clientId' => $project->client->public_id,
                'url' => url('api/tasks/' . $project->client->public_id . '/' . $project->public_id),
            ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
    </div>

    <script type="text/javascript">

    var loadedTabs = {};

	$(function() {
		$('.normalDropDown:not(.dropdown-toggle)').click(function(event) {
            openUrlOnClick('<?php echo e(URL::to('projects/' . $project->public_id . '/edit')); ?>', event)
		});
		$('.primaryDropDown:not(.dropdown-toggle)').click(function(event) {
			openUrlOnClick('<?php echo e(URL::to('tasks/create/' . $project->client->public_id . '/' . $project->public_id )); ?>', event);
		});

        $('.nav-tabs a[href="#tasks"]').tab('show');

        var chartData = <?php echo json_encode($chartData); ?>;
        loadChart(chartData);
	});

	function onArchiveClick() {
		$('#action').val('archive');
		$('.mainForm').submit();
	}

	function onRestoreClick() {
		$('#action').val('restore');
		$('.mainForm').submit();
	}

	function onDeleteClick() {
		if (confirm(<?php echo json_encode(trans('texts.are_you_sure')); ?>)) {
			$('#action').val('delete');
			$('.mainForm').submit();
		}
	}

    function loadChart(data) {
        if (! data.duration) {
            return;
        }
        var ctx = document.getElementById('chart-canvas').getContext('2d');
        $('#chart-canvas').fadeIn();
        window.myChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                legend: {
                    display: false,
                },
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            unit: 'day',
                            round: 'day',
                        },
                        gridLines: {
                            display: false,
                        },
                    }],
                    yAxes: [{
                        ticks: {
                            <?php if($project->budgeted_hours): ?>
                                max: <?php echo e(max($project->budgeted_hours, $chartData->duration / 60 / 60)); ?>,
                            <?php endif; ?>
                            beginAtZero: true,
                            callback: function(label, index, labels) {
                                return roundToTwo(label) + " <?php echo e(trans('texts.hours')); ?>";
                            }
                        },
                    }]
                }
            }
        });
    }


	</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>