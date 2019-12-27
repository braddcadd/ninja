<?php $__env->startSection('content'); ?>

	<style type="text/css">
        table.dataTable thead > tr > th, table.invoice-table thead > tr > th {
            background-color: <?php echo e($color); ?> !important;
        }

        .pagination>.active>a,
        .pagination>.active>span,
        .pagination>.active>a:hover,
        .pagination>.active>span:hover,
        .pagination>.active>a:focus,
        .pagination>.active>span:focus {
            background-color: <?php echo e($color); ?>;
            border-color: <?php echo e($color); ?>;
        }

        table.table thead .sorting:after { content: '' !important }
        table.table thead .sorting_asc:after { content: '' !important }
        table.table thead .sorting_desc:after { content: '' !important }
        table.table thead .sorting_asc_disabled:after { content: '' !important }
        table.table thead .sorting_desc_disabled:after { content: '' !important }

		<?php for($i = 0; $i < count($columns); $i++): ?>
			table.dataTable td:nth-child(<?php echo e($i + 1); ?>) {
				<?php if($columns[$i] == trans('texts.status')): ?>
					text-align: center;
				<?php endif; ?>
			}
		<?php endfor; ?>

	</style>

	<div class="container" id="main-container">

		<p>&nbsp;</p>

		<!--
		<div id="top_right_buttons" class="pull-right">
			<input id="tableFilter" type="text" style="width:140px;margin-right:17px" class="form-control pull-left" placeholder="<?php echo e(trans('texts.filter')); ?>"/>
		</div>
		-->

        <?php if(($entityType == ENTITY_INVOICE || $entityType == ENTITY_RECURRING_QUOTE) && $client->hasRecurringInvoices()): ?>
            <div class="pull-right" style="margin-top:5px">
                <?php echo Button::primary(trans("texts.recurring_invoices"))->asLinkTo(URL::to('/client/invoices/recurring')); ?>

            </div>
        <?php endif; ?>

        <?php if($entityType == ENTITY_TICKET): ?>
            <?php echo Button::primary(trans('texts.new_ticket'))
            ->asLinkTo(URL::to('/client/tickets/create'))
            ->withAttributes(['class' => 'pull-right'])
            ->appendIcon(Icon::create('plus-sign')); ?>

        <?php endif; ?>


        <?php if(($entityType == ENTITY_QUOTE || $entityType == ENTITY_RECURRING_INVOICE) && $client->hasRecurringQuotes()): ?>
            <div class="pull-right" style="margin-top:5px">
                <?php echo Button::primary(trans("texts.recurring_quotes"))->asLinkTo(URL::to('/client/invoices/recurring_quotes')); ?>

            </div>
        <?php endif; ?>

        <h3><?php echo e($title); ?></h3>

		<?php echo Datatable::table()
	    	->addColumn($columns)
	    	->setUrl(route('api.client.' . $entityType . 's'))
	    	->setOptions('sPaginationType', 'bootstrap')
			->setOptions('aaSorting', [[$sortColumn, 'desc']])
	    	->render('datatable'); ?>

	</div>

    <?php if($entityType == ENTITY_RECURRING_INVOICE): ?>
        <?php echo Former::open(URL::to('/client/invoices/auto_bill'))->id('auto_bill_form'); ?>

        <input type="hidden" name="public_id" id="auto_bill_public_id">
        <input type="hidden" name="enable" id="auto_bill_enable">
        <?php echo Former::close(); ?>


        <script type="text/javascript">
            function setAutoBill(publicId, enable){
                $('#auto_bill_public_id').val(publicId);
                $('#auto_bill_enable').val(enable?'1':'0');
                $('#auto_bill_form').submit();
            }
        </script>
    <?php endif; ?>


	<p>&nbsp;</p>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('public.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>